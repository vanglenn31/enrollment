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

                    <!-- HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Edit Payment</h1>
                            <p class="mt-1 text-sm text-gray-500">Update payment details or status.</p>
                        </div>
                        <a href="{{ route('admin.payments') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            ← Back to payments
                        </a>
                    </div>

                    <!-- TUITION SUMMARY -->
                    <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-6 space-y-4">

                        <h2 class="text-base font-semibold text-gray-700">Tuition Summary</h2>

                        @php $student = $enrollment->student; @endphp

                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">
                                    {{ $student->profile->first_name }} {{ $student->profile->last_name }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $student->student_number }}
                                    &nbsp;·&nbsp;
                                    {{ $enrollment->course->name ?? 'N/A' }}
                                    &nbsp;·&nbsp;
                                    {{ $enrollment->term->name ?? 'N/A' }}
                                </p>
                            </div>

                            @if ($balance <= 0 && $totalTuition > 0)
                                <span class="inline-flex items-center text-xs font-semibold bg-green-100 text-green-700 px-3 py-1.5 rounded-full">
                                    ✓ Fully Paid
                                </span>
                            @else
                                <span class="inline-flex items-center text-xs font-semibold bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full">
                                    Balance Remaining
                                </span>
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

                    <!-- EDIT FORM -->
                    <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-6">

                        <h2 class="text-base font-semibold text-gray-700 mb-5">Payment #{{ $payment->id }}</h2>

                        @if ($errors->any())
                            <div class="mb-4 bg-red-50 border border-red-200 rounded-2xl px-4 py-3 text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="mb-4 bg-green-50 border border-green-200 rounded-2xl px-4 py-3 text-sm text-green-700">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.payments.update', $payment) }}" method="POST" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Amount</label>
                                <input type="number" step="0.01" name="amount"
                                    value="{{ old('amount', $payment->amount) }}"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Date</label>
                                <input type="date" name="payment_date"
                                    value="{{ old('payment_date', $payment->payment_date?->format('Y-m-d')) }}"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <select name="payment_method"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select method</option>
                                    <option value="cash"          {{ old('payment_method', $payment->payment_method) === 'cash'          ? 'selected' : '' }}>Cash</option>
                                    <option value="gcash"         {{ old('payment_method', $payment->payment_method) === 'gcash'         ? 'selected' : '' }}>GCash</option>
                                    <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                                <select name="payment_status"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="pending"   {{ old('payment_status', $payment->payment_status) === 'pending'   ? 'selected' : '' }}>Pending</option>
                                    <option value="partial"   {{ old('payment_status', $payment->payment_status) === 'partial'   ? 'selected' : '' }}>Partial</option>
                                    <option value="paid"      {{ old('payment_status', $payment->payment_status) === 'paid'      ? 'selected' : '' }}>Paid</option>
                                    <option value="cancelled" {{ old('payment_status', $payment->payment_status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.payments') }}"
                                    class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                                    Update Payment
                                </button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </main>

    </div>

</div>

</x-app-layout>
