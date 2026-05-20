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

                <div class="class="flex-1 w-full lg:ml-64 flex flex-col">
            
            <div class="w-full max-w-7xl space-y-6">

                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">
                            Welcome, {{ Auth::user()->profile->first_name ?? 'Student' }}!
                        </h1>

                        <p class="text-gray-700">
                            This is your student dashboard. Here you can track your enrollment progress and payment status.
                        </p>
                    </div>

                    <div>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $verified ? 'Verified' : 'Unverified' }}
                        </span>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <p class="text-sm text-gray-500">Program</p>

                        <p class="mt-2 text-xl font-semibold text-gray-900">
                            {{ $student?->programRelation->name ?? 'Not assigned' }}
                        </p>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <p class="text-sm text-gray-500">Courses assigned</p>

                        <p class="mt-2 text-xl font-semibold text-gray-900 text-end">
                            {{ $enrollments->count() }}
                        </p>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <p class="text-sm text-gray-500">Downpayment paid</p>

                        <p class="mt-2 text-xl font-semibold text-gray-900 text-end">
                            ₱{{ number_format($downpaymentPaid, 2) }}
                        </p>
                    </div>  

                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <p class="text-sm text-gray-500">Pending payments</p>

                        <p class="mt-2 text-xl font-semibold text-gray-900 text-end">
                            ₱{{ number_format($pendingAmount, 2) }}
                        </p>
                    </div>

                </div>

                <!-- Enrollment Flow -->
                <div class="bg-white rounded-xl shadow-sm p-6">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">
                                Enrollment flow
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                This is the current status of your application, downpayment, verification and enrollment.
                            </p>
                        </div>

                        <div class="rounded-full bg-blue-50 px-4 py-2 text-sm text-blue-700">
                            {{ $nextAction }}
                        </div>

                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div class="rounded-2xl border border-gray-200 p-4 bg-slate-50">
                            <p class="text-sm text-gray-500">Downpayment</p>

                            <p class="mt-2 font-semibold {{ $hasDownpayment ? 'text-green-700' : 'text-orange-600' }}">
                                {{ $hasDownpayment ? 'Paid' : 'Pending' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 p-4 bg-slate-50">
                            <p class="text-sm text-gray-500">Verification</p>

                            <p class="mt-2 font-semibold {{ $verified ? 'text-green-700' : 'text-orange-600' }}">
                                {{ $verified ? 'Verified' : 'Unverified' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 p-4 bg-slate-50">
                            <p class="text-sm text-gray-500">Admin enrollment</p>

                            <p class="mt-2 font-semibold {{ $enrollments->isNotEmpty() ? 'text-green-700' : 'text-orange-600' }}">
                                {{ $enrollments->isNotEmpty() ? 'Courses assigned' : 'Pending assignment' }}
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Courses -->
                <div class="bg-white rounded-xl shadow-sm p-6">

                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        Current Courses
                    </h2>

                    @if($enrollments->isEmpty())

                        <div class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-gray-500">

                            <p class="font-medium">
                                No course assignments yet.
                            </p>

                            <p class="mt-2">
                                Once your downpayment is complete and your record is verified, the admin will assign your courses.
                            </p>

                        </div>

                    @else

                        <div class="space-y-4">

                            @foreach($enrollments as $enrollment)

                                <div class="rounded-2xl border border-gray-200 p-4 sm:flex sm:items-center sm:justify-between gap-4">

                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ optional($enrollment->course)->course_code }}
                                            -
                                            {{ optional($enrollment->course)->course_name }}
                                        </p>

                                        <p class="text-sm text-gray-500">
                                            {{ optional($enrollment->course)->units ?? 0 }} units
                                        </p>
                                    </div>

                                    <p class="text-sm text-gray-500">
                                        Prof:
                                        {{ $enrollment->course?->professor?->profile?->last_name ?? 'TBA' }}
                                    </p>

                                    <div class="text-sm text-gray-600">
                                        Assigned on
                                        {{ $enrollment->enrollment_date?->format('M d, Y') ?? 'N/A' }}
                                    </div>

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