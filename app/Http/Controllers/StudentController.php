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
    $user    = Auth::user()->load('profile');
    $student = $user->profile->student ?? null;
 
    $activeTerm = \App\Models\Term::active()->first();
 
    // Courses enlisted in the ACTIVE TERM only (resets per term)
    $currentEnrollments = collect();
    if ($student && $activeTerm) {
        $currentEnrollments = \App\Models\EnrolledCourse::whereHas('studentEnrollment', function ($q) use ($student, $activeTerm) {
                $q->where('student_id', $student->id)
                  ->where('term_id', $activeTerm->id);
            })
            ->with('course')
            ->get();
    }
 
    $enlistedCourseIds = $currentEnrollments->pluck('course_id');
 
    $availableCourses = collect();
    if ($student) {
        $availableCourses = \App\Models\Course::where('is_active', true)
            ->where(function ($q) use ($student) {
                $q->where('program_id', $student->program)
                  ->orWhereNull('program_id');
            })
            ->whereNotIn('id', $enlistedCourseIds)
            ->with('program')
            ->get();
    }
 
    $canSelfEnroll = $student?->is_verified && $activeTerm?->is_enrollment_open;
 
    $message = match (true) {
        !$activeTerm                       => 'There is no active term right now. Enlistment is unavailable.',
        !$activeTerm->is_enrollment_open   => "Enrollment for {$activeTerm->label} is currently closed.",
        !$student?->is_verified            => 'Your account is not yet verified. Please wait for admin approval.',
        default                            => "Enrollment is open for {$activeTerm->label}. Enlist in the subjects below.",
    };
 
    $enrollments = $currentEnrollments;
    $payments = $student
    ? \App\Models\Payment::whereHas('studentEnrollment', function ($q) use ($student) {
        $q->where('student_id', $student->id);
    })->get()
    : collect();

$hasDownpayment = $payments
    ->where('payment_status', 'paid')
    ->isNotEmpty();

$payments = $student
    ? \App\Models\Payment::whereHas('studentEnrollment', function ($q) use ($student) {
        $q->where('student_id', $student->id);
    })->get()
    : collect();

return view('student.course', [
    'student'            => $student,
    'enrollments'        => $currentEnrollments,
    'currentEnrollments' => $currentEnrollments,
    'availableCourses'   => $availableCourses,
    'canSelfEnroll'      => $canSelfEnroll,
    'message'            => $message,
    'activeTerm'         => $activeTerm,
    'payments'           => $payments,
    'hasDownpayment'     => $hasDownpayment,
]);
}
 
  
public function myCourses()
{
    $user    = Auth::user()->load('profile');
    $student = $user->profile->student ?? null;
 
    if (!$student) {
        return view('student.my_courses', [
            'enrollmentsByTerm' => collect(),
            'totalCourses'      => 0,
            'totalUnits'        => 0,
            'termsCompleted'    => 0,
            'gwa'               => null,
        ]);
    }
 
    $enrolledCourses = \App\Models\EnrolledCourse::whereHas(
            'studentEnrollment',
            fn($q) => $q->where('student_id', $student->id)
        )
        ->with([
            'studentEnrollment.term',
            'course.program',
            'professor.profile',
            'room',
        ])
        ->get();
 
    // Group by term, newest first
    $enrollmentsByTerm = $enrolledCourses
        ->groupBy(fn($ec) => optional($ec->studentEnrollment->term)->id)
        ->map(function ($courses) {
            return [
                'term'    => optional($courses->first()->studentEnrollment)->term,
                'courses' => $courses,
            ];
        })
        ->sortByDesc(fn($group) => optional($group['term'])->start_date)
        ->values();
 
    $totalCourses   = $enrolledCourses->count();
    $totalUnits     = $enrolledCourses->sum(fn($ec) => optional($ec->course)->units ?? 0);
    $termsCompleted = $enrollmentsByTerm->filter(fn($g) => optional($g['term'])->status === 'ended')->count();
 
    $graded = $enrolledCourses->filter(fn($ec) => $ec->grade !== null);
    $gwa    = $graded->count() ? round($graded->avg('grade'), 2) : null;
 
    return view('student.my_courses', [
        'enrollmentsByTerm' => $enrollmentsByTerm,
        'totalCourses'      => $totalCourses,
        'totalUnits'        => $totalUnits,
        'termsCompleted'    => $termsCompleted,
        'gwa'               => $gwa,
    ]);
}

    public function enlistCourse(Request $request)
    {
        $currentTerm = Term::active()->first();

if (!$currentTerm) {
    return back()->with('error', 'No active term available right now.');
}

if (!$currentTerm->is_enrollment_open) {
    return back()->with('error', "Enrollment is currently closed for {$currentTerm->label}.");
}
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

        // Prevent enrollment if course is already full for the term
        $enrolledCount = \App\Models\StudentEnrollment::where('course_id', $course->id)
            ->where('term_id', $currentTerm->id)
            ->count();

        $slots = $course->slots ?? 30;
        if ($enrolledCount >= $slots) {
            return back()->with('error', 'Cannot enroll: course is full.');
        }

        $student->studentEnrollments()->create([
    'course_id'       => $course->id,
    'term_id'         => $currentTerm->id,
    'status'          => 'enrolled',   // ← was 'pending', change to valid value
    'enrollment_date' => now(),
    'units'           => $course->units,
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
    $user = auth()->user()->load([
        'profile.student.studentEnrollments.payments',
        'profile.student.studentEnrollments.course',
        'profile.student.studentEnrollments.term',
    ]);

    $student = $user->profile?->student;

    if (!$student) {
        return view('student.payments', [
            'payments' => collect(),
            'pendingPayments' => collect(),
            'paymentRequests' => collect(),
            'totalPaid' => 0,
            'totalPending' => 0,
            'overdue' => 0,
        ]);
    }

    // Student enrollments
    $enrollments = $student->studentEnrollments;

    // Latest active enrollment
    $enrollment = $enrollments
        ->whereIn('status', ['verified', 'enrolled'])
        ->sortByDesc('created_at')
        ->first();

    // IDs
    $enrollmentIds = $enrollments->pluck('id');

    // Payments
    $payments = \App\Models\Payment::with('studentEnrollment.course')
        ->whereIn('student_enrollment_id', $enrollmentIds)
        ->latest('payment_date')
        ->get();

    // Guard: no active enrollment — show empty state with zeroed totals
    if (!$enrollment) {
        return view('student.payments', [
            'payments'        => $payments,
            'pendingPayments' => collect(),
            'paymentRequests' => \App\Models\PaymentRequest::with('payment.studentEnrollment.course')
                                    ->where('student_id', $student->id)
                                    ->latest()
                                    ->get(),
            'totalPaid'       => $payments->whereNotIn('payment_status', ['cancelled', 'pending'])->sum('amount'),
            'totalPending'    => $payments->where('payment_status', 'pending')->sum('amount'),
            'totalTuition'    => 0,
            'balance'         => 0,
        ]);
    }

    // Pending
    $totalTuition = $enrollment->enrolledCourse()->sum('course_price');
    $downpayment  = $enrollment->payments()->where('payment_type', 'downpayment')->whereNotIn('payment_status', ['cancelled'])->sum('amount');
    $amountPaid   = $enrollment->payments
                        ->where('payment_type', 'tuition')
                        ->whereNotIn('payment_status', ['cancelled'])
                        ->sum('amount');
    $balance      = max(0, $totalTuition - ($amountPaid + $downpayment));

    $pendingPayments = $payments
        ->whereIn('payment_status', ['pending'])
        ->values();

    // Requests
    $paymentRequests = \App\Models\PaymentRequest::with('payment.studentEnrollment.course')
        ->where('student_id', $student->id)
        ->latest()
        ->get();

    // Totals
    $totalPaid = $payments
        ->whereNotIn('payment_status', ['cancelled', 'pending'])
        ->sum('amount');

    $totalPending = $payments
        ->where('payment_status', 'pending')
        ->sum('amount');

    return view('student.payments', compact(
        'payments',
        'pendingPayments',
        'paymentRequests',
        'totalPaid',
        'totalPending',
        'totalTuition',
        'balance',
    ));
}

    public function payPage()
{
    $user = auth()->user()->load([
        'profile.student.studentEnrollments.payments',
        'profile.student.studentEnrollments.enrolledCourse',
        'profile.student.studentEnrollments.course',
        'profile.student.studentEnrollments.term',
    ]);

    $student = $user->profile?->student;

    if (!$student) {
        return redirect()->route('student.payment')->with('error', 'Student record not found.');
    }

    // Pick the latest active enrollment (same logic used elsewhere)
    $enrollment = $student->studentEnrollments
        ->whereIn('status', ['verified', 'enrolled'])
        ->sortByDesc('created_at')
        ->first();

    if (!$enrollment) {
        return redirect()->route('student.payment')->with('error', 'No active enrollment found.');
    }

    // Balance calculation — identical to PaymentController::create()
    $totalTuition = $enrollment->enrolledCourse()->sum('course_price');

    $downpayment = $enrollment->payments()
        ->where('payment_type', 'downpayment')
        ->whereNotIn('payment_status', ['cancelled'])
        ->sum('amount');

    $amountPaid = $enrollment->payments
        ->where('payment_type', 'tuition')
        ->whereNotIn('payment_status', ['cancelled'])
        ->sum('amount');

    $balance = max(0, $totalTuition - ($amountPaid + $downpayment));

    // The payment record the student will submit a request against
    // (the single pending/partial tuition payment for this enrollment, if any)
    $pendingPayment = $enrollment->payments
        ->whereIn('payment_status', ['pending', 'partial', 'for_review'])
        ->where('payment_type', 'tuition')
        ->sortByDesc('created_at')
        ->first();

    return view('student.pay', compact(
        'enrollment',
        'totalTuition',
        'amountPaid',
        'downpayment',
        'balance',
        'pendingPayment',  // single record used as the payment_id in the form
    ));
}
}