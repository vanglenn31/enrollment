<x-app-layout>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    .enroll-wrap * { font-family: 'DM Sans', sans-serif; }
    .mono { font-family: 'DM Mono', monospace; }

    .course-card {
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .course-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transform: translateY(-1px);
    }

    .badge-schedule {
        letter-spacing: 0.05em;
    }

    .btn-enlist {
        transition: background 0.2s ease, transform 0.1s ease;
    }
    .btn-enlist:active {
        transform: scale(0.98);
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        display: inline-block;
        box-shadow: 0 0 0 3px rgba(34,197,94,0.2);
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, #4f46e5 0%, #818cf8 60%, transparent 100%);
        border-radius: 2px;
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

<div class="enroll-wrap flex min-h-screen bg-gray-50">

    <!-- SIDEBAR -->
    <aside class="hidden lg:block fixed inset-y-0 left-0 w-64 z-30 w-full">
        @include('layouts.student_side_bar')
    </aside>

    <!-- MAIN -->
    <div class="flex-1 w-full lg:ml-64 flex flex-col">

        <!-- NAV -->
        <header class="sticky top-0 z-50">
            @include('layouts.navigation')
        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-3xl mx-auto space-y-6">

                <!-- PAGE HEADER -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                        <div class="flex items-start gap-4">
                            <!-- Avatar -->
                            <div class="shrink-0 w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($student?->profile->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($student?->profile->last_name ?? '', 0, 1)) }}
                            </div>
                            <div>
                                <h1 class="text-xl font-semibold text-gray-900">
                                    {{ $student?->profile->first_name }} {{ $student?->profile->last_name }}
                                </h1>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                                    <span class="mono text-xs text-gray-500">{{ $student?->student_number }}</span>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-xs text-gray-500">{{ $student?->programRelation->name ?? 'No program' }}</span>
                                    <span class="text-gray-300">•</span>
                                    @if($enrollments->isNotEmpty())
                                        <span class="flex items-center gap-1.5 text-xs text-green-600 font-medium">
                                            <span class="status-dot"></span> Enrolled
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1.5 text-xs text-orange-500 font-medium">
                                            Awaiting Assignment
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- <a href="{{ route('student.dashboard') }}"
                           class="shrink-0 inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back
                        </a> -->

                    </div>
                </div>

                <!-- SUCCESS FLASH -->
                @if(session('success'))
                    <div class="flex items-center gap-3 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-green-700 text-sm">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center gap-3 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-red-700 text-sm">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 012 0v4a1 1 0 01-2 0V9zm1 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- STATUS BANNER -->
                @if (!$hasDownpayment)
                    <div class="flex items-start gap-4 rounded-2xl border border-orange-200 bg-orange-50 p-5">
                        <div class="shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-orange-800">Downpayment Required</p>
                            <p class="mt-1 text-xs text-orange-700">
                                You have not yet paid your downpayment. Course enlistment is locked until the downpayment is confirmed by the admin.
                            </p>
                        </div>
                    </div>

                @elseif (!$student?->is_verified)
                    <div class="flex items-start gap-4 rounded-2xl border border-yellow-200 bg-yellow-50 p-5">
                        <div class="shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 102 0V6zm-1 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-yellow-800">Pending Admin Verification</p>
                            <p class="mt-1 text-xs text-yellow-700">
                                Your account is awaiting admin verification. Course enlistment will be available once you are verified.
                            </p>
                        </div>
                    </div>

                @else
                    <div class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-3 text-sm text-green-700">
                        <svg class="w-4 h-4 shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                  clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </div>
                @endif

                <!-- ─────────────────────────────────────────── -->
                <!-- SECTION 1 — MY ENROLLED COURSES             -->
                <!-- ─────────────────────────────────────────── -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                    <div class="px-5 sm:px-6 pt-5 pb-4">
                        <div class="flex items-center justify-between mb-1">
                            <h2 class="text-base font-semibold text-gray-900">My Courses</h2>
                            @php
                                $enrolledCount = $enrollments->count();
                                $totalUnits    = $enrollments->sum(fn($e) => optional($e->course)->units ?? 0);
                            @endphp
                            <div class="flex items-center gap-3">
                                <span class="mono text-xs text-gray-400">{{ $totalUnits }} units</span>
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold">
                                    {{ $enrolledCount }}
                                </span>
                            </div>
                        </div>
                        <div class="section-divider mt-3"></div>
                    </div>

                    @if($enrollments->isEmpty())

                        <div class="empty-state mx-5 mb-5 rounded-xl border border-dashed border-gray-200 p-8 text-center">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                            </svg>
                            <p class="text-sm text-gray-400">No courses enlisted yet.</p>
                        </div>

                    @else

                        <div class="px-5 sm:px-6 pb-5 space-y-3">

                            @foreach($enrollments as $enrollment)

                                <div class="course-card rounded-xl border border-gray-100 bg-gray-50 p-4">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">

                                        <!-- Course Info -->
                                        <div class="flex-1 min-w-0">

                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="mono text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md">
                                                    {{ optional($enrollment->course)->course_code }}
                                                </span>
                                                <span class="text-sm font-medium text-gray-900 truncate">
                                                    {{ optional($enrollment->course)->course_name }}
                                                </span>
                                            </div>

                                            <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1">

                                                @if(optional($enrollment->course)->schedule_type)
                                                    <span class="badge-schedule inline-flex items-center gap-1 text-xs font-semibold text-violet-700 bg-violet-50 border border-violet-100 px-2 py-0.5 rounded-md">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        {{ $enrollment->course->schedule_type }}
                                                    </span>
                                                @endif

                                                @if(optional($enrollment->course)->start_time && optional($enrollment->course)->end_time)
                                                    <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
                                                        </svg>
                                                        <span class="mono">
                                                            {{ \Carbon\Carbon::parse($enrollment->course->start_time)->format('h:i A') }}
                                                            –
                                                            {{ \Carbon\Carbon::parse($enrollment->course->end_time)->format('h:i A') }}
                                                        </span>
                                                    </span>
                                                @endif

                                                <span class="text-xs text-gray-400">
                                                    {{ optional($enrollment->course)->units }} units
                                                </span>

                                                <span class="text-xs text-gray-400">
                                                    ₱{{ number_format(optional($enrollment->course)->course_price ?? 0, 2) }}
                                                </span>

                                            </div>

                                        </div>

                                        <!-- Status Badge -->
                                        <div class="shrink-0">
                                            @php $status = $enrollment->status ?? 'enrolled'; @endphp
                                            <span class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-semibold
                                                @if($status === 'enrolled')    bg-green-100  border border-green-200  text-green-700
                                                @elseif($status === 'pending') bg-yellow-100 border border-yellow-200 text-yellow-700
                                                @elseif($status === 'dropped') bg-red-100    border border-red-200    text-red-500
                                                @else                          bg-gray-100   border border-gray-200   text-gray-600
                                                @endif">
                                                @if($status === 'enrolled')
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                                {{ ucfirst($status) }}
                                            </span>
                                        </div>

                                    </div>
                                </div>

                            @endforeach

                        </div>

                    @endif

                </div>

                <!-- ─────────────────────────────────────────── -->
                <!-- SECTION 2 — ENLIST A COURSE                 -->
                <!-- ─────────────────────────────────────────── -->

                @php
                    /* ── Enrolled slots for conflict detection ── */
                    $enrolledSlots = $enrollments->map(fn($e) => [
                        'type'  => optional($e->course)->schedule_type,
                        'start' => optional($e->course)->start_time,
                        'end'   => optional($e->course)->end_time,
                        'name'  => optional($e->course)->course_name,
                    ]);

                    /* ── Schedule conflict check ── */
                    $hasConflict = function ($course) use ($enrolledSlots) {
                        foreach ($enrolledSlots as $slot) {
                            if ($slot['type'] !== $course->schedule_type) continue;
                            if ($slot['start'] < $course->end_time && $slot['end'] > $course->start_time) {
                                return true;
                            }
                        }
                        return false;
                    };

                    /* ── Already enlisted check ── */
                    $hasDuplicate = function ($course) use ($enrolledSlots) {
                        return $enrolledSlots->contains(fn($slot) => $slot['name'] === $course->course_name);
                    };

                    /* ── Group available courses by name ── */
                    $grouped = $availableCourses->groupBy('course_name');
                @endphp

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                    <div class="px-5 sm:px-6 pt-5 pb-4">
                        <h2 class="text-base font-semibold text-gray-900 mb-1">Enlist a Course</h2>
                        <div class="section-divider mt-3"></div>
                    </div>

                    <div class="px-5 sm:px-6 pb-6">

                        @if(!$canSelfEnroll)

                            <div class="empty-state rounded-xl border border-dashed border-gray-200 p-8 text-center">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                                </svg>
                                <p class="text-sm text-gray-400">Enlistment is currently unavailable.</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $message }}</p>
                            </div>

                        @elseif($availableCourses->isEmpty())

                            <div class="empty-state rounded-xl border border-dashed border-gray-200 p-8 text-center">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-gray-400">All available courses have been enlisted.</p>
                            </div>

                        @else

                            {{-- Accordion list — one row per unique course name --}}
                            <div class="rounded-xl border border-gray-100 overflow-hidden divide-y divide-gray-100 mb-5" id="course-accordion">

                                @foreach($grouped as $courseName => $variants)

                                    @php
                                        $groupId     = 'cg-' . Str::slug($courseName);
                                        $allConflict = $variants->every(fn($c) => $hasConflict($c));
                                    @endphp

                                    {{-- Accordion header --}}
                                    <div>
                                        <button type="button"
                                            onclick="toggleGroup('{{ $groupId }}')"
                                            class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-gray-50 transition-colors group">

                                            <span class="text-sm font-medium {{ $allConflict ? 'line-through text-gray-400' : 'text-gray-800' }}">
                                                {{ $courseName }}
                                                @if($allConflict)
                                                    <span class="ml-2 text-xs font-normal text-red-400" style="text-decoration:none;">⚠ conflict</span>
                                                @endif
                                            </span>

                                            <div class="flex items-center gap-2">
                                                @if($variants->count() > 1)
                                                    <span class="text-xs text-gray-400">{{ $variants->count() }} sections</span>
                                                @endif
                                                <svg id="{{ $groupId }}-chevron"
                                                     class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </div>

                                        </button>

                                        {{-- Expanded panel --}}
                                        <div id="{{ $groupId }}" class="hidden border-t border-gray-100 bg-gray-50">

                                            <div class="px-4 py-3 space-y-3">

                                                @foreach($variants as $course)
                                                    @php
                                                        $conflict = $hasConflict($course);
                                                        $slots    = $course->slots ?? 30;
                                                        $enrolled = $course->studentEnrollments()->count();
                                                        $left     = max(0, $slots - $enrolled);
                                                    @endphp

                                                    <div class="rounded-xl border {{ $conflict ? 'border-red-100 bg-red-50' : 'border-gray-200 bg-white' }} p-3">

                                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                                                            <div class="flex-1 min-w-0">

                                                                <div class="flex items-center gap-2 flex-wrap">
                                                                    <span class="mono text-xs font-medium {{ $conflict ? 'text-red-400 bg-red-100' : 'text-gray-500 bg-gray-100' }} px-2 py-0.5 rounded">
                                                                        {{ $course->course_code }}
                                                                    </span>
                                                                    <span class="text-sm font-medium {{ $conflict ? 'line-through text-gray-400' : 'text-gray-800' }}">
                                                                        {{ $course->course_name }}
                                                                    </span>
                                                                    @if($conflict)
                                                                        <span class="text-xs text-red-500 font-medium">Schedule conflict</span>
                                                                    @endif
                                                                </div>

                                                                <div class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1">

                                                                    @if($course->schedule_type)
                                                                        <span class="badge-schedule inline-flex items-center gap-1 text-xs font-semibold
                                                                            {{ $conflict ? 'text-red-400' : 'text-violet-700 bg-violet-50 border border-violet-100' }}
                                                                            px-2 py-0.5 rounded-md">
                                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                            </svg>
                                                                            {{ $course->schedule_type }}
                                                                        </span>
                                                                    @endif

                                                                    @if($course->start_time && $course->end_time)
                                                                        <span class="inline-flex items-center gap-1 text-xs {{ $conflict ? 'text-red-400' : 'text-gray-500' }}">
                                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
                                                                            </svg>
                                                                            <span class="mono">
                                                                                {{ \Carbon\Carbon::parse($course->start_time)->format('h:i A') }}
                                                                                –
                                                                                {{ \Carbon\Carbon::parse($course->end_time)->format('h:i A') }}
                                                                            </span>
                                                                        </span>
                                                                    @endif

                                                                    <span class="text-xs {{ $conflict ? 'text-red-400' : 'text-gray-400' }}">
                                                                        {{ $course->units }} units
                                                                    </span>

                                                                    <span class="text-xs {{ $conflict ? 'text-red-400' : 'text-gray-400' }}">
                                                                        ₱{{ number_format($course->course_price ?? 0, 2) }}
                                                                    </span>

                                                                    <span class="text-xs {{ $left <= 3 ? 'text-red-400 font-medium' : 'text-gray-400' }}">
                                                                        {{ $left }} slot{{ $left === 1 ? '' : 's' }} left
                                                                    </span>

                                                                </div>

                                                            </div>

                                                            {{-- Action button --}}
                                                            @if($hasDuplicate($course))

                                                                <span class="shrink-0 inline-flex items-center gap-1 rounded-lg bg-yellow-100 border border-yellow-200 px-4 py-2 text-xs font-semibold text-yellow-600 cursor-not-allowed">
                                                                    Already Enlisted
                                                                </span>

                                                            @elseif($left <= 0)

                                                                <span class="shrink-0 inline-flex items-center gap-1 rounded-lg bg-gray-100 border border-gray-200 px-4 py-2 text-xs font-semibold text-gray-400 cursor-not-allowed">
                                                                    Full
                                                                </span>

                                                            @elseif(!$conflict)

                                                                <form action="{{ route('student.course.enlist') }}" method="POST" class="shrink-0">
                                                                    @csrf
                                                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                                                    <button type="submit"
                                                                        class="btn-enlist inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 px-4 py-2 text-xs font-semibold text-white transition-colors">
                                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                                                        </svg>
                                                                        Enlist
                                                                    </button>
                                                                </form>

                                                            @else

                                                                <span class="shrink-0 inline-flex items-center gap-1 rounded-lg bg-red-100 border border-red-200 px-4 py-2 text-xs font-semibold text-red-400 cursor-not-allowed">
                                                                    Conflict
                                                                </span>

                                                            @endif

                                                        </div>

                                                    </div>

                                                @endforeach

                                            </div>

                                            @error('course_id')
                                                <p class="px-4 pb-3 text-red-500 text-xs flex items-center gap-1">
                                                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror

                                        </div>
                                    </div>

                                @endforeach

                            </div>

                            <p class="text-xs text-gray-400 text-center mt-3">
                                Click a course name to see available sections and enlist.
                            </p>

                        @endif

                    </div>

                </div>

            </div>
        </main>
    </div>
</div>

<script>
    function toggleGroup(id) {
        const panel   = document.getElementById(id);
        const chevron = document.getElementById(id + '-chevron');
        const isOpen  = !panel.classList.contains('hidden');

        // Close all
        document.querySelectorAll('#course-accordion [id^="cg-"]').forEach(el => {
            if (!el.id.endsWith('-chevron')) el.classList.add('hidden');
        });
        document.querySelectorAll('#course-accordion [id$="-chevron"]').forEach(el => {
            el.style.transform = '';
        });

        // Toggle clicked
        if (!isOpen) {
            panel.classList.remove('hidden');
            chevron.style.transform = 'rotate(180deg)';
        }
    }
</script>

</x-app-layout>