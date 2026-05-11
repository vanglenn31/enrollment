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
            <div class="max-w-7xl mx-auto space-y-6">

                <!-- HEADER -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Payment Requests</h1>
                        <p class="mt-1 text-sm text-gray-500">Review and approve student-submitted payment proofs.</p>
                    </div>
                    @if($pendingCount > 0)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-orange-100 text-orange-700 px-4 py-2 text-sm font-semibold">
                            <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                            {{ $pendingCount }} pending review
                        </span>
                    @endif
                </div>

                @if(session('success'))
                    <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- FILTER TABS -->
                <div class="flex gap-2 flex-wrap">
                    @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $value => $label)
                        <a href="{{ route('admin.payment-requests.index', ['filter' => $value]) }}"
                           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors
                               {{ $filter === $value
                                   ? 'bg-slate-900 text-white'
                                   : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                            {{ $label }}
                            @if($value === 'pending' && $pendingCount > 0)
                                <span class="ml-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-orange-500 text-white text-xs">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <!-- TABLE -->
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                    @if($requests->isEmpty())
                        <div class="p-12 text-center">
                            <p class="font-medium text-gray-600">No {{ $filter !== 'all' ? $filter : '' }} requests found.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-[700px] w-full text-sm">
                                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                                    <tr>
                                        <th class="px-5 py-3 text-left">Student</th>
                                        <th class="px-4 py-3 text-left">Amount</th>
                                        <th class="px-4 py-3 text-left">Method</th>
                                        <th class="px-4 py-3 text-left">Reference</th>
                                        <th class="px-4 py-3 text-left">Submitted</th>
                                        <th class="px-4 py-3 text-left">Status</th>
                                        <th class="px-4 py-3 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($requests as $req)
                                        @php
                                            $profile = $req->student?->profile;
                                            $badgeClass = match($req->status) {
                                                'approved' => 'bg-green-100 text-green-700',
                                                'rejected' => 'bg-red-100 text-red-700',
                                                default    => 'bg-orange-100 text-orange-700',
                                            };
                                        @endphp
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-5 py-4">
                                                <p class="font-medium text-gray-900">
                                                    {{ $profile?->first_name }} {{ $profile?->last_name }}
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    {{ $req->payment?->studentEnrollment?->term?->name ?? '—' }}
                                                </p>
                                            </td>
                                            <td class="px-4 py-4 font-semibold text-gray-900">
                                                ₱{{ number_format($req->amount_paid, 2) }}
                                            </td>
                                            <td class="px-4 py-4 text-gray-600 capitalize">
                                                {{ str_replace('_', ' ', $req->payment_method) }}
                                            </td>
                                            <td class="px-4 py-4 text-gray-500 text-xs font-mono">
                                                {{ $req->reference_number ?? '—' }}
                                            </td>
                                            <td class="px-4 py-4 text-gray-500">
                                                {{ $req->created_at->format('M d, Y') }}
                                                <br>
                                                <span class="text-xs text-gray-400">{{ $req->created_at->diffForHumans() }}</span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                                    {{ ucfirst($req->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <a href="{{ route('admin.payment-requests.show', $req) }}"
                                                   class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                    Review →
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- PAGINATION -->
                        @if($requests->hasPages())
                            <div class="px-5 py-4 border-t border-gray-100">
                                {{ $requests->links() }}
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </main>

    </div>
</div>

</x-app-layout>
