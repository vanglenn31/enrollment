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
            <div class="min-h-screen bg-gray-100 flex items-start justify-center py-8">
                <div class="w-full max-w-3xl space-y-6">

                    <!-- PAGE HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Create Payment</h1>
                            <p class="mt-1 text-sm text-gray-500">Add a payment record for a student.</p>
                        </div>
                        <a href="{{ route('admin.payments') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            ← Back to payments
                        </a>
                    </div>

                    <!-- STEP 1: LOOK UP STUDENT -->
                    <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-6">
                        <h2 class="text-base font-semibold text-gray-700 mb-4">Look up Student</h2>

                        <form method="GET" action="{{ route('admin.payments.create') }}" class="flex gap-3">
                            <input type="text" name="student_number"
                                value="{{ $studentNumber ?? '' }}"
                                placeholder="Enter student number"
                                class="flex-1 rounded-2xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500" />
                            <button type="submit"
                                class="bg-slate-900 text-white px-5 py-3 rounded-2xl text-sm font-semibold whitespace-nowrap">
                                Look up
                            </button>
                        </form>
                    </div>

                    @if ($studentNumber && ! $enrollment)
                        <!-- NOT FOUND -->
                        <div class="bg-red-50 border border-red-200 rounded-2xl px-5 py-4 text-sm text-red-700">
                            No active enrollment found for student number <strong>{{ $studentNumber }}</strong>.
                        </div>
                    @endif

                    @if ($enrollment)

                        <!-- TUITION SUMMARY -->
                        <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-6 space-y-4">

                            <h2 class="text-base font-semibold text-gray-700">Tuition Summary</h2>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500">Student</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $enrollment->student->profile->first_name }}
                                        {{ $enrollment->student->profile->last_name }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ $enrollment->student->student_number }}
                                        &nbsp;·&nbsp;
                                        {{ $enrollment->course->name ?? 'N/A' }}
                                        &nbsp;·&nbsp;
                                        {{ $enrollment->term->name ?? 'N/A' }}
                                    </p>
                                </div>

                                @if ($alreadyPaid)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold bg-green-100 text-green-700 px-3 py-1.5 rounded-full">
                                        ✓ Fully Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full">
                                        Balance Remaining
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-3 gap-3 text-center">

                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <p class="text-xs text-gray-500 mb-1">Total Tuition</p>
                                    <p class="text-lg font-bold text-gray-900">
                                        ₱{{ number_format($totalTuition, 2) }}
                                    </p>
                                </div>

                                <div class="bg-green-50 rounded-2xl p-4">
                                    <p class="text-xs text-gray-500 mb-1">Amount Paid</p>
                                    <p class="text-lg font-bold text-green-600">
                                        ₱{{ number_format($amountPaid, 2) }}
                                    </p>
                                </div>

                                <div class="bg-orange-50 rounded-2xl p-4">
                                    <p class="text-xs text-gray-500 mb-1">Balance</p>
                                    <p class="text-lg font-bold text-orange-600">
                                        ₱{{ number_format($balance, 2) }}
                                    </p>
                                </div>

                            </div>

                            <!-- Payment history for this enrollment -->
                            @if ($enrollment->payments->isNotEmpty())
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                        Payment History
                                    </p>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-xs text-left">
                                            <thead class="bg-gray-50 text-gray-500">
                                                <tr>
                                                    <th class="px-3 py-2">Date</th>
                                                    <th class="px-3 py-2">Amount</th>
                                                    <th class="px-3 py-2">Method</th>
                                                    <th class="px-3 py-2">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y text-gray-700">
                                                @foreach ($enrollment->payments as $p)
                                                    <tr>
                                                        <td class="px-3 py-2">
                                                            {{ $p->payment_date?->format('M d, Y') ?? '—' }}
                                                        </td>
                                                        <td class="px-3 py-2">
                                                            ₱{{ number_format($p->amount, 2) }}
                                                        </td>
                                                        <td class="px-3 py-2">
                                                            {{ ucfirst($p->payment_method ?? '—') }}
                                                        </td>
                                                        <td class="px-3 py-2">
                                                            <span class="px-2 py-0.5 rounded-full
                                                                @if($p->payment_status === 'paid') bg-green-100 text-green-700
                                                                @elseif($p->payment_status === 'cancelled') bg-red-100 text-red-600
                                                                @elseif($p->payment_status === 'partial') bg-blue-100 text-blue-600
                                                                @else bg-orange-100 text-orange-600
                                                                @endif">
                                                                {{ ucfirst($p->payment_status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <!-- PAYMENT FORM (blocked when fully paid) -->
                        @if ($alreadyPaid)

                            <div class="bg-green-50 border border-green-200 rounded-2xl px-5 py-5 text-center">
                                <p class="text-green-700 font-semibold text-sm">
                                    ✓ This student has already fully paid their tuition.
                                </p>
                                <p class="text-green-600 text-xs mt-1">
                                    No further payment can be recorded.
                                </p>
                            </div>

                        @else

                            <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-6">

                                <h2 class="text-base font-semibold text-gray-700 mb-5">New Payment</h2>

                                @if ($errors->any())
                                    <div class="mb-4 bg-red-50 border border-red-200 rounded-2xl px-4 py-3 text-sm text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                @endif

                                <form action="{{ route('admin.payments.store') }}" method="POST" class="space-y-5">
                                    @csrf

                                    <!-- Hidden: resolved enrollment id -->
                                    <input type="hidden" name="student_number" value="{{ $enrollment->student->student_number }}">

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">
                                            Amount
                                            <span class="text-gray-400 font-normal">(remaining balance: ₱{{ number_format($balance, 2) }})</span>
                                        </label>
                                        <input type="number" step="0.01" name="amount"
                                            max="{{ $balance }}"
                                            value="{{ old('amount') }}"
                                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Payment Date</label>
                                        <input type="date" name="payment_date"
                                            value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                                        <select name="payment_method"
                                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Select method</option>
                                            <option value="cash"          {{ old('payment_method') === 'cash'          ? 'selected' : '' }}>Cash</option>
                                            <option value="gcash"         {{ old('payment_method') === 'gcash'         ? 'selected' : '' }}>GCash</option>
                                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                                        <select name="payment_status"
                                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                                            <option value="pending"   {{ old('payment_status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                                            <option value="partial"   {{ old('payment_status') === 'partial'   ? 'selected' : '' }}>Partial</option>
                                            <option value="paid"      {{ old('payment_status') === 'paid'      ? 'selected' : '' }}>Paid</option>
                                            <option value="cancelled" {{ old('payment_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit"
                                            class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                                            Save Payment
                                        </button>
                                    </div>

                                </form>

                            </div>

                        @endif

                    @endif

                </div>
            </div>
        </main>

    </div>

</div>

</x-app-layout>