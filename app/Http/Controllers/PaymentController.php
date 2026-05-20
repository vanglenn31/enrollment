<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Term;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->get('search');

        $payments = Payment::with([
                'studentEnrollment.student.profile',
                'studentEnrollment.term',
                'paymentRequests',
            ])
            ->when($search, function ($query, $search) {
                $query->whereHas('studentEnrollment.student.profile', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name',  'like', "%{$search}%");
                })->orWhereHas('studentEnrollment.student', function ($q) use ($search) {
                    $q->where('student_number', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('admin.payments.index', compact('payments', 'search'));
    }

    // ═══════════════════════════════════════════════════════════════
    //  DOWNPAYMENT  — admin records a downpayment for a student
    //  GET  /admin/payments/downpayment
    //  POST /admin/payments/downpayment
    // ═══════════════════════════════════════════════════════════════

    /**
     * Show the downpayment form.
     * Look up a student by student_number and show their downpayment status.
     */
    public function createDownpayment(Request $request)
    {
        $studentNumber       = $request->get('student_number');
        $student             = null;
        $enrollment          = null;
        $existingDownpayment = null;

        if ($studentNumber) {
            $student = Student::with('profile')
                ->where('student_number', $studentNumber)
                ->first();

            if ($student) {
                // Latest pending/verified enrollment
                $enrollment = StudentEnrollment::with(['payments', 'term'])
                    ->where('student_id', $student->id)
                    ->latest()
                    ->first();

                // Already has a confirmed downpayment?
                if ($enrollment) {
                    $existingDownpayment = $enrollment->payments()
                        ->where('payment_type', 'downpayment')
                        ->whereNotIn('payment_status', ['cancelled'])
                        ->first();
                }
            }
        }

        return view('admin.payments.create-downpayment', compact(
            'studentNumber',
            'student',
            'enrollment',
            'existingDownpayment',
        ));
    }

    /**
     * Store a downpayment record and — when status is 'paid' — mark the
     * student's downpayment_paid flag so they can be enrolled in courses.
     */
    public function storeDownpayment(Request $request)
    {
        $validated = $request->validate([
            'student_number'  => ['required', 'string'],
            'amount'          => ['required', 'numeric', 'min:0.01'],
            'payment_date'    => ['nullable', 'date'],
            'payment_method'  => ['nullable', 'in:cash,gcash,bank_transfer'],
            'payment_status'  => ['required', 'in:pending,paid,cancelled'],
            'reference_number' => ['nullable', 'string', 'max:100'],
        ]);

        // ─────────────────────────────────────────────
        // 1. Find student
        // ─────────────────────────────────────────────
        $student = Student::where('student_number', $validated['student_number'])->first();

        if (! $student) {
            return back()->withInput()
                ->withErrors(['student_number' => 'Student not found.']);
        }

        // ─────────────────────────────────────────────
        // 2. Require an active term
        // ─────────────────────────────────────────────
        $currentTerm = Term::where('status', 'active')->first();

        if (! $currentTerm) {
            return back()->withErrors([
                'student_number' => 'No active term found. Please set an active term first.',
            ]);
        }

        // NOTE: intentionally NOT blocking on is_enrollment_open here —
        // a downpayment is a prerequisite FOR enrollment, so it should be
        // recordable even while the enrollment window is closed.

        // ─────────────────────────────────────────────
        // 3. FIX: Only create the StudentEnrollment anchor row
        //    when the downpayment is actually being marked as PAID.
        //    For pending/cancelled, look up an existing row only.
        // ─────────────────────────────────────────────
        if ($validated['payment_status'] === 'paid') {
            $enrollment = StudentEnrollment::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'term_id'    => $currentTerm->id,
                ],
                [
                    'status'          => 'pending',
                    'enrollment_date' => now(),
                ]
            );
        } else {
            // pending or cancelled — only use an existing row, never create one
            $enrollment = StudentEnrollment::where('student_id', $student->id)
                ->where('term_id', $currentTerm->id)
                ->first();

            if (! $enrollment) {
                return back()->withInput()->withErrors([
                    'student_number' => 'No enrollment record found for this student in the active term. Record a paid downpayment first.',
                ]);
            }
        }

        // ─────────────────────────────────────────────
        // 4. Prevent duplicate confirmed downpayment
        // ─────────────────────────────────────────────
        $alreadyPaid = $enrollment->payments()
            ->where('payment_type', 'downpayment')
            ->where('payment_status', 'paid')
            ->exists();

        if ($alreadyPaid) {
            return back()->withInput()
                ->withErrors(['student_number' => 'This student already has a confirmed downpayment.']);
        }

        // ─────────────────────────────────────────────
        // 5. Create payment record
        // ─────────────────────────────────────────────
        Payment::create([
            'student_enrollment_id' => $enrollment->id,
            'amount'                => $validated['amount'],
            'payment_date'          => $validated['payment_date'] ?? now(),
            'payment_method'        => $validated['payment_method'],
            'payment_status'        => $validated['payment_status'],
            'payment_type'          => 'downpayment',
            'reference_number'      => $validated['reference_number'] ?? null,
        ]);

        // ─────────────────────────────────────────────
        // 6. Unlock student if paid
        // ─────────────────────────────────────────────
        if ($validated['payment_status'] === 'paid') {
            $student->update(['downpayment_paid' => true]);
        }

        return redirect()
            ->route('admin.payments')
            ->with('success', 'Downpayment recorded successfully.');
    }

    /**
     * Admin confirms a pending downpayment → marks student as cleared.
     * POST /admin/payments/{payment}/confirm-downpayment
     */
    public function confirmDownpayment(Payment $payment)
    {
        if ($payment->payment_type !== 'downpayment') {
            return back()->with('error', 'This is not a downpayment record.');
        }

        $payment->update(['payment_status' => 'paid']);

        // Unlock enrollment
        $student = $payment->studentEnrollment?->student;
        if ($student) {
            $student->update(['downpayment_paid' => true]);
        }

        return back()->with('success', 'Downpayment confirmed. Student can now be assigned courses.');
    }

    // ═══════════════════════════════════════════════════════════════
    //  CREATE / STORE  — regular tuition payment
    // ═══════════════════════════════════════════════════════════════

    public function create(Request $request)
    {
        $enrollment   = null;
        $totalTuition = 0;
        $downpayment  = 0;
        $amountPaid   = 0;
        $balance      = 0;
        $alreadyPaid  = false;

        $studentNumber = $request->get('student_number');

        if ($studentNumber) {

    $enrollment = StudentEnrollment::with([
            'student.profile',
            'payments',
            'term',
        ])
        ->whereHas('student', function ($q) use ($studentNumber) {
            $q->where('student_number', $studentNumber);
        })
        ->whereIn('status', ['verified', 'enrolled'])
        ->latest()
        ->first();

    if ($enrollment) {

        // ✅ TOTAL TUITION (based on enrolled courses relation)
        $totalTuition = $enrollment->course?->course_price ?? 0;

        // If student can have multiple courses per enrollment:
        // $totalTuition = $enrollment->courses->sum('course_price');

        $downpayment = \App\Models\Payment::whereHas('studentEnrollment', function ($q) use ($enrollment) {
            $q->where('student_id', $enrollment->student_id);
            })
            ->where('payment_type', 'downpayment')
            ->where('payment_status', 'paid')
            ->value('amount') ?? 0;

        // ✅ TUITION PAYMENTS
        $amountPaid = $enrollment->payments()
            ->where('payment_type', 'tuition')
            ->whereNotIn('payment_status', ['cancelled', 'for_review'])
            ->sum('amount');

        // ✅ BALANCE
        $balance = max(0, $totalTuition - $downpayment - $amountPaid);

        $alreadyPaid = $balance <= 0 && $totalTuition > 0;
    }
}

        return view('admin.payments.create-payment', compact(
            'enrollment',
            'totalTuition',
            'downpayment',
            'amountPaid',
            'balance',
            'alreadyPaid',
            'studentNumber',
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_number' => ['required', 'string'],
            'amount'         => ['required', 'numeric', 'min:0.01'],
            'payment_date'   => ['nullable', 'date'],
            'payment_method' => ['nullable', 'in:cash,gcash,bank_transfer'],
            'payment_status' => ['required', 'in:pending,partial,paid,cancelled'],
        ]);

        $enrollment = StudentEnrollment::with('payments', 'student')
            ->whereHas('student', fn ($q) => $q->where('student_number', $validated['student_number']))
            ->whereIn('status', ['verified', 'enrolled'])
            ->latest()
            ->first();

        if (! $enrollment) {
            return back()->withInput()
                ->withErrors(['student_number' => 'No active enrollment found for this student number.']);
        }

        $totalTuition = $enrollment->enrolledCourse()->sum('course_price');
        $downpayment  = $enrollment->payments()->where('payment_type', 'downpayment')->whereNotIn('payment_status', ['cancelled'])->sum('amount');
        $amountPaid   = $enrollment->payments
                            ->where('payment_type', 'tuition')
                            ->whereNotIn('payment_status', ['cancelled'])
                            ->sum('amount');
        $balance      = max(0, $totalTuition - ($amountPaid + $downpayment));

        if ($totalTuition > 0 && $balance <= 0) {
            return back()->withInput()
                ->withErrors(['student_number' => 'This student has already fully paid their tuition.']);
        }

        if ($validated['amount'] > $balance && $validated['payment_status'] !== 'cancelled') {
            return back()->withInput()
                ->withErrors(['amount' => 'The amount exceeds the remaining balance of ₱' . number_format($balance, 2) . '.']);
        }

        Payment::create([
            'student_enrollment_id' => $enrollment->id,
            'amount'                => $validated['amount'],
            'payment_date'          => $validated['payment_date'] ?? now(),
            'payment_method'        => $validated['payment_method'],
            'payment_status'        => $validated['payment_status'],
            'payment_type'          => 'tuition',
        ]);

        return redirect()
            ->route('admin.payments')
            ->with('success', 'Payment recorded successfully.');
    }

    // ═══════════════════════════════════════════════════════════════
    //  EDIT / UPDATE
    // ═══════════════════════════════════════════════════════════════

    public function edit(Payment $payment)
    {
        $payment->load('studentEnrollment.student.profile', 'studentEnrollment.payments');

        $enrollment   = $payment->studentEnrollment;
        $totalTuition = $enrollment->total_tuition ?? 0;
        $downpayment  = $enrollment->payments()->where('payment_type', 'downpayment')->whereNotIn('payment_status', ['cancelled'])->sum('amount');
        $amountPaid   = $enrollment->payments
                            ->where('payment_type', 'tuition')
                            ->whereNotIn('payment_status', ['cancelled'])
                            ->sum('amount');
        $balance      = max(0, $totalTuition - ($amountPaid + $downpayment));

        return view('admin.payments.edit-payment', compact('payment', 'enrollment', 'totalTuition', 'amountPaid', 'balance'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:pending,partial,paid,cancelled,for_review'],
            'payment_method' => ['nullable', 'in:cash,gcash,bank_transfer'],
            'amount'         => ['required', 'numeric', 'min:0.01'],
            'payment_date'   => ['nullable', 'date'],
        ]);

        $payment->update($validated);

        // If a downpayment is being updated to paid, unlock the student
        if ($payment->payment_type === 'downpayment' && $validated['payment_status'] === 'paid') {
            $payment->studentEnrollment?->student?->update(['downpayment_paid' => true]);
        }

        return redirect()
            ->route('admin.payments')
            ->with('success', 'Payment updated successfully.');
    }
}