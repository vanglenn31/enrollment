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
                            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Record Downpayment</h1>
                            <p class="mt-1 text-sm text-gray-500">
                                Students must pay a downpayment before they can be assigned courses.
                            </p>
                        </div>
                        <a href="{{ route('admin.payments') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            ← Back to payments
                        </a>
                    </div>

                    {{-- ── STEP 1: LOOK UP STUDENT ── --}}
                    <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-6">
                        <h2 class="text-base font-semibold text-gray-700 mb-4">Look up Student</h2>

                        <form method="GET" action="{{ route('admin.payments.downpayment') }}" class="flex gap-3">
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

                    {{-- ── NOT FOUND ── --}}
                    @if ($studentNumber && ! $student)
                        <div class="bg-red-50 border border-red-200 rounded-2xl px-5 py-4 text-sm text-red-700">
                            No student found with number <strong>{{ $studentNumber }}</strong>.
                        </div>
                    @endif

                    {{-- ── STUDENT FOUND ── --}}
                    @if ($student)

                        {{-- Student card --}}
                        <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-6">
                            <h2 class="text-base font-semibold text-gray-700 mb-4">Student Info</h2>

                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-bold text-lg shrink-0">
                                    {{ strtoupper(substr($student->profile->first_name, 0, 1)) }}{{ strtoupper(substr($student->profile->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $student->profile->first_name }} {{ $student->profile->last_name }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ $student->student_number }}
                                    </p>
                                </div>

                                @if ($existingDownpayment && $existingDownpayment->payment_status === 'paid')
                                    <span class="ml-auto inline-flex items-center gap-1.5 text-xs font-semibold bg-green-100 text-green-700 px-3 py-1.5 rounded-full">
                                        ✓ Downpayment Cleared
                                    </span>
                                @else
                                    <span class="ml-auto inline-flex items-center gap-1.5 text-xs font-semibold bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full">
                                        Downpayment Pending
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Already has a confirmed downpayment --}}
                        @if ($existingDownpayment && $existingDownpayment->payment_status === 'paid')
                            <div class="bg-green-50 border border-green-200 rounded-2xl px-5 py-5 text-center">
                                <p class="text-green-700 font-semibold text-sm">
                                    ✓ Downpayment already confirmed on
                                    {{ $existingDownpayment->payment_date?->format('M d, Y') }}.
                                </p>
                                <p class="text-green-600 text-xs mt-1">
                                    This student is cleared for course assignment.
                                </p>
                                <a href="{{ route('admin.enrollment.assign', $student) }}"
                                   class="mt-4 inline-block bg-indigo-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-indigo-700">
                                    Assign Courses →
                                </a>
                            </div>

                        @elseif ($existingDownpayment && $existingDownpayment->payment_status === 'pending')

                            {{-- Pending downpayment — admin can confirm it here --}}
                            <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 space-y-4">
                                <p class="text-sm font-semibold text-orange-700">
                                    A pending downpayment of
                                    ₱{{ number_format($existingDownpayment->amount, 2) }}
                                    was recorded on
                                    {{ $existingDownpayment->payment_date?->format('M d, Y') }}.
                                </p>
                                <p class="text-xs text-orange-600">
                                    Confirm it once the cash / transfer has been verified.
                                </p>
                                <form action="{{ route('admin.payments.confirm-downpayment', $existingDownpayment) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-green-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-green-700">
                                        ✓ Confirm Downpayment
                                    </button>
                                </form>
                            </div>

                        @else

                            {{-- No downpayment yet — show form --}}
                            <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-6">
                                <h2 class="text-base font-semibold text-gray-700 mb-5">Downpayment Details</h2>

                                @if ($errors->any())
                                    <div class="mb-4 bg-red-50 border border-red-200 rounded-2xl px-4 py-3 text-sm text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                @endif

                                <form action="{{ route('admin.payments.downpayment.store') }}" method="POST" class="space-y-5">
                                    @csrf

                                    <input type="hidden" name="student_number" value="{{ $student->student_number }}">

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                                        <input type="number" step="0.01" name="amount"
                                            value="{{ old('amount', $student->downpayment_amount ?? '') }}"
                                            placeholder="e.g. 5000.00"
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
                                        <label class="block text-sm font-medium text-gray-700">Reference Number
                                            <span class="text-gray-400 font-normal">(optional)</span>
                                        </label>
                                        <input type="text" name="reference_number"
                                            value="{{ old('reference_number') }}"
                                            placeholder="GCash / bank ref"
                                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <select name="payment_status"
                                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                                            {{-- Choose 'paid' if cash is in hand, 'pending' if awaiting verification --}}
                                            <option value="pending" {{ old('payment_status', 'pending') === 'pending' ? 'selected' : '' }}>
                                                Pending — record now, confirm later
                                            </option>
                                            <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>
                                                Paid — immediately unlock course assignment
                                            </option>
                                            <option value="cancelled" {{ old('payment_status') === 'cancelled' ? 'selected' : '' }}>
                                                Cancelled
                                            </option>
                                        </select>
                                        <p class="mt-1.5 text-xs text-gray-400">
                                            Setting status to <strong>Paid</strong> will immediately allow the admin to assign courses to this student.
                                        </p>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit"
                                            class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                                            Save Downpayment
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