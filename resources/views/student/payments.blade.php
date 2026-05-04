<x-app-layout>
    <div class="grid grid-cols-5 2xl:grid-cols-6 gap-x-0 gap-y-0">
        <div class="col-span-1 fixed h-screen z-0">
            @include('layouts.student_side_bar')
        </div>
        <div class="col-span-5 col-start-2 sticky top-0 z-50">
            @include('layouts.navigation')
        </div>

        <div class="col-span-4 col-start-2 p-6 z-30 w-full">
            <div class="max-w-7xl mx-auto space-y-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold">Payments</h1>
                            <p class="text-sm text-gray-500 mt-1">View your payment history, downpayment status, and any pending balance.</p>
                        </div>
                        <div class="space-x-2 text-sm">
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-slate-700">Total paid: ₱{{ number_format($totalPaid, 2) }}</span>
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-slate-700">Pending: ₱{{ number_format($totalPending, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-gray-200 p-4 bg-slate-50">
                            <p class="text-sm text-gray-500">Paid amount</p>
                            <p class="mt-2 text-2xl font-semibold text-gray-900">₱{{ number_format($totalPaid, 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 p-4 bg-slate-50">
                            <p class="text-sm text-gray-500">Pending amount</p>
                            <p class="mt-2 text-2xl font-semibold text-gray-900">₱{{ number_format($totalPending, 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 p-4 bg-slate-50">
                            <p class="text-sm text-gray-500">Overdue</p>
                            <p class="mt-2 text-2xl font-semibold text-red-600">₱{{ number_format($overdue, 2) }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-dashed border-gray-300 p-4 text-sm text-gray-600 bg-slate-50">
                        Payments are managed by the admin. Any records or balances shown here are recorded from their system.
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment history</h2>
                    @if($payments->isEmpty())
                        <div class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-gray-500">
                            <p class="font-medium">No payment records found.</p>
                            <p class="mt-2">Payments will appear here once the admin records them.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-700">
                                <thead class="border-b border-gray-200">
                                    <tr>
                                        <th class="py-3 px-4 font-medium">Date</th>
                                        <th class="py-3 px-4 font-medium">Description</th>
                                        <th class="py-3 px-4 font-medium">Amount</th>
                                        <th class="py-3 px-4 font-medium">Status</th>
                                        <th class="py-3 px-4 font-medium">Course</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td class="py-4 px-4">{{ optional($payment->payment_date)->format('M d, Y') ?? 'Pending' }}</td>
                                            <td class="py-4 px-4">Downpayment / Tuition</td>
                                            <td class="py-4 px-4">₱{{ number_format($payment->amount, 2) }}</td>
                                            <td class="py-4 px-4">
                                                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $payment->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                                    {{ ucfirst($payment->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">{{ optional(optional($payment->studentEnrollment)->course)->course_name ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
