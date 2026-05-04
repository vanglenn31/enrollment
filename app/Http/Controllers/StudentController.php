<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function dashboard()
    {
        $user = Auth::user()->load('profile.student.programRelation');
        $student = $user->profile?->student;

        $enrollments = $student ? $student->studentEnrollments()->with('course.professor.profile')->get() : collect();
        $payments = $student
            ? Payment::whereIn('student_enrollment_id', $enrollments->pluck('id'))->with('studentEnrollment.course')->orderByDesc('payment_date')->get()
            : collect();

        $downpaymentPaid = $payments->where('payment_status', 'paid')->sum('amount');
        $pendingAmount = $payments->where('payment_status', 'pending')->sum('amount');
        $hasDownpayment = $payments->where('payment_status', 'paid')->isNotEmpty();
        $verified = $student?->is_verified == true;
        $nextAction = 'Waiting for admin assignment';

        if (!$student) {
            $nextAction = 'Student record not found.';
        } elseif (!$hasDownpayment) {
            $nextAction = 'A downpayment is required before enrollment.';
        } elseif (!$verified) {
            $nextAction = 'Your account must be verified by admin before enrollment.';
        } elseif ($enrollments->isEmpty()) {
            $nextAction = 'Waiting for admin to enroll you in courses.';
        } else {
            $nextAction = 'You are now enrolled and can track progress below.';
        }

        return view('student.dashboard', compact(
            'student',
            'enrollments',
            'payments',
            'downpaymentPaid',
            'pendingAmount',
            'hasDownpayment',
            'verified',
            'nextAction'
        ));
    }

    public function course()
    {
        $user = Auth::user()->load('profile.student.programRelation');
        $student = $user->profile?->student;
        $currentEnrollments = $student?->studentEnrollments()->with('course')->get() ?? collect();
        $enrolledCourseIds = $currentEnrollments->pluck('course_id')->toArray();

        $generalEducationProgram = Program::firstWhere('name', 'General Education');
        $allowedProgramIds = $student ? array_filter([$student->program, $generalEducationProgram?->id]) : [];

        $availableCourses = Course::with('program')
            ->when($student, function ($query) use ($allowedProgramIds) {
                $query->whereIn('program_id', $allowedProgramIds);
            })
            ->when($enrolledCourseIds, function ($query) use ($enrolledCourseIds) {
                $query->whereNotIn('id', $enrolledCourseIds);
            })
            ->get();

        $canSelfEnroll = $student && $student->status === 'verified';
        $message = 'Your account must be verified by admin before you can select courses.';

        if (!$student) {
            $message = 'Student record not found. Please contact the registrar.';
        } elseif ($canSelfEnroll) {
            $message = 'Choose from your program courses or general education courses only.';
        }

        return view('student.course', compact('student', 'currentEnrollments', 'availableCourses', 'canSelfEnroll', 'message'));
    }

    public function enlistCourse(Request $request)
    {
        $user = Auth::user()->load('profile.student');
        $student = $user->profile?->student;

        if (!$student) {
            return back()->with('error', 'Student record not found.');
        }

        if ($student->status !== 'verified') {
            return back()->with('error', 'Your account must be verified by admin before you can select courses.');
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        $generalEducationProgram = Program::firstWhere('name', 'General Education');
        $allowedProgramIds = array_filter([$student->program, $generalEducationProgram?->id]);

        if (!in_array($course->program_id, $allowedProgramIds, true)) {
            return back()->with('error', 'You may only enlist courses from your program or general education.');
        }

        if ($student->studentEnrollments()->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'You have already enlisted this course.');
        }

        $currentTerm = Term::latest('id')->first() ?? Term::create([
            'school_year' => date('Y') . '-' . (date('Y') + 1),
            'semester' => 'First',
        ]);

        $student->studentEnrollments()->create([
            'course_id' => $course->id,
            'enrollment_date' => now(),
            'term_id' => $currentTerm->id,
            'status' => 'enrolled',
        ]);

        return back()->with('success', 'Course added to your enrollment successfully.');
    }

    public function enrollment()
    {
        $user = Auth::user()->load('profile.student.programRelation');
        $student = $user->profile?->student;
        $enrollments = $student?->studentEnrollments()->with('course')->get() ?? collect();
        $payments = $student
            ? Payment::whereIn('student_enrollment_id', $enrollments->pluck('id'))->orderByDesc('payment_date')->get()
            : collect();
        $hasDownpayment = $payments->where('payment_status', 'paid')->isNotEmpty();

        return view('student.enrollment', compact('student', 'enrollments', 'payments', 'hasDownpayment'));
    }

    public function payment()
    {
        $user = Auth::user()->load('profile.student.programRelation');
        $student = $user->profile?->student;
        $enrollments = $student?->studentEnrollments()->pluck('id') ?? collect();
        $payments = $student
            ? Payment::whereIn('student_enrollment_id', $enrollments)->orderByDesc('payment_date')->get()
            : collect();
        $totalPaid = $payments->where('payment_status', 'paid')->sum('amount');
        $totalPending = $payments->where('payment_status', 'pending')->sum('amount');
        $overdue = $payments->where('payment_status', 'pending')->filter(fn($payment) => $payment->payment_date?->lessThan(now()))->sum('amount');

        return view('student.payments', compact('student', 'payments', 'totalPaid', 'totalPending', 'overdue'));
    }
}
