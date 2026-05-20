<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Payment;
use App\Models\PaymentRequest;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentRequestController extends Controller
{
    /* ================================================================== */
    /*  STUDENT: Submit a payment request                                  */
    /* ================================================================== */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'enrollment_id'    => ['required', 'exists:student_enrollments,id'],
            'amount_paid'      => ['required', 'numeric', 'min:0.01'],
            'payment_method'   => ['required', 'in:gcash,bank_transfer,cash,maya,other'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'proof_of_payment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'note'             => ['nullable', 'string', 'max:500'],
        ]);

        // ── 1. Load enrollment and verify ownership ───────────────────────────
        $enrollment = StudentEnrollment::with(['payments', 'enrolledCourse', 'student.profile'])
            ->findOrFail($validated['enrollment_id']);

        $user = Auth::user();

        if (
            ! $enrollment->student ||
            ! $enrollment->student->profile ||
            $enrollment->student->profile->user_id !== $user->id
        ) {
            abort(403, 'Unauthorized: this enrollment does not belong to you.');
        }

        // ── 2. Compute balance ────────────────────────────────────────────────
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

        if ($balance <= 0) {
            return back()->with('error', 'Your tuition is already fully paid.');
        }

        if ($validated['amount_paid'] > $balance) {
            return back()->with('error',
                'The amount ₱' . number_format($validated['amount_paid'], 2) .
                ' exceeds your remaining balance of ₱' . number_format($balance, 2) . '.'
            );
        }

        // ── 3. Guard: no duplicate pending ────────────────────────────────────
        $alreadyPending = PaymentRequest::whereHas('payment', function ($q) use ($enrollment) {
                $q->where('student_enrollment_id', $enrollment->id);
            })
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return back()->with('error', 'You already have a pending request under review. Please wait for admin to resolve it before submitting another.');
        }

        // ── 4. Store proof file ───────────────────────────────────────────────
        $path = null;
        if ($request->hasFile('proof_of_payment')) {
            $path = $request->file('proof_of_payment')
                            ->store('payment_proofs', 'public');
        }

        // ── 5. Create Payment row ─────────────────────────────────────────────
        $payment = Payment::create([
            'student_enrollment_id' => $enrollment->id,
            'amount'                => $validated['amount_paid'],
            'payment_type'          => 'tuition',
            'payment_method'        => $validated['payment_method'],
            'reference_number'      => $validated['reference_number'] ?? null,
            'payment_status'        => 'pending',
            'payment_date'          => now(),
        ]);

        // ── 6. Create PaymentRequest ──────────────────────────────────────────
        PaymentRequest::create([
            'payment_id'       => $payment->id,
            'student_id'       => Auth::id(),
            'amount_paid'      => $validated['amount_paid'],
            'payment_method'   => $validated['payment_method'],
            'reference_number' => $validated['reference_number'] ?? null,
            'proof_of_payment' => $path ?? null,
            'note'             => $validated['note'] ?? null,
            'status'           => 'pending',
        ]);

        // ── 7. Notify student: request received ───────────────────────────────
        

        return back()->with('success', 'Payment request submitted. The admin will review it shortly.');
    }

    /* ================================================================== */
    /*  ADMIN: List all payment requests                                   */
    /* ================================================================== */

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'pending');

        $requests = PaymentRequest::with([
                'payment.studentEnrollment.student.profile',
                'payment.studentEnrollment.course',
                'payment.studentEnrollment.term',
                'student.profile',
                'reviewer',
            ])
            ->when($filter !== 'all', fn ($q) => $q->where('status', $filter))
            ->latest()
            ->paginate(2)
            ->withQueryString();

        $pendingCount = PaymentRequest::where('status', 'pending')->count();

        return view('admin.payments.payment-requests', compact('requests', 'filter', 'pendingCount'));
    }

    public function show(PaymentRequest $paymentRequest)
    {
        $paymentRequest->load([
            'payment.studentEnrollment.student.profile',
            'payment.studentEnrollment.payments',
            'payment.studentEnrollment.course',
            'payment.studentEnrollment.term',
            'student.profile',
            'reviewer',
        ]);

        $enrollment   = $paymentRequest->payment->studentEnrollment;
        $totalTuition = $enrollment->enrolledCourse()->sum('course_price');

        $downpayment = $enrollment->payments()
            ->where('payment_type', 'downpayment')
            ->whereNotIn('payment_status', ['cancelled'])
            ->sum('amount');

        $amountPaid = $enrollment->payments
            ->where('payment_type', 'tuition')
            ->whereNotIn('payment_status', ['cancelled', 'for_review'])
            ->sum('amount');

        $balance = max(0, $totalTuition - ($amountPaid + $downpayment));

        return view('admin.payments.review-request', compact(
            'paymentRequest',
            'enrollment',
            'totalTuition',
            'amountPaid',
            'balance',
        ));
    }

    /* ================================================================== */
    /*  ADMIN: Approve a request                                           */
    /* ================================================================== */

    public function approve(Request $request, PaymentRequest $paymentRequest)
    {
        $request->validate([
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $paymentRequest->isPending()) {
            return back()->with('error', 'This request has already been reviewed.');
        }

        $paymentRequest->update([
            'status'      => 'approved',
            'reviewed_by' => Auth::id(),
            'admin_note'  => $request->admin_note,
            'reviewed_at' => now(),
        ]);

        $payment    = $paymentRequest->payment->load('studentEnrollment.payments');
        $enrollment = $payment->studentEnrollment;

        $totalTuition = $enrollment->enrolledCourse()->sum('course_price');

        $downpayment = $enrollment->payments()
            ->where('payment_type', 'downpayment')
            ->whereNotIn('payment_status', ['cancelled'])
            ->sum('amount');

        $alreadyPaid = $enrollment->payments
            ->where('payment_type', 'tuition')
            ->whereIn('payment_status', ['paid', 'partial'])
            ->sum('amount');

        $newTotal   = $alreadyPaid + $downpayment + $paymentRequest->amount_paid;
        $newBalance = max(0, $totalTuition - $newTotal);

        $newStatus = $newBalance <= 0 ? 'paid' : 'partial';

        $payment->update([
            'amount'           => $paymentRequest->amount_paid,
            'payment_method'   => $paymentRequest->payment_method,
            'reference_number' => $paymentRequest->reference_number,
            'payment_date'     => now(),
            'payment_status'   => $newStatus,
        ]);

        // ── Notify student: payment approved ─────────────────────────────────
        // Resolve the student's user_id through the ownership chain
        $studentUserId = $enrollment->student?->profile?->user_id;

        if ($studentUserId) {
            $balanceMsg = $newBalance > 0
                ? ' Your remaining balance is ₱' . number_format($newBalance, 2) . '.'
                : ' Your tuition is now fully paid. 🎉';

            
        }

        return redirect()
            ->route('admin.payment-requests.index')
            ->with('success', 'Payment request approved and payment record confirmed.');
    }

    /* ================================================================== */
    /*  ADMIN: Reject a request                                            */
    /* ================================================================== */

    public function reject(Request $request, PaymentRequest $paymentRequest)
    {
        $request->validate([
            'admin_note' => ['required', 'string', 'max:500'],
        ]);

        if (! $paymentRequest->isPending()) {
            return back()->with('error', 'This request has already been reviewed.');
        }

        $paymentRequest->update([
            'status'      => 'rejected',
            'reviewed_by' => Auth::id(),
            'admin_note'  => $request->admin_note,
            'reviewed_at' => now(),
        ]);

        $paymentRequest->payment->update(['payment_status' => 'cancelled']);

        // ── Notify student: payment rejected ─────────────────────────────────
        $enrollment    = $paymentRequest->payment->studentEnrollment;
        $studentUserId = $enrollment->student?->profile?->user_id;

        if ($studentUserId) {
            $reason = $request->admin_note
                ? ' Reason: ' . $request->admin_note
                : '';

           
        }

        return redirect()
            ->route('admin.payment-requests.index')
            ->with('success', 'Payment request rejected. The student can submit a new request.');
    }
}