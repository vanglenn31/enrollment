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
</style>

<div class="page-wrap flex min-h-screen bg-gray-50">

    <!-- SIDEBAR -->
    <aside class="hidden lg:block fixed inset-y-0 left-0 w-64 z-30 w-full">
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
            <div class="max-w-6xl mx-auto space-y-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold">Enrollment</h1>
                            <p class="text-sm text-gray-500 mt-1">Track your enrollment progress and see what the next required step is.</p>
                        </div>
                        <div class="space-x-2 text-sm">
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-slate-700">Status: {{ $student?->is_verified == true ? 'Verified' : 'Pending' }}</span>
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-slate-700">Program: {{ $student?->programRelation->name ?? 'Not assigned' }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800">Enrollment checklist</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-gray-200 p-4">
                            <p class="text-sm text-gray-500">Application submitted</p>
                            <p class="mt-2 font-semibold text-green-700">Completed</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 p-4">
                            <p class="text-sm text-gray-500">Downpayment</p>
                            <p class="mt-2 font-semibold {{ $hasDownpayment ? 'text-green-700' : 'text-orange-600' }}">{{ $hasDownpayment ? 'Paid' : 'Required' }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 p-4">
                            <p class="text-sm text-gray-500">Admin verification</p>
                            <p class="mt-2 font-semibold {{ $student?->is_verified == true ? 'text-green-700' : 'text-orange-600' }}">{{ $student?->is_verified == true ? 'Verified' : 'Pending' }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 p-4">
                            <p class="text-sm text-gray-500">Course assignment</p>
                            <p class="mt-2 font-semibold {{ $enrollments->isNotEmpty() ? 'text-green-700' : 'text-orange-600' }}">{{ $enrollments->isNotEmpty() ? 'Assigned' : 'Awaiting admin' }}</p>
                        </div>
                    </div>
                    <div class="mt-6 rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-700">
                        <p class="font-semibold">Note:</p>
                        <p class="mt-1">Course enrollment is completed by an admin only. Once your downpayment is paid and verification is finished, the admin will assign your courses.</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800">Current enrollment details</h2>
                    @if($enrollments->isEmpty())
                        <div class="mt-4 rounded-2xl border border-dashed border-gray-300 p-6 text-gray-600">
                            <p class="font-medium">No course assignments have been made yet.</p>
                            <p class="mt-2">Once your application is verified and downpayment is confirmed, the admin can enroll you in courses.</p>
                        </div>
                    @else
                        <div class="mt-4 space-y-4">
                            @foreach($enrollments as $enrollment)
                                <div class="rounded-2xl border border-gray-200 p-4">
                                    <p class="font-semibold text-gray-900">{{ optional($enrollment->course)->course_code }} - {{ optional($enrollment->course)->course_name }}</p>
                                    <p class="text-sm text-gray-500">{{ optional($enrollment->course)->credits ?? 0 }} credits</p>
                                    <p class="text-xs text-gray-400 mt-2">Assigned on {{ optional($enrollment->enrollment_date)->format('M d, Y') ?? 'N/A' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

            </div>
        </main>

    </div>
</div>

</x-app-layout>