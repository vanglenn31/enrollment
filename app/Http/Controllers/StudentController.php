<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Term;
use App\Models\StudentEnrollment;
use App\Models\EnrolledCourse;
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

    // Is enrollment finalized for this term?
    $isFinalized = $activeTerm
        ? $this->isEnrollmentFinalized($student, $activeTerm)
        : false;

    // ─────────────────────────────
    // ACTIVE TERM ENROLLMENTS
    // ─────────────────────────────
    $currentEnrollments = collect();

    if ($student && $activeTerm) {
        $currentEnrollments = $student->studentEnrollments()
            ->where('term_id', $activeTerm->id)
            ->whereNotNull('course_id')
            ->with('course')
            ->get();
    }

    $enlistedCourseIds = $currentEnrollments->pluck('course_id');

    // ─────────────────────────────
    // 🔥 FIX: COURSES ALREADY TAKEN (PERMANENT HISTORY)
    // ─────────────────────────────
    $takenCourseIds = \App\Models\EnrolledCourse::where('student_id', $student->id)
        ->pluck('course_id');

    // ─────────────────────────────
    // AVAILABLE COURSES
    // ─────────────────────────────
    $availableCourses = collect();

    if ($student && $activeTerm && !$isFinalized) {

        $generalProgram = \App\Models\Program::firstWhere('code', 'GEN')
            ?? \App\Models\Program::firstWhere('name', 'General Education');

        $allowedProgramIds = array_filter([
            $student->program,
            $generalProgram?->id
        ]);

        $availableCourses = \App\Models\Course::where('status', 'active')
            ->whereIn('program_id', $allowedProgramIds)

            // 🔥 CRITICAL FIX HERE
            ->whereNotIn('id', array_merge(
                $enlistedCourseIds->toArray(),
                $takenCourseIds->toArray()
            ))

            ->withCount('studentEnrollments')
            ->with('program')
            ->get();
    }

    // ─────────────────────────────
    // DOWNPAYMENT CHECK
    // ─────────────────────────────
    $hasDownpayment = false;

    if ($student) {
        $allEnrollmentIds = $student->studentEnrollments()->pluck('id');

        $hasDownpayment = \App\Models\Payment::whereIn('student_enrollment_id', $allEnrollmentIds)
            ->where('payment_type', 'downpayment')
            ->where('payment_status', 'paid')
            ->exists();
    }

    $payments = collect();

    // ─────────────────────────────
    // PERMISSION CHECK
    // ─────────────────────────────
    $canSelfEnroll = $student?->is_verified
        && $activeTerm?->is_enrollment_open
        && !$isFinalized;

    $message = match (true) {
        !$activeTerm => 'There is no active term right now. Enlistment is unavailable.',
        !$activeTerm->is_enrollment_open => "Enrollment for {$activeTerm->label} is currently closed.",
        !$student?->is_verified => 'Your account is not yet verified.',
        $isFinalized => 'Your enrollment is finalized.',
        default => "Enrollment is open for {$activeTerm->label}.",
    };

    return view('student.course', [
        'student'            => $student,
        'enrollments'        => $currentEnrollments,
        'currentEnrollments' => $currentEnrollments,
        'availableCourses'   => $availableCourses,
        'canSelfEnroll'      => $canSelfEnroll,
        'isFinalized'        => $isFinalized,
        'hasDownpayment'     => $hasDownpayment,
        'message'            => $message,
        'activeTerm'         => $activeTerm,
        'payments'           => $payments,
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

    if (!$student->is_verified) {
        return back()->with('error', 'Your account must be verified by admin before you can select courses.');
    }

    if ($this->isEnrollmentFinalized($student, $currentTerm)) {
        return back()->with('error', 'Your enrollment has been finalized.');
    }

    $validated = $request->validate([
        'course_id' => 'required|exists:courses,id',
    ]);

    $course = Course::findOrFail($validated['course_id']);

    $generalEducationProgram = Program::firstWhere('name', 'General Education');
    $allowedProgramIds = array_filter([
        $student->program,
        $generalEducationProgram?->id
    ]);

    if (!in_array($course->program_id, $allowedProgramIds, true)) {
        return back()->with('error', 'You may only enlist courses from your program or general education.');
    }

    // ─────────────────────────────────────────────
    // 🔥 NEW FIX: BLOCK IF ALREADY PASSED (>=75)
    // ─────────────────────────────────────────────
    $alreadyPassed = EnrolledCourse::where('student_id', $student->id)
        ->where('course_id', $course->id)
        ->where('grade', '>=', 75)
        ->exists();

    if ($alreadyPassed) {
        return back()->with('error', 'You already passed this course and cannot re-enroll it.');
    }

    // Duplicate check (current term only)
    $alreadyEnlisted = $student->studentEnrollments()
        ->where('course_id', $course->id)
        ->where('term_id', $currentTerm->id)
        ->exists();

    if ($alreadyEnlisted) {
        return back()->with('error', 'You have already enlisted this course for this term.');
    }

    // Slot check
    $enrolledCount = StudentEnrollment::where('course_id', $course->id)
        ->where('term_id', $currentTerm->id)
        ->count();

    $slots = $course->slots ?? 30;

    if ($enrolledCount >= $slots) {
        return back()->with('error', 'Cannot enlist: this course is full.');
    }

    // Create enrollment
    $studentEnrollment = $student->studentEnrollments()->create([
        'course_id'       => $course->id,
        'term_id'         => $currentTerm->id,
        'status'          => 'pending',
        'enrollment_date' => now(),
        'units'           => $course->units,
    ]);

    // History record
    EnrolledCourse::create([
        'student_id'   => $student->id,
        'course_id'    => $course->id,
        'professor_id' => $course->professor_id ?? null,
        'room_id'      => $course->room_id ?? null,
        'course_price' => $course->course_price ?? 0,
        'grade'        => null,
    ]);

    return back()->with('success', 'Course added to your enlistment successfully.');
}

    /**
     * Drop (remove) an enlisted course — only allowed before finalization.
     */
    public function dropCourse(StudentEnrollment $studentEnrollment)
    {
        $user    = Auth::user()->load('profile.student');
        $student = $user->profile?->student;

        // Ownership check
        if (!$student || $studentEnrollment->student_id !== $student->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        $currentTerm = Term::active()->first();

        if (!$currentTerm || !$currentTerm->is_enrollment_open) {
            return back()->with('error', 'Enrollment is not open. You cannot drop courses at this time.');
        }

        // Block if already finalized
        if ($this->isEnrollmentFinalized($student, $currentTerm)) {
            return back()->with('error', 'Your enrollment has been finalized. You can no longer remove courses.');
        }

        // Only allow dropping courses from the current term
        if ($studentEnrollment->term_id !== $currentTerm->id) {
            return back()->with('error', 'You can only drop courses from the current active term.');
        }

        // Delete the EnrolledCourse record by student + course (student_enrollment_id no longer exists)
        EnrolledCourse::where('student_id', $student->id)
            ->where('course_id', $studentEnrollment->course_id)
            ->delete();
        $studentEnrollment->delete();

        return back()->with('success', 'Course removed from your enlistment.');
    }

    /**
     * Finalize the student's enrollment for the current term.
     * Once finalized, no courses can be added or removed.
     */
    public function finalizeEnrollment(Request $request)
    {
        $user    = Auth::user()->load('profile.student');
        $student = $user->profile?->student;

        if (!$student) {
            return back()->with('error', 'Student record not found.');
        }

        $currentTerm = Term::active()->first();

        if (!$currentTerm) {
            return back()->with('error', 'No active term found.');
        }

        if (!$currentTerm->is_enrollment_open) {
            return back()->with('error', 'Enrollment is not currently open.');
        }

        // Check there is at least one course enlisted
        $enlistedCount = $student->studentEnrollments()
            ->where('term_id', $currentTerm->id)
            ->count();

        if ($enlistedCount === 0) {
            return back()->with('error', 'You must enlist at least one course before finalizing.');
        }

        // Already finalized?
        if ($this->isEnrollmentFinalized($student, $currentTerm)) {
            return back()->with('error', 'Your enrollment is already finalized.');
        }

        // Mark all current-term enrollments as finalized
        $student->studentEnrollments()
            ->where('term_id', $currentTerm->id)
            ->update(['status' => 'enrolled']);

        return back()->with('success', 'Your enrollment has been finalized successfully! No further changes are allowed for this term.');
    }

    public function enrollment()
{
    $user    = Auth::user()->load('profile.student.programRelation');
    $student = $user->profile?->student;

    $activeTerm = Term::active()->first();

    // ─────────────────────────────────────────────
    // 1. CURRENT TERM ENROLLMENTS
    // ─────────────────────────────────────────────
    $enrollments = collect();

    if ($student && $activeTerm) {
        $enrollments = $student->studentEnrollments()
            ->where('term_id', $activeTerm->id)
            ->whereNotNull('course_id')
            ->with('course')
            ->get();
    }

    // ─────────────────────────────────────────────
    // 2. ALL ENROLLMENTS (FOR PAYMENTS)
    // ─────────────────────────────────────────────
    $allEnrollments = $student
        ? $student->studentEnrollments()->get()
        : collect();

    // ─────────────────────────────────────────────
    // 3. PAYMENTS
    // ─────────────────────────────────────────────
    $payments = $student
        ? Payment::whereIn('student_enrollment_id', $allEnrollments->pluck('id'))
            ->orderByDesc('payment_date')
            ->get()
        : collect();

    // ─────────────────────────────────────────────
    // 4. DOWNPAYMENT CHECK (FIXED)
    // ─────────────────────────────────────────────
    $hasDownpayment = $payments
        ->where('payment_type', 'downpayment')
        ->where('payment_status', 'paid')
        ->isNotEmpty();

    // ─────────────────────────────────────────────
    // 5. FINALIZED CHECK
    // ─────────────────────────────────────────────
    $isFinalized = $activeTerm
        ? $this->isEnrollmentFinalized($student, $activeTerm)
        : false;

    // ─────────────────────────────────────────────
    // 6. 🔥 MISSING FILTER ADDED HERE (PASSED COURSES)
    // ─────────────────────────────────────────────
    $passedCourseIds = \App\Models\EnrolledCourse::where('student_id', $student->id)
        ->where('grade', '>=', 75)
        ->pluck('course_id')
        ->toArray();

    // ─────────────────────────────────────────────
    // 7. CURRENT TERM COURSE IDS
    // ─────────────────────────────────────────────
    $currentCourseIds = $enrollments->pluck('course_id')->toArray();

    // ─────────────────────────────────────────────
    // 8. AVAILABLE COURSES
    // ─────────────────────────────────────────────
    $availableCourses = collect();

    if ($student && $activeTerm && !$isFinalized) {

        $generalProgram = Program::firstWhere('code', 'GEN')
            ?? Program::firstWhere('name', 'General Education');

        $allowedProgramIds = array_filter([
            $student->program,
            $generalProgram?->id
        ]);

        $blockedCourseIds = array_merge($currentCourseIds, $passedCourseIds);

        $availableCourses = Course::where('status', 'active')
            ->whereIn('program_id', $allowedProgramIds)
            ->whereNotIn('id', $blockedCourseIds)
            ->with('program')
            ->withCount('studentEnrollments')
            ->get();
    }

    // ─────────────────────────────────────────────
    // 9. CAN SELF ENROLL
    // ─────────────────────────────────────────────
    $canSelfEnroll = $student?->is_verified
        && $activeTerm?->is_enrollment_open
        && !$isFinalized
        && $hasDownpayment;

    // ─────────────────────────────────────────────
    // 10. MESSAGE
    // ─────────────────────────────────────────────
    $message = match (true) {
        !$activeTerm                     => 'No active term.',
        !$activeTerm->is_enrollment_open => "Enrollment closed for {$activeTerm->label}.",
        !$student?->is_verified          => 'Not verified.',
        !$hasDownpayment                 => 'Downpayment required first.',
        $isFinalized                     => 'Enrollment finalized.',
        default                          => "Enrollment open for {$activeTerm->label}.",
    };

    return view('student.enrollment', compact(
        'student',
        'enrollments',
        'payments',
        'hasDownpayment',
        'activeTerm',
        'availableCourses',
        'canSelfEnroll',
        'isFinalized',
        'message'
    ));
}

    // ── Helper ──────────────────────────────────────────────────────────────

    /**
     * Returns true if the student has at least one enrollment for the given term
     * with status 'finalized'. If $student is null, returns false.
     */
    private function isEnrollmentFinalized($student, $term): bool
    {
        if (!$student || !$term) {
            return false;
        }

        return $student->studentEnrollments()
            ->where('term_id', $term->id)
            ->where('status', 'enrolled')
            ->exists();
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
                'payments'        => collect(),
                'pendingPayments' => collect(),
                'paymentRequests' => collect(),
                'totalPaid'       => 0,
                'totalPending'    => 0,
                'overdue'         => 0,
            ]);
        }

        $enrollments   = $student->studentEnrollments;
        $enrollment    = $enrollments
            ->whereIn('status', ['verified', 'enrolled', 'finalized'])
            ->sortByDesc('created_at')
            ->first();

        $enrollmentIds = $enrollments->pluck('id');

        $payments = \App\Models\Payment::with('studentEnrollment.course')
            ->whereIn('student_enrollment_id', $enrollmentIds)
            ->latest('payment_date')
            ->get();

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

         $totalTuition = $enrollment->course?->course_price ?? 0;
        $downpayment = \App\Models\Payment::whereHas('studentEnrollment', function ($q) use ($enrollment) {
            $q->where('student_id', $enrollment->student_id);
            })
            ->where('payment_type', 'downpayment')
            ->where('payment_status', 'paid')
            ->value('amount') ?? 0;
        $amountPaid   = $enrollment->payments
                            ->where('payment_type', 'tuition')
                            ->whereNotIn('payment_status', ['cancelled'])
                            ->sum('amount');
        $balance = max(0, $totalTuition - ($amountPaid + $downpayment));

        $pendingPayments = $payments->whereIn('payment_status', ['pending'])->values();

        $paymentRequests = \App\Models\PaymentRequest::with('payment.studentEnrollment.course')
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        $totalPaid    = $payments->whereNotIn('payment_status', ['cancelled', 'pending'])->sum('amount');
        $totalPending = $payments->where('payment_status', 'pending')->sum('amount');

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

        $enrollment = $student->studentEnrollments
            ->whereIn('status', ['verified', 'enrolled', 'finalized'])
            ->sortByDesc('created_at')
            ->first();

        if (!$enrollment) {
            return redirect()->route('student.payment')->with('error', 'No active enrollment found.');
        }

         $totalTuition = $enrollment->course?->course_price ?? 0;
        $downpayment = \App\Models\Payment::whereHas('studentEnrollment', function ($q) use ($enrollment) {
            $q->where('student_id', $enrollment->student_id);
            })
            ->where('payment_type', 'downpayment')
            ->where('payment_status', 'paid')
            ->value('amount') ?? 0;

        $amountPaid = $enrollment->payments
            ->where('payment_type', 'tuition')
            ->whereNotIn('payment_status', ['cancelled'])
            ->sum('amount');

        $balance = max(0, $totalTuition - ($amountPaid + $downpayment));

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
            'pendingPayment',
        ));
    }
}