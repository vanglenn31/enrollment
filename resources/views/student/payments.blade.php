<x-app-layout>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    .page-wrap * {
        font-family: 'DM Sans', sans-serif;
    }

    .mono {
        font-family: 'DM Mono', monospace;
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, #4f46e5 0%, #818cf8 60%, transparent 100%);
        border-radius: 2px;
    }

    .card-hover {
        transition: all 0.2s ease;
    }

    .card-hover:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 9999px;
        background: #22c55e;
        display: inline-block;
        box-shadow: 0 0 0 3px rgba(34,197,94,0.15);
    }

    .empty-state {
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 6px,
            rgba(0,0,0,0.015) 6px,
            rgba(0,0,0,0.015) 12px
        );
    }

    /* Modal */
    #payment-modal {
        display: none;
    }
    #payment-modal.is-open {
        display: flex;
    }

    /* Tabs */
    .tab-panel {
        display: none;
    }
    .tab-panel.is-active {
        display: block;
    }

    .tab-btn.is-active {
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        color: #111827;
        font-weight: 600;
    }
</style>

<div class="page-wrap flex min-h-screen bg-gray-50">

    <!-- SIDEBAR -->
    <aside class="hidden lg:block fixed inset-y-0 left-0 w-fit">
        @include('layouts.student_side_bar')
    </aside>

    <!-- MAIN -->
    <div class="flex-1 w-full lg:ml-64 flex flex-col">

        <!-- NAVIGATION -->
        <header class="sticky top-0 z-50">
            @include('layouts.navigation')
        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-6xl mx-auto space-y-6">

                <div class="col-span-4 col-start-2 p-6 z-30 w-full">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
                        <p class="text-sm text-gray-500 mt-1">View your payment history and submit payment requests for admin approval.</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center rounded-full bg-orange-50 px-3 py-1.5 text-sm text-orange-700 font-medium">
                            Pending: ₱{{ number_format($totalPending, 2) }}
                        </span>
                        <a href="{{ route('student.payment.pay') }}"
                           class="relative inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                            </svg>
                            Make a Payment
                        </a>
                    </div>
                </div>

                {{-- Alerts --}}
                @if(session('success'))
                    <div class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700 text-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700 text-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.07 16.5C2.3 17.333 3.262 19 4.8 19z"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Summary Cards --}}
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Paid</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 text-end">₱{{ number_format($totalPaid, 2) }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Balance</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 text-end">₱{{ number_format($balance, 2) }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Tuition</p>
                        <p class="mt-2 text-2xl font-bold text-red-600 text-end">₱{{ number_format($totalTuition, 2) }}</p>
                    </div>
                </div>

                {{-- Outstanding Payments --}}
                @if($pendingPayments->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div>
                            <h2 class="font-bold text-gray-900">Outstanding balance</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Select a payment record to submit a payment request.</p>
                        </div>
                        <span class="inline-flex items-center justify-center rounded-full bg-orange-100 text-orange-700 text-xs font-bold px-2.5 py-1">
                            {{ $pendingPayments->count() }} pending
                        </span>
                    </div>

                    <div class="p-6 space-y-3">
                        @foreach($pendingPayments as $payment)
                            <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-100 bg-slate-50 px-5 py-4 hover:border-orange-200 transition-colors">
                                <div class="min-w-0">
                                    <p class="font-semibold text-sm text-gray-900">
                                        {{ optional(optional($payment->studentEnrollment)->course)->course_name ?? 'Tuition / Downpayment' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Due: {{ optional($payment->due_date)->format('M d, Y') ?? 'No due date' }}
                                        @if($payment->payment_status === 'overdue')
                                            <span class="ml-2 text-red-500 font-medium">· Overdue</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="font-bold text-gray-900 mr-1">₱{{ number_format($payment->amount, 2) }}</span>

                                    {{-- Pay Online --}}
                                    <button
                                        type="button"
                                        onclick="openPaymentModal({{ $payment->id }}, '{{ number_format($payment->amount, 2) }}', {{ $payment->amount }}, '{{ addslashes(optional(optional($payment->studentEnrollment)->course)->course_name ?? 'Tuition / Downpayment') }}', 'online')"
                                        class="inline-flex items-center gap-1.5 rounded-xl bg-gray-900 px-3 py-2 text-xs font-semibold text-white hover:bg-gray-700 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Pay Online
                                    </button>

                                    {{-- Upload Receipt --}}
                                    <button
                                        type="button"
                                        onclick="openPaymentModal({{ $payment->id }}, '{{ number_format($payment->amount, 2) }}', {{ $payment->amount }}, '{{ addslashes(optional(optional($payment->studentEnrollment)->course)->course_name ?? 'Tuition / Downpayment') }}', 'manual')"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        Upload Receipt
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Payment Modal --}}
                    <div id="payment-modal"
                         class="fixed inset-0 z-50 items-center justify-center p-4 bg-black/40">

                        <div id="payment-modal-box"
                             class="w-full max-w-md bg-white rounded-2xl shadow-xl flex flex-col max-h-[90vh]">

                            {{-- Header --}}
                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                                <div>
                                    <h3 class="font-bold text-gray-900">Submit Payment Request</h3>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        For: <span id="modal-label" class="font-medium text-gray-600"></span>
                                        &nbsp;·&nbsp; Due: <span class="font-medium text-gray-600">₱<span id="modal-amount"></span></span>
                                    </p>
                                </div>
                                <button type="button" onclick="closePaymentModal()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            {{-- Tabs --}}
                            <div class="px-6 pt-4 shrink-0">
                                <div class="flex rounded-xl border border-gray-200 p-1 bg-gray-50 gap-1">
                                    <button type="button" id="tab-btn-online"
                                            onclick="switchTab('online')"
                                            class="tab-btn flex-1 flex items-center justify-center gap-1.5 py-2 rounded-lg text-sm transition-all text-gray-500 hover:text-gray-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Pay Online
                                    </button>
                                    <button type="button" id="tab-btn-manual"
                                            onclick="switchTab('manual')"
                                            class="tab-btn flex-1 flex items-center justify-center gap-1.5 py-2 rounded-lg text-sm transition-all text-gray-500 hover:text-gray-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        Upload Receipt
                                    </button>
                                </div>
                            </div>

                            {{-- Scrollable body --}}
                            <div class="overflow-y-auto flex-1 px-6 pb-6 pt-4">

                                {{-- ── ONLINE TAB ──────────────────────────────── --}}
                                <div id="tab-panel-online" class="tab-panel space-y-4">
                                    <form action="{{ route('student.payment.request') }}" method="POST" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="payment_id"   id="online-payment-id">
                                        <input type="hidden" name="request_type" value="online">

                                        <div class="rounded-xl bg-blue-50 border border-blue-100 px-4 py-3 flex items-start gap-3">
                                            <svg class="w-4 h-4 text-blue-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <p class="text-xs text-blue-700 leading-relaxed">
                                                Fill in your payment details and submit. The admin will review and confirm your payment within 1–2 business days.
                                            </p>
                                        </div>

                                        {{-- Amount --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                Amount paid <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 font-medium text-sm">₱</span>
                                                <input type="number" name="amount_paid" id="online-amount-input" min="1" step="0.01" required
                                                       class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                            </div>
                                            <p class="mt-1 text-xs text-gray-400">Partial payments are accepted.</p>
                                        </div>

                                        {{-- Method --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                Payment method <span class="text-red-500">*</span>
                                            </label>
                                            <select name="payment_method" required
                                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent bg-white">
                                                <option value="" disabled selected>Select a method</option>
                                                <option value="gcash">GCash</option>
                                                <option value="maya">Maya</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="cash">Cash (over the counter)</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>

                                        {{-- Reference --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                Reference / transaction number
                                            </label>
                                            <input type="text" name="reference_number"
                                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                                                   placeholder="e.g. GCash ref # or bank ref #">
                                        </div>

                                        {{-- Note --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                Note <span class="text-gray-400 font-normal">(optional)</span>
                                            </label>
                                            <textarea name="note" rows="2"
                                                      class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent resize-none"
                                                      placeholder="Any additional details for the admin…"></textarea>
                                        </div>

                                        <div class="flex items-center gap-3 pt-1">
                                            <button type="submit"
                                                    class="flex-1 rounded-xl bg-gray-900 py-2.5 text-sm font-semibold text-white hover:bg-gray-700 transition-colors">
                                                Submit Request
                                            </button>
                                            <button type="button" onclick="closePaymentModal()"
                                                    class="flex-1 rounded-xl border border-gray-200 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                {{-- ── MANUAL TAB — upload proof ──────────────────────────────── --}}
                                <div id="tab-panel-manual" class="tab-panel space-y-4">
                                    <form action="{{ route('student.payment.request') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="payment_id"   id="manual-payment-id">
                                        <input type="hidden" name="request_type" value="manual">

                                        <div class="rounded-xl bg-amber-50 border border-amber-100 px-4 py-3 flex items-start gap-3">
                                            <svg class="w-4 h-4 text-amber-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <p class="text-xs text-amber-700 leading-relaxed">
                                                Already paid via GCash, bank, or cash? Upload your screenshot or receipt and the admin will verify it.
                                            </p>
                                        </div>

                                        {{-- Amount --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                Amount paid <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 font-medium text-sm">₱</span>
                                                <input type="number" name="amount_paid" id="manual-amount-input" min="1" step="0.01" required
                                                       class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                            </div>
                                        </div>

                                        {{-- Method --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                Payment method <span class="text-red-500">*</span>
                                            </label>
                                            <select name="payment_method" required
                                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent bg-white">
                                                <option value="" disabled selected>Select a method</option>
                                                <option value="gcash">GCash</option>
                                                <option value="maya">Maya</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="cash">Cash (over the counter)</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>

                                        {{-- Reference --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                Reference / transaction number
                                            </label>
                                            <input type="text" name="reference_number"
                                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                                                   placeholder="e.g. GCash ref # or bank ref #">
                                        </div>

                                        {{-- Proof --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                Proof of payment <span class="text-red-500">*</span>
                                            </label>
                                            <label class="flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-200 bg-slate-50 px-4 py-6 cursor-pointer hover:border-gray-400 hover:bg-white transition-colors">
                                                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                                                <span class="text-sm text-gray-500">Click to upload screenshot or receipt</span>
                                                <span class="text-xs text-gray-400">PNG, JPG, PDF up to 5MB</span>
                                                <input type="file" name="proof_of_payment" accept="image/*,.pdf" required class="sr-only">
                                            </label>
                                        </div>

                                        {{-- Note --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                Note <span class="text-gray-400 font-normal">(optional)</span>
                                            </label>
                                            <textarea name="note" rows="2"
                                                      class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent resize-none"
                                                      placeholder="Any additional information for the admin…"></textarea>
                                        </div>

                                        <div class="flex items-center gap-3 pt-1">
                                            <button type="submit"
                                                    class="flex-1 rounded-xl bg-gray-900 py-2.5 text-sm font-semibold text-white hover:bg-gray-700 transition-colors">
                                                Submit Request
                                            </button>
                                            <button type="button" onclick="closePaymentModal()"
                                                    class="flex-1 rounded-xl border border-gray-200 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>{{-- end scrollable --}}
                        </div>
                    </div>

                </div>
                @endif

                {{-- Payment History --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-bold text-gray-900">Payment history</h2>
                        <p class="text-xs text-gray-500 mt-0.5">All recorded transactions for your account.</p>
                    </div>

                    @if($payments->isEmpty())
                        <div class="p-12 text-center">
                            <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/></svg>
                            </div>
                            <p class="font-medium text-gray-700">No payment records yet.</p>
                            <p class="mt-1 text-sm text-gray-400">Payments will appear here once recorded by the admin.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wide border-b border-gray-100">
                                        <th class="py-3 px-6 text-left">Date</th>
                                        <th class="py-3 px-4 text-left">Course</th>
                                        <th class="py-3 px-4 text-left">Method</th>
                                        <th class="py-3 px-4 text-left">Reference</th>
                                        <th class="py-3 px-4 text-right">Amount</th>
                                        <th class="py-3 px-6 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($payments as $payment)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-4 px-6 text-gray-600">
                                                {{ optional($payment->payment_date)->format('M d, Y') ?? '—' }}
                                            </td>
                                            <td class="py-4 px-4">
                                                <p class="font-medium text-gray-900">
                                                    {{ optional(optional($payment->studentEnrollment)->course)->course_name ?? 'Tuition / Downpayment' }}
                                                </p>
                                            </td>
                                            <td class="py-4 px-4 text-gray-600 capitalize">
                                                {{ $payment->payment_method ? str_replace('_', ' ', $payment->payment_method) : '—' }}
                                            </td>
                                            <td class="py-4 px-4 text-gray-500 text-xs font-mono">
                                                {{ $payment->reference_number ?? '—' }}
                                            </td>
                                            <td class="py-4 px-4 text-right font-semibold text-gray-900">
                                                ₱{{ number_format($payment->amount, 2) }}
                                            </td>
                                            <td class="py-4 px-6 text-center">
                                                @php
                                                    $badgeClass = match($payment->payment_status) {
                                                        'paid'     => 'text-green-700',
                                                        'pending'  => 'bg-orange-100 text-orange-700',
                                                        'overdue'  => 'bg-red-100 text-red-700',
                                                        'for_review' => 'bg-blue-100 text-blue-700',
                                                        default    => 'bg-gray-100 text-gray-600',
                                                    };
                                                    $badgeLabel = match($payment->payment_status) {
                                                        'for_review' => 'For review',
                                                        default      => ucfirst($payment->payment_status),
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                                    {{ $badgeLabel }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Submitted Requests --}}
                @if($paymentRequests->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-bold text-gray-900">My payment requests</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Requests you submitted, pending admin review.</p>
                    </div>
                    <div class="p-6 space-y-3">
                        @foreach($paymentRequests as $req)
                            <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-100 bg-slate-50 px-5 py-4">
                                <div class="min-w-0">
                                    <p class="font-semibold text-sm text-gray-900">₱{{ number_format($req->amount_paid, 2) }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ str_replace('_', ' ', ucfirst($req->payment_method)) }}
                                        @if($req->reference_number) · Ref: {{ $req->reference_number }} @endif
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">Submitted {{ $req->created_at->diffForHumans() }}</p>
                                    @if($req->note)
                                        <p class="text-xs text-gray-500 mt-1 italic">"{{ $req->note }}"</p>
                                    @endif
                                </div>
                                <span class="shrink-0 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold
                                    {{ $req->status === 'approved' ? 'bg-green-100 text-green-700' :
                                       ($req->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <p class="text-xs text-center text-gray-400 pb-2">
                    Payments are verified by the admin. Submitted requests may take 1–2 business days to process.
                </p>

            </div>
        </div>

            </div>
        </main>

    </div>
</div>

<script>
    function openPaymentModal(paymentId, formattedAmount, rawAmount, label, tab) {
        // Populate shared header fields
        document.getElementById('modal-label').textContent  = label;
        document.getElementById('modal-amount').textContent = formattedAmount;

        // Populate hidden payment_id inputs
        document.getElementById('online-payment-id').value = paymentId;
        document.getElementById('manual-payment-id').value = paymentId;

        // Populate amount placeholders
        document.getElementById('online-amount-input').placeholder = rawAmount;
        document.getElementById('manual-amount-input').placeholder = rawAmount;

        // Show modal
        document.getElementById('payment-modal').classList.add('is-open');

        // Switch to the requested tab
        switchTab(tab);
    }

    function closePaymentModal() {
        document.getElementById('payment-modal').classList.remove('is-open');
    }

    function switchTab(tab) {
        ['online', 'manual'].forEach(function(t) {
            document.getElementById('tab-panel-' + t).classList.toggle('is-active', t === tab);
            document.getElementById('tab-btn-' + t).classList.toggle('is-active', t === tab);
        });
    }

    // Close modal when clicking the backdrop
    document.getElementById('payment-modal').addEventListener('click', function(e) {
        if (e.target === this) closePaymentModal();
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closePaymentModal();
    });
</script>

</x-app-layout>