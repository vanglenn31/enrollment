<x-app-layout>

<div class="flex min-h-screen bg-gray-100">

    <!-- SIDEBAR -->
    <aside class="hidden lg:block fixed inset-y-0 left-0 w-64 z-30 bg-white shadow">
        @include('layouts.admin_side_bar')
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="flex-1 w-full lg:ml-64 flex flex-col">

        <!-- NAV -->
        <header class="sticky top-0 z-50 bg-white shadow-sm">
            @include('layouts.navigation')
        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-3xl mx-auto space-y-6">

                <!-- BACK + TITLE -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Review Payment Request</h1>
                        <p class="mt-1 text-sm text-gray-500">Request #{{ $paymentRequest->id }}</p>
                    </div>
                    <a href="{{ route('admin.payment-requests.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        ← Back to requests
                    </a>
                </div>

                @if(session('error'))
                    <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- STUDENT + TUITION SUMMARY -->
                <div class="bg-white rounded-3xl shadow-sm p-5 sm:p-6 space-y-4">
                    <h2 class="text-base font-semibold text-gray-700">Tuition Summary</h2>

                    @php $profile = $paymentRequest->student?->profile; @endphp
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $profile?->first_name }} {{ $profile?->last_name }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $enrollment->student?->student_number ?? '—' }}
                                &nbsp;·&nbsp;
                                {{ $enrollment->course?->name ?? 'N/A' }}
                                &nbsp;·&nbsp;
                                {{ $enrollment->term?->name ?? 'N/A' }}
                            </p>
                        </div>
                        @if($balance <= 0 && $totalTuition > 0)
                            <span class="inline-flex items-center text-xs font-semibold bg-green-100 text-green-700 px-3 py-1.5 rounded-full">✓ Fully Paid</span>
                        @else
                            <span class="inline-flex items-center text-xs font-semibold bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full">Balance Remaining</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div class="bg-gray-50 rounded-2xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Total Tuition</p>
                            <p class="text-lg font-bold text-gray-900">₱{{ number_format($totalTuition, 2) }}</p>
                        </div>
                        <div class="bg-green-50 rounded-2xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Amount Paid</p>
                            <p class="text-lg font-bold text-green-600">₱{{ number_format($amountPaid, 2) }}</p>
                        </div>
                        <div class="bg-orange-50 rounded-2xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Balance</p>
                            <p class="text-lg font-bold text-orange-600">₱{{ number_format($balance, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- REQUEST DETAILS -->
                <div class="bg-white rounded-3xl shadow-sm p-5 sm:p-6 space-y-5">
                    <h2 class="text-base font-semibold text-gray-700">Request Details</h2>

                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Amount claimed</dt>
                            <dd class="mt-1 text-xl font-bold text-gray-900">₱{{ number_format($paymentRequest->amount_paid, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Status</dt>
                            <dd class="mt-1">
                                @php
                                    $badgeClass = match($paymentRequest->status) {
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        default    => 'bg-orange-100 text-orange-700',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                    {{ ucfirst($paymentRequest->status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Payment method</dt>
                            <dd class="mt-1 font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $paymentRequest->payment_method) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Reference number</dt>
                            <dd class="mt-1 font-mono text-gray-700">{{ $paymentRequest->reference_number ?? '—' }}</dd>
                        </div>
                        <div class="col-span-2">
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Student note</dt>
                            <dd class="mt-1 text-gray-700 italic">{{ $paymentRequest->note ?? 'None' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Submitted</dt>
                            <dd class="mt-1 text-gray-600">{{ $paymentRequest->created_at->format('M d, Y · g:i A') }}</dd>
                        </div>
                        @if($paymentRequest->reviewed_at)
                        <div>
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Reviewed</dt>
                            <dd class="mt-1 text-gray-600">{{ $paymentRequest->reviewed_at->format('M d, Y · g:i A') }}</dd>
                        </div>
                        @endif
                        @if($paymentRequest->admin_note)
                        <div class="col-span-2">
                            <dt class="text-xs text-gray-500 uppercase tracking-wide">Admin note</dt>
                            <dd class="mt-1 text-gray-700 italic">{{ $paymentRequest->admin_note }}</dd>
                        </div>
                        @endif
                    </dl>

                    <!-- PROOF OF PAYMENT -->
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-3">Proof of payment</p>
                        @php
                            $proofUrl  = asset('storage/' . $paymentRequest->proof_of_payment);
                            $isPdf     = str_ends_with(strtolower($paymentRequest->proof_of_payment), '.pdf');
                        @endphp

                        @if($isPdf)
                            <a href="{{ $proofUrl }}" target="_blank"
                               class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-blue-600 hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                          d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                                </svg>
                                View PDF receipt
                            </a>
                        @else
                            <a href="{{ $proofUrl }}" target="_blank" class="block">
                                <img src="{{ $proofUrl }}" alt="Proof of payment"
                                     class="max-h-80 w-auto rounded-2xl border border-gray-200 object-contain hover:opacity-90 transition-opacity cursor-zoom-in">
                            </a>
                            <p class="mt-1 text-xs text-gray-400">Click to open full size</p>
                        @endif
                    </div>
                </div>

                <!-- REVIEW ACTIONS (only shown if still pending) -->
                @if($paymentRequest->isPending())
                <div x-data="{ rejectOpen: false }" class="space-y-3">

                    <!-- APPROVE -->
                    <form action="{{ route('admin.payment-requests.approve', $paymentRequest) }}" method="POST">
                        @csrf
                        <div class="bg-white rounded-3xl shadow-sm p-5 sm:p-6 space-y-4">
                            <h2 class="text-base font-semibold text-gray-700">Approve Request</h2>
                            <p class="text-sm text-gray-500">
                                Approving will mark this payment as
                                <strong class="text-gray-700">₱{{ number_format($paymentRequest->amount_paid, 2) }}</strong>
                                received and update the student's balance.
                            </p>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Admin note <span class="text-gray-400 font-normal">(optional)</span>
                                </label>
                                <textarea name="admin_note" rows="2"
                                          class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"
                                          placeholder="e.g. Verified via GCash portal"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                        class="rounded-xl bg-green-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition-colors">
                                    ✓ Approve Payment
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- REJECT -->
                    <div class="bg-white rounded-3xl shadow-sm p-5 sm:p-6 space-y-4">
                        <button @click="rejectOpen = !rejectOpen"
                                class="flex items-center justify-between w-full text-left">
                            <h2 class="text-base font-semibold text-red-600">Reject Request</h2>
                            <svg :class="rejectOpen ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="rejectOpen" x-cloak>
                            <form action="{{ route('admin.payment-requests.reject', $paymentRequest) }}" method="POST" class="space-y-4">
                                @csrf
                                <p class="text-sm text-gray-500">
                                    Rejecting will revert the payment back to <strong>pending</strong> so the student can resubmit.
                                    A reason is required.
                                </p>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Reason for rejection <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="admin_note" rows="3" required
                                              class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                                              placeholder="e.g. Screenshot is unclear, reference number not found…"></textarea>
                                    @error('admin_note') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit"
                                            class="rounded-xl bg-red-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition-colors">
                                        Reject Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                @else
                    <!-- Already reviewed notice -->
                    <div class="rounded-2xl border border-gray-200 bg-white px-5 py-4 text-sm text-gray-500 text-center">
                        This request was <strong class="text-gray-700">{{ $paymentRequest->status }}</strong>
                        on {{ $paymentRequest->reviewed_at?->format('M d, Y') }}
                        @if($paymentRequest->reviewer) by {{ $paymentRequest->reviewer->name }} @endif.
                    </div>
                @endif

            </div>
        </main>

    </div>
</div>

</x-app-layout>
