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

                <div class="col-span-4 col-start-2 p-6 z-10 md:z-30 w-full">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">My Courses</h1>
                        <p class="text-sm text-gray-500 mt-1">Your complete academic record — all enrolled courses and grades across every term.</p>
                    </div>
                    <div class="flex items-center gap-2 text-sm flex-wrap">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-slate-700 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            {{ $totalUnits }} total units
                        </span>
                        <!-- <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1.5 text-blue-700 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            @if($gwa !== null)
                                GWA: {{ number_format($gwa, 2) }}
                            @else
                                GWA: N/A
                            @endif
                        </span> -->
                    </div>
                </div>

                {{-- Summary Cards --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Terms Completed</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 text-end">{{ $termsCompleted }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Courses Taken</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 text-end">{{ $totalCourses }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Units Earned</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 text-end">{{ $totalUnits }}</p>
                    </div>
                    <!-- <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Current GWA</p>
                        <p class="mt-2 text-2xl font-bold {{ $gwa !== null && $gwa <= 3.0 ? 'text-green-600' : 'text-gray-900' }}">
                            {{ $gwa !== null ? number_format($gwa, 2) : '—' }}
                        </p>
                    </div> -->
                </div>

                {{-- Courses Grouped by Term --}}
                @if($enrollmentsByTerm->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-300 p-12 text-center">
                        <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <p class="font-semibold text-gray-700">No courses yet</p>
                        <p class="mt-1 text-sm text-gray-500">Your enrolled courses will appear here once the admin assigns them.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($enrollmentsByTerm as $termGroup)
                            @php
                                $term       = $termGroup['term'];
                                $courses    = $termGroup['courses'];
                                $isActive   = $term->status === 'active';
                                $termUnits  = $courses->sum(fn($ec) => optional($ec->course)->units ?? 0);
                                $graded     = $courses->filter(fn($ec) => $ec->grade !== null);
                                $termGwa    = $graded->count()
                                    ? round($graded->avg('grade'), 2)
                                    : null;
                            @endphp

                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                {{-- Term Header --}}
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-6 py-4 border-b border-gray-100 bg-slate-50">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <h2 class="font-bold text-gray-900 text-base">{{ $term->label }}</h2>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                {{ $term->start_date?->format('M d, Y') ?? 'TBD' }}
                                                @if($term->end_date) — {{ $term->end_date->format('M d, Y') }} @endif
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $term->statusBadgeClass() }}">
                                            {{ ucfirst($term->status) }}
                                        </span>
                                        @if($isActive)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse inline-block"></span>
                                                Current Term
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4 text-sm">
                                        <span class="text-gray-500">{{ $courses->count() }} course{{ $courses->count() !== 1 ? 's' : '' }} · {{ $termUnits }} units</span>
                                        @if($termGwa !== null)
                                            <span class="font-semibold text-gray-800">Term GWA: {{ number_format($termGwa, 2) }}</span>
                                        @else
                                            <span class="text-gray-400 text-xs">Grades pending</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Courses Table --}}
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wide border-b border-gray-100">
                                                <th class="py-3 px-6 text-left">Course</th>
                                                <th class="py-3 px-4 text-left">Professor</th>
                                                <th class="py-3 px-4 text-left">Room</th>
                                                <th class="py-3 px-4 text-center">Units</th>
                                                <th class="py-3 px-4 text-center">Price</th>
                                                <th class="py-3 px-6 text-center">Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @foreach($courses as $enrolledCourse)
                                                <tr class="hover:bg-slate-50 transition-colors">
                                                    <td class="py-4 px-6">
                                                        <p class="font-semibold text-gray-900">{{ optional($enrolledCourse->course)->course_code ?? '—' }}</p>
                                                        <p class="text-xs text-gray-500 mt-0.5">{{ optional($enrolledCourse->course)->course_name ?? 'Unknown course' }}</p>
                                                    </td>
                                                    <td class="py-4 px-4 text-gray-600">
                                                        {{ optional(optional($enrolledCourse->professor)->profile)->last_name ?? 'TBA' }}
                                                    </td>
                                                    <td class="py-4 px-4 text-gray-600">
                                                        {{ optional($enrolledCourse->room)->room_name ?? 'TBA' }}
                                                    </td>
                                                    <td class="py-4 px-4 text-center text-gray-700">
                                                        {{ optional($enrolledCourse->course)->units ?? '—' }}
                                                    </td>
                                                    <td class="py-4 px-4 text-center text-gray-700">
                                                        ₱{{ number_format($enrolledCourse->course_price ?? 0, 2) }}
                                                    </td>
                                                    <td class="py-4 px-6 text-center">
                                                        @if($enrolledCourse->grade !== null)
                                                            @php
                                                                $g = (float) $enrolledCourse->grade;
                                                                $gradeClass = $g <= 1.5 ? 'bg-green-100 text-green-700'
                                                                    : ($g <= 2.5 ? 'bg-blue-50 text-blue-700'
                                                                    : ($g <= 3.0 ? 'bg-yellow-50 text-yellow-700'
                                                                    : 'bg-red-50 text-red-700'));
                                                            @endphp
                                                            <span class="inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-bold {{ $gradeClass }}">
                                                                {{ number_format($g, 2) }}
                                                            </span>
                                                        @elseif($isActive)
                                                            <span class="inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-medium bg-slate-100 text-slate-500">
                                                                In progress
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-medium bg-orange-50 text-orange-500">
                                                                Pending
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>


            </div>
        </main>

    </div>
</div>

</x-app-layout>
