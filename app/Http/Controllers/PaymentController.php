<?php

namespace App\Http\Controllers;

use App\Models\StudentEnrollment;
use App\Models\EnrolledCourse;
use App\Models\Student;
use App\Models\Course;
use App\Models\Program;
use App\Models\Term;
use App\Models\Payment;
use Illuminate\Http\Request;

class StudentEnrollmentController extends Controller
{
    public function enrollment(Request $request)
    {
        $search = $request->input('search');

        $verifiedStudents = Student::where('is_verified', true)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('student_number', 'like', "%{$search}%")
                      ->orWhereHas('profile', function ($q) use ($search) {
                          $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                      });
                });
            })
            ->with(['profile', 'programRelation', 'studentEnrollments.course'])
            ->orderByDesc('updated_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.enrollment.enrollment', compact('verifiedStudents', 'search'));
    }

    public function assignCoursesForm(Request $request, Student $student)
    {
        $student->refresh();
        $student->load(['profile', 'programRelation', 'studentEnrollments.course']);

        // Check if student has a paid downpayment — pass to view so UI can warn admin
        $hasDownpayment = Payment::whereHas('studentEnrollment', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->where('payment_type', 'downpayment')
            ->where('payment_status', 'paid')
            ->exists();

        $currentCourses    = $student->studentEnrollments()->whereNotNull('course_id')->pluck('course_id')->toArray();
        $generalProgram    = Program::firstWhere('code', 'GEN');
        $allowedProgramIds = array_filter([$student->program, $generalProgram?->id]);

        $availableCourses = Course::whereIn('program_id', $allowedProgramIds)
            ->whereNotIn('id', $currentCourses)
            ->with('program')
            ->get();

        // Paginate by course_name groups (5 per page)
        $grouped     = $availableCourses->groupBy('course_name');
        $perPage     = 5;
        $currentPage = (int) $request->input('course_page', 1);
        $totalGroups = $grouped->count();
        $groupedPage = $grouped->slice(($currentPage - 1) * $perPage, $perPage);

        $groupPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedPage,
            $totalGroups,
            $perPage,
            $currentPage,
            [
                'path'     => $request->url(),
                'query'    => array_merge($request->query(), ['course_page' => null]),
                'pageName' => 'course_page',
            ]
        );

        return view('admin.enrollment.assign-courses', compact(
            'student',
            'currentCourses',
            'availableCourses',
            'groupPaginator',
            'hasDownpayment',
        ));
    }

    public function storeEnrollment(Request $request, Student $student)
    {
        // 1. Active term — single authoritative check
        $currentTerm = Term::active()->first();

        if (!$currentTerm) {
            return back()->withErrors([
                'course_id' => 'No active term found. Please set an active term first.',
            ]);
        }

        if (!$currentTerm->is_enrollment_open) {
            return back()->withErrors([
                'course_id' => "Enrollment is closed for {$currentTerm->label}.",
            ]);
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::findOrFail($validated['course_id']);

        // 2. Downpayment gate — block enrollment until downpayment is paid
        $hasDownpayment = Payment::whereHas('studentEnrollment', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->where('payment_type', 'downpayment')
            ->where('payment_status', 'paid')
            ->exists();

        if (!$hasDownpayment) {
            return back()->withErrors([
                'course_id' => 'Cannot assign courses: student has not completed a downpayment yet.',
            ]);
        }

        // 3. Program validity
        $generalProgram    = Program::firstWhere('code', 'GEN');
        $allowedProgramIds = array_filter([
            (int) $student->program,
            $generalProgram ? (int) $generalProgram->id : null,
        ]);

        if (!in_array((int) $course->program_id, $allowedProgramIds, true)) {
            return back()->withErrors([
                'course_id' => 'This course does not belong to the student\'s program or general education.',
            ]);
        }

        // 4. Duplicate check (scoped to current term)
        $alreadyEnrolled = $student->studentEnrollments()
            ->where('term_id', $currentTerm->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($alreadyEnrolled) {
            return back()->withErrors([
                'course_id' => 'Student is already enrolled in this course for this term.',
            ]);
        }

        // 5. Slot check
        $enrolledCount = StudentEnrollment::where('course_id', $course->id)
            ->where('term_id', $currentTerm->id)
            ->count();

        if ($enrolledCount >= ($course->slots ?? 30)) {
            return back()->withErrors([
                'course_id' => 'Cannot assign course: all slots are full.',
            ]);
        }

        // 6. Schedule conflict check
        $hasTimeConflict = $student->studentEnrollments()
            ->where('term_id', $currentTerm->id)
            ->whereHas('course', function ($q) use ($course) {
                $q->where('schedule_type', $course->schedule_type)
                  ->where('start_time', '<', $course->end_time)
                  ->where('end_time', '>', $course->start_time);
            })
            ->exists();

        if ($hasTimeConflict) {
            return back()->withErrors([
                'course_id' => 'Schedule conflict detected with another enrolled course.',
            ]);
        }

        // 7. Create enrollment + enrolled course record
        $studentEnrollment = $student->studentEnrollments()->create([
            'course_id'       => $course->id,
            'enrollment_date' => now(),
            'term_id'         => $currentTerm->id,
            'status'          => 'enrolled',
            'units'           => $course->units,
        ]);

        EnrolledCourse::create([
            'student_enrollment_id' => $studentEnrollment->id,
            'course_id'             => $course->id,
            'professor_id'          => $course->professor_id ?? null,
            'room_id'               => $course->room_id ?? null,
            'course_price'          => $course->course_price ?? null,
            'grade'                 => null,
        ]);

        // 8. Promote student status if needed
        if ($student->status !== 'enrolled') {
            $student->update(['status' => 'enrolled']);
        }

        return redirect()
            ->route('admin.enrollment.assign', $student)
            ->with('success', 'Course assigned successfully.');
    }

    public function editEnrollment(StudentEnrollment $studentEnrollment)
    {
        $student           = $studentEnrollment->student;
        $generalProgram    = Program::firstWhere('code', 'GEN');
        $assignedCourseIds = $student->studentEnrollments()->pluck('course_id')->filter()->toArray();
        $allowedProgramIds = array_filter([$student->program, $generalProgram?->id]);

        $availableCourses = Course::whereIn('program_id', $allowedProgramIds)
            ->whereNotIn('id', array_filter($assignedCourseIds, fn($id) => $id !== $studentEnrollment->course_id))
            ->with('program')
            ->get();

        return view('admin.enrollment.edit-enrollment', compact('studentEnrollment', 'availableCourses'));
    }

    public function updateEnrollment(Request $request, StudentEnrollment $studentEnrollment)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course            = Course::findOrFail($validated['course_id']);
        $generalProgram    = Program::firstWhere('code', 'GEN');
        $allowedProgramIds = array_filter([$studentEnrollment->student->program, $generalProgram?->id]);

        if (!in_array($course->program_id, $allowedProgramIds, true)) {
            return back()->withErrors([
                'course_id' => 'This course does not belong to the student\'s program or general education.',
            ]);
        }

        if ($studentEnrollment->student->studentEnrollments()
            ->where('course_id', $course->id)
            ->where('id', '<>', $studentEnrollment->id)
            ->exists()
        ) {
            return back()->withErrors([
                'course_id' => 'This student is already enrolled in this course.',
            ]);
        }

        $studentEnrollment->update(['course_id' => $course->id]);

        // Sync the EnrolledCourse record so it doesn't go stale
        EnrolledCourse::where('student_enrollment_id', $studentEnrollment->id)
            ->update([
                'course_id'    => $course->id,
                'professor_id' => $course->professor_id ?? null,
                'room_id'      => $course->room_id ?? null,
                'course_price' => $course->course_price ?? null,
            ]);

        return redirect()
            ->route('admin.enrollment.assign', $studentEnrollment->student)
            ->with('success', 'Course assignment updated successfully.');
    }

    public function removeEnrollment(StudentEnrollment $studentEnrollment)
    {
        $student = $studentEnrollment->student;

        // Delete child EnrolledCourse first to avoid orphaned records
        EnrolledCourse::where('student_enrollment_id', $studentEnrollment->id)->delete();

        $studentEnrollment->delete();

        return redirect()
            ->route('admin.enrollment.assign', $student->id)
            ->with('success', 'Course enrollment removed.');
    }
}