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
                        <h1 class="text-xl sm:text-3xl font-bold text-gray-900">Payments</h1>
                        <p class="text-sm text-gray-500">Manage financial records</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">

                        {{-- Payment Requests badge/link --}}
                        @php $pendingRequestCount = \App\Models\PaymentRequest::where('status','pending')->count(); @endphp
                        <a href="{{ route('admin.payment-requests.index') }}"
                           class="relative inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Payment Requests
                            @if($pendingRequestCount > 0)
                                <span class="absolute -top-1.5 -right-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-orange-500 text-white text-xs font-bold">
                                    {{ $pendingRequestCount }}
                                </span>
                            @endif
                        </a>

                        {{-- ── NEW: Record Downpayment ── --}}
                        <a href="{{ route('admin.payments.downpayment') }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2.5 text-sm font-semibold text-indigo-700 hover:bg-indigo-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 9V7a5 5 0 00-10 0v2M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Record Downpayment
                        </a>

                        <a href="{{ route('admin.payments.create') }}"
                            class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold text-center">
                            + Add Payment
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-2xl px-5 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- SEARCH -->
                <form method="GET" action="{{ route('admin.payments') }}">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input name="search" value="{{ $search ?? '' }}"
                            placeholder="Search by name or student number..."
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        <button class="bg-slate-900 text-white px-5 py-3 rounded-xl text-sm w-full sm:w-auto">
                            Search
                        </button>
                    </div>
                </form>

                <!-- SUMMARY CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">

                    <div class="bg-white rounded-2xl border p-4 sm:p-6">
                        <h2 class="font-semibold text-gray-800">Overview</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">

                            <div class="border rounded-xl p-4">
                                <p class="text-sm text-gray-500">Total Collected</p>
                                <p class="text-xl sm:text-2xl font-bold text-blue-600">
                                    ₱{{ number_format($payments->whereNotIn('payment_status', ['cancelled'])->sum('amount'), 2) }}
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
                                @php $student = $payment->studentEnrollment?->student?->profile; @endphp
                                <div class="border rounded-xl p-4">
                                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2">
                                        <p class="font-medium text-gray-800">
                                            {{ $student?->first_name }} {{ $student?->last_name }}
                                        </p>
                                        <div class="flex items-center gap-2">
                                            @if ($payment->payment_type === 'downpayment')
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 font-medium">
                                                    Downpayment
                                                </span>
                                            @endif
                                            <span class="text-xs px-2 py-1 rounded-full w-fit
                                                {{ $payment->payment_status === 'paid'
                                                    ? 'bg-green-100 text-green-600'
                                                    : ($payment->payment_status === 'for_review'
                                                        ? 'bg-blue-100 text-blue-600'
                                                        : 'bg-orange-100 text-orange-600') }}">
                                                {{ $payment->payment_status === 'for_review' ? 'For Review' : ucfirst($payment->payment_status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500">₱{{ number_format($payment->amount, 2) }}</p>
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
                        <table class="min-w-[900px] w-full text-sm">

                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="p-3 text-left">Student</th>
                                    <th class="p-3 text-left">Type</th>
                                    <th class="p-3 text-left">Amount</th>
                                    <th class="p-3 text-left">Status</th>
                                    <th class="p-3 text-left">Method</th>
                                    <th class="p-3 text-left">Date</th>
                                    <th class="p-3 text-left">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y">

                                @forelse($payments as $payment)
                                    @php $studentProfile = $payment->studentEnrollment?->student?->profile; @endphp

                                    <tr class="hover:bg-gray-50">

                                        <td class="p-3 whitespace-nowrap">
                                            {{ $studentProfile?->first_name }} {{ $studentProfile?->last_name }}
                                        </td>

                                        {{-- ── TYPE badge ── --}}
                                        <td class="p-3 whitespace-nowrap">
                                            @if ($payment->payment_type === 'downpayment')
                                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                                    Downpayment
                                                </span>
                                            @else
                                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                    Tuition
                                                </span>
                                            @endif
                                        </td>

                                        <td class="p-3 whitespace-nowrap">
                                            ₱{{ number_format($payment->amount, 2) }}
                                        </td>

                                        <td class="p-3 whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                                @if($payment->payment_status === 'paid')        bg-green-100 text-green-700
                                                @elseif($payment->payment_status === 'partial') bg-blue-100 text-blue-700
                                                @elseif($payment->payment_status === 'for_review') bg-purple-100 text-purple-700
                                                @elseif($payment->payment_status === 'cancelled') bg-red-100 text-red-600
                                                @else bg-orange-100 text-orange-600
                                                @endif">
                                                {{ $payment->payment_status === 'for_review' ? 'For Review' : ucfirst($payment->payment_status) }}
                                            </span>
                                        </td>

                                        <td class="p-3 whitespace-nowrap">
                                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'cash')) }}
                                        </td>

                                        <td class="p-3 whitespace-nowrap">
                                            {{ $payment->payment_date?->format('M d, Y') ?? 'Pending' }}
                                        </td>

                                        <td class="p-3 whitespace-nowrap flex items-center gap-3">
                                            <a href="{{ route('admin.payments.edit', $payment) }}"
                                                class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                Edit
                                            </a>

                                            {{-- Confirm downpayment shortcut --}}
                                            @if ($payment->payment_type === 'downpayment' && $payment->payment_status === 'pending')
                                                <form action="{{ route('admin.payments.confirm-downpayment', $payment) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-xs text-green-600 hover:text-green-800 font-medium">
                                                        ✓ Confirm
                                                    </button>
                                                </form>
                                            @endif

                                            @if($payment->payment_status === 'for_review')
                                                @php $pendReq = $payment->paymentRequests->where('status','pending')->first(); @endphp
                                                @if($pendReq)
                                                    <a href="{{ route('admin.payment-requests.show', $pendReq) }}"
                                                       class="text-xs text-purple-600 hover:text-purple-800 font-medium">
                                                        Review →
                                                    </a>
                                                @endif
                                            @endif
                                        </td>

                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center p-6 text-gray-400">
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