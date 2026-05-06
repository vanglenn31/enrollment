<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Program;
use App\Models\Professor;
use App\Models\Room;
use App\Models\EnrolledCourse;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function courses(Request $request)
    {
        $search = $request->input('search');

        $courses = Course::with('program.department', 'professor.profile')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('course_name', 'like', "%{$search}%")
                      ->orWhere('course_code', 'like', "%{$search}%")
                      ->orWhereHas('program', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('professor.profile', function ($q) use ($search) {
                          $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.course.course', compact('courses', 'search'));
    }

    /**
     * Show enrolled students for a course + allow grading.
     */
    public function showCourse(Course $course)
    {
        $course->load([
            'program',
            'professor.profile',
            'room',
            'enrolledCourses.studentEnrollment.student.profile',
        ]);

        $enrolledCount = $course->enrolledCourses->count();
        $availableSlots = max(0, $course->slots - $enrolledCount);

        return view('admin.course.course-detail', compact('course', 'enrolledCount', 'availableSlots'));
    }

    /**
     * Update grade for a specific enrolled course record.
     */
    public function updateGrade(Request $request, EnrolledCourse $enrolledCourse)
    {
        $validated = $request->validate([
            'grade' => 'nullable|numeric|min:0|max:100',
        ]);

        $enrolledCourse->update([
            'grade' => $validated['grade'],
        ]);

        return back()->with('success', 'Grade updated successfully.');
    }

    public function createCourse()
    {
        $programs = Program::with('department')->get();

        $professors = Professor::with('profile')
            ->where('status', 'active')
            ->get();

        $rooms = Room::orderBy('room_building')
            ->orderBy('room_name')
            ->get();

        return view('admin.course.create-course', compact('programs', 'professors', 'rooms'));
    }

    public function editCourse(Course $course)
    {
        $programs = Program::with('department')->get();

        $professors = Professor::with('profile')
            ->where('status', 'active')
            ->get();

        $rooms = Room::orderBy('room_building')->orderBy('room_name')->get();

        return view('admin.course.edit-course', compact('course', 'programs', 'professors', 'rooms'));
    }

    public function updateCourse(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_name'   => 'required|string|max:255',
            'course_code'   => 'required|string|max:100|unique:courses,course_code,' . $course->id,
            'description'   => 'nullable|string|max:1000',
            'units'         => 'required|integer|min:1',
            'slots'         => 'required|integer|min:1',
            'course_price'  => 'nullable|numeric|min:0',
            'schedule_type' => 'required|in:MWF,TTH,DAILY',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i|after:start_time',
            'program_id'    => 'nullable|exists:programs,id',
            'professor_id'  => 'nullable|exists:professors,id',
            'room_id'       => 'nullable|exists:rooms,id',
        ]);

        $course->update([
            'course_name'   => $validated['course_name'],
            'course_code'   => $validated['course_code'],
            'description'   => $validated['description'] ?? null,
            'units'         => $validated['units'],
            'slots'         => $validated['slots'],
            'course_price'  => $validated['course_price'] ?? null,
            'program_id'    => $validated['program_id'],
            'professor_id'  => $validated['professor_id'],
            'room_id'       => $validated['room_id'],
            'schedule_type' => $validated['schedule_type'],
            'start_time'    => $validated['start_time'],
            'end_time'      => $validated['end_time'],
        ]);

        return redirect()
            ->route('admin.course.course')
            ->with('success', 'Course updated successfully.');
    }

    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'course_name'   => 'required|string|max:255',
            'course_code'   => 'required|string|max:100|unique:courses,course_code',
            'description'   => 'nullable|string|max:1000',
            'units'         => 'required|integer|min:1',
            'slots'         => 'required|integer|min:1',
            'course_price'  => 'nullable|numeric|min:0',
            'schedule_type' => 'required|in:MWF,TTH,DAILY',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i|after:start_time',
            'program_id'    => 'nullable|exists:programs,id',
            'professor_id'  => 'nullable|exists:professors,id',
            'room_id'       => 'nullable|exists:rooms,id',
        ]);

        // Auto assign GEN program if empty
        if (empty($validated['program_id'])) {
            $generalProgram = Program::firstOrCreate(
                ['code' => 'GEN'],
                [
                    'name'          => 'General Education',
                    'department_id' => null,
                ]
            );
            $validated['program_id'] = $generalProgram->id;
        }

        Course::create([
            'course_name'   => $validated['course_name'],
            'course_code'   => $validated['course_code'],
            'description'   => $validated['description'] ?? null,
            'units'         => $validated['units'],
            'slots'         => $validated['slots'],
            'course_price'  => $validated['course_price'] ?? null,
            'program_id'    => $validated['program_id'],
            'professor_id'  => $validated['professor_id'],
            'room_id'       => $validated['room_id'],
            'schedule_type' => $validated['schedule_type'],
            'start_time'    => $validated['start_time'],
            'end_time'      => $validated['end_time'],
        ]);

        return redirect()
            ->route('admin.course.course')
            ->with('success', 'Course added successfully.');
    }

    public function deactivateCourse(Course $course)
    {
        $course->update(['status' => 'inactive']);
        return back()->with('success', 'Course deactivated.');
    }

    public function activateCourse(Course $course)
    {
        $course->update(['status' => 'active']);
        return back()->with('success', 'Course activated.');
    }
}