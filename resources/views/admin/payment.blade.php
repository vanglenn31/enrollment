<x-app-layout>

<div class="flex min-h-screen bg-gray-100">

    <!-- SIDEBAR (desktop only) -->
    <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
        @include('layouts.' . auth()->user()->role->role . '_side_bar')
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col w-full lg:ml-64">

        <!-- NAV -->
        <header class="sticky top-0 z-30 bg-white shadow-sm">
            @include('layouts.navigation')
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">

            <div class="max-w-7xl mx-auto space-y-6">

                <!-- HEADER -->
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h1 class="text-xl sm:text-3xl font-bold text-gray-900">
                            Payments
                        </h1>
                        <p class="text-sm text-gray-500">
                            Manage financial records
                        </p>
                    </div>

                    <a href="{{ route('admin.payments.create') }}"
                        class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold text-center w-full sm:w-auto">
                        + Add Payment
                    </a>

                </div>

                <!-- SEARCH -->
                <form method="GET" action="{{ route('admin.payments') }}">
                    <div class="flex flex-col sm:flex-row gap-3">

                        <input name="search" value="{{ $search ?? '' }}"
                            placeholder="Search payments..."
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500">

                        <button class="bg-slate-900 text-white px-5 py-3 rounded-xl text-sm w-full sm:w-auto">
                            Search
                        </button>

                    </div>
                </form>

                <!-- SUMMARY -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">

                    <div class="bg-white rounded-2xl border p-4 sm:p-6">
                        <h2 class="font-semibold text-gray-800">Overview</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">

                            <div class="border rounded-xl p-4">
                                <p class="text-sm text-gray-500">Total</p>
                                <p class="text-xl sm:text-2xl font-bold text-blue-600">
                                    ₱{{ number_format($payments->sum('amount'), 2) }}
                                </p>
                            </div>

                            <div class="border rounded-xl p-4">
                                <p class="text-sm text-gray-500">Pending</p>
                                <p class="text-xl sm:text-2xl font-bold text-orange-600">
                                    ₱{{ number_format($payments->where('payment_status','pending')->sum('amount'), 2) }}
                                </p>
                            </div>

                        </div>
                    </div>

                    <!-- RECENT -->
                    <div class="bg-white rounded-2xl border p-4 sm:p-6">

                        <h2 class="font-semibold text-gray-800">Recent Payments</h2>

                        <div class="mt-4 space-y-3">

                            @forelse($payments->take(2) as $payment)

                                @php
                                    $student = $payment->studentEnrollment?->student?->profile;
                                @endphp

                                <div class="border rounded-xl p-4">

                                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2">

                                        <p class="font-medium text-gray-800">
                                            {{ $student?->first_name }} {{ $student?->last_name }}
                                        </p>

                                        <span class="text-xs px-2 py-1 rounded-full w-fit
                                            {{ $payment->payment_status === 'paid'
                                                ? 'bg-green-100 text-green-600'
                                                : 'bg-orange-100 text-orange-600' }}">
                                            {{ ucfirst($payment->payment_status) }}
                                        </span>

                                    </div>

                                    <p class="text-sm text-gray-500">
                                        ₱{{ number_format($payment->amount, 2) }}
                                    </p>

                                </div>

                            @empty
                                <p class="text-sm text-gray-500">No recent payments</p>
                            @endforelse

                        </div>

                    </div>

                </div>

                <!-- TABLE -->
                <div class="bg-white rounded-2xl border p-3 sm:p-6">

                    <div class="overflow-x-auto">

                        <table class="min-w-[700px] w-full text-sm">

                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="p-3 text-left">Student</th>
                                    <th class="p-3 text-left">Amount</th>
                                    <th class="p-3 text-left">Status</th>
                                    <th class="p-3 text-left">Method</th>
                                    <th class="p-3 text-left">Date</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y">

                                @forelse($payments as $payment)

                                    @php
                                        $student = $payment->studentEnrollment?->student?->profile;
                                    @endphp

                                    <tr class="hover:bg-gray-50">

                                        <td class="p-3 whitespace-nowrap">
                                            {{ $student?->first_name }} {{ $student?->last_name }}
                                        </td>

                                        <td class="p-3 whitespace-nowrap">
                                            ₱{{ number_format($payment->amount, 2) }}
                                        </td>

                                        <td class="p-3 whitespace-nowrap">
                                            <span class="{{ $payment->payment_status === 'paid' ? 'text-green-600' : 'text-orange-600' }} font-medium">
                                                {{ ucfirst($payment->payment_status) }}
                                            </span>
                                        </td>

                                        <td class="p-3 whitespace-nowrap">
                                            {{ ucfirst($payment->payment_method ?? 'cash') }}
                                        </td>

                                        <td class="p-3 whitespace-nowrap">
                                            {{ $payment->payment_date?->format('M d, Y') ?? 'Pending' }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5" class="text-center p-6 text-gray-400">
                                            No payments found
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

</x-app-layout>