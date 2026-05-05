<x-app-layout>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    .assign-wrap * { font-family: 'DM Sans', sans-serif; }
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

    .available-row {
        transition: background 0.15s ease;
    }
    .available-row:hover {
        background: #f0f4ff;
    }

    select:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .btn-assign {
        transition: background 0.2s ease, transform 0.1s ease;
    }
    .btn-assign:active {
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

<div class="assign-wrap flex min-h-screen bg-gray-50">

    <!-- SIDEBAR -->
    <aside class="hidden lg:block fixed inset-y-0 left-0 w-64 z-30">
        @include('layouts.admin_side_bar')
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
                                {{ strtoupper(substr($student->profile->first_name, 0, 1)) }}{{ strtoupper(substr($student->profile->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <h1 class="text-xl font-semibold text-gray-900">
                                    {{ $student->profile->first_name }} {{ $student->profile->last_name }}
                                </h1>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                                    <span class="mono text-xs text-gray-500">{{ $student->student_number }}</span>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-xs text-gray-500">{{ $student->programRelation->name }}</span>
                                    <span class="text-gray-300">•</span>
                                    <span class="flex items-center gap-1.5 text-xs text-green-600 font-medium">
                                        <span class="status-dot"></span> Enrolled
                                    </span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('admin.enrollment') }}"
                           class="shrink-0 inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back
                        </a>

                    </div>
                </div>

                <!-- SUCCESS -->
                @if(session('success'))
                    <div class="flex items-center gap-3 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-green-700 text-sm">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- ─────────────────────────────────────────── -->
                <!-- SECTION 1 — ENROLLED COURSES                -->
                <!-- ─────────────────────────────────────────── -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                    <div class="px-5 sm:px-6 pt-5 pb-4">
                        <div class="flex items-center justify-between mb-1">
                            <h2 class="text-base font-semibold text-gray-900">Enrolled Courses</h2>
                            @php
                                $enrolledCount = $student->studentEnrollments->whereNotNull('course_id')->count();
                                $totalUnits    = $student->studentEnrollments->whereNotNull('course_id')->sum(fn($e) => $e->course->units ?? 0);
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

                    @if($student->studentEnrollments->whereNotNull('course_id')->isEmpty())

                        <div class="empty-state mx-5 mb-5 rounded-xl border border-dashed border-gray-200 p-8 text-center">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                            </svg>
                            <p class="text-sm text-gray-400">No courses assigned yet.</p>
                        </div>

                    @else

                        <div class="px-5 sm:px-6 pb-5 space-y-3">

                            @foreach($student->studentEnrollments->whereNotNull('course_id') as $enrollment)

                                <div class="course-card rounded-xl border border-gray-100 bg-gray-50 p-4">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">

                                        <!-- Course Info -->
                                        <div class="flex-1 min-w-0">

                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="mono text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md">
                                                    {{ $enrollment->course->course_code }}
                                                </span>
                                                <span class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $enrollment->course->course_name }}
                                                </span>
                                            </div>

                                            <!-- Time & Schedule Row -->
                                            <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1">

                                                @if($enrollment->course->schedule_type)
                                                    <span class="badge-schedule inline-flex items-center gap-1 text-xs font-semibold text-violet-700 bg-violet-50 border border-violet-100 px-2 py-0.5 rounded-md">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        {{ $enrollment->course->schedule_type }}
                                                    </span>
                                                @endif

                                                @if($enrollment->course->start_time && $enrollment->course->end_time)
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
                                                    {{ $enrollment->course->units }} units
                                                </span>

                                                <span class="text-xs text-gray-400">
                                                    ₱{{ number_format($enrollment->course->course_price ?? 0, 2) }}
                                                </span>

                                            </div>

                                        </div>

                                        <!-- Actions -->
                                        <div class="flex items-center gap-2 shrink-0">

                                            <a href="{{ route('admin.enrollment.edit', $enrollment) }}"
                                               class="inline-flex items-center gap-1 rounded-lg bg-white border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:border-indigo-300 hover:text-indigo-700 transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z"/>
                                                </svg>
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.enrollment.remove', $enrollment) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Remove this course from the student\'s enrollment?')"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-white border border-gray-200 px-3 py-1.5 text-xs font-semibold text-red-500 hover:border-red-300 hover:bg-red-50 transition-colors">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Remove
                                                </button>
                                            </form>

                                        </div>

                                    </div>
                                </div>

                            @endforeach

                        </div>

                    @endif

                </div>

                <!-- ─────────────────────────────────────────── -->
                <!-- SECTION 2 — ADD COURSE                      -->
                <!-- ─────────────────────────────────────────── -->

                @php
                    /* ── Conflict detection ─────────────────────────────
                       Build a set of schedule_type+time ranges the student
                       is already enrolled in so we can flag new courses.   */
                    $enrolledSlots = $student->studentEnrollments
                        ->whereNotNull('course_id')
                        ->map(fn($e) => [
                            'type'  => $e->course->schedule_type,
                            'start' => $e->course->start_time,
                            'end'   => $e->course->end_time,
                        ]);

                    $hasConflict = function($course) use ($enrolledSlots) {
                        foreach ($enrolledSlots as $slot) {
                            if ($slot['type'] !== $course->schedule_type) continue;
                            // Overlap: A.start < B.end AND A.end > B.start
                            if ($slot['start'] < $course->end_time &&
                                $slot['end']   > $course->start_time) {
                                return true;
                            }
                        }
                        return false;
                    };

                    /* ── Group by course_name ───────────────────────── */
                    $grouped = $availableCourses->groupBy('course_name');
                @endphp

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                    <div class="px-5 sm:px-6 pt-5 pb-4">
                        <h2 class="text-base font-semibold text-gray-900 mb-1">Add a Course</h2>
                        <div class="section-divider mt-3"></div>
                    </div>

                    <div class="px-5 sm:px-6 pb-6">

                        @if($availableCourses->isEmpty())

                            <div class="empty-state rounded-xl border border-dashed border-gray-200 p-8 text-center">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-gray-400">All courses for this program have been assigned.</p>
                            </div>

                        @else

                            {{-- ── Accordion list (one row per unique course name) ── --}}
                            <div class="rounded-xl border border-gray-100 overflow-hidden divide-y divide-gray-100 mb-5" id="course-accordion">

                                @foreach($grouped as $courseName => $variants)

                                    @php
                                        $groupId   = 'cg-' . Str::slug($courseName);
                                        /* A group is "all conflicted" only if every variant conflicts */
                                        $allConflict = $variants->every(fn($c) => $hasConflict($c));
                                    @endphp

                                    {{-- ── Accordion header (just the name) ── --}}
                                    <div>
                                        <button type="button"
                                            onclick="toggleGroup('{{ $groupId }}')"
                                            class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-gray-50 transition-colors group">

                                            <span class="text-sm font-medium
                                                {{ $allConflict ? 'line-through text-gray-400' : 'text-gray-800' }}">
                                                {{ $courseName }}
                                                @if($allConflict)
                                                    <span class="ml-2 text-xs font-normal no-underline text-red-400 not-italic"
                                                          style="text-decoration:none;">⚠ conflict</span>
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

                                        {{-- ── Expanded panel: variants with full details + form ── --}}
                                        <div id="{{ $groupId }}" class="hidden border-t border-gray-100 bg-gray-50">

                                            <div class="px-4 py-3 space-y-3">

                                                @foreach($variants as $course)
                                                    @php $conflict = $hasConflict($course); @endphp

                                                    <div class="rounded-xl border {{ $conflict ? 'border-red-100 bg-red-50' : 'border-gray-200 bg-white' }} p-3">

                                                        {{-- Course detail row --}}
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

                                                                </div>

                                                            </div>

                                                            {{-- Assign button (disabled if conflict) --}}
                                                            @if(!$conflict)
                                                                <form action="{{ route('admin.enrollment.store', $student) }}" method="POST" class="shrink-0">
                                                                    @csrf
                                                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                                                    <button type="submit"
                                                                        class="btn-assign inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 px-4 py-2 text-xs font-semibold text-white transition-colors">
                                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                                                        </svg>
                                                                        Assign
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="shrink-0 inline-flex items-center gap-1 rounded-lg bg-red-100 border border-red-200 px-4 py-2 text-xs font-semibold text-red-400 cursor-not-allowed">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                                    </svg>
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

                            <p class="text-xs text-gray-400 text-center">
                                Click a course name to see available sections and assign.
                            </p>

                        @endif

                    </div>

                </div>

                <script>
                    function toggleGroup(id) {
                        const panel   = document.getElementById(id);
                        const chevron = document.getElementById(id + '-chevron');
                        const isOpen  = !panel.classList.contains('hidden');

                        // Close all
                        document.querySelectorAll('#course-accordion [id^="cg-"]').forEach(el => {
                            if (!el.id.endsWith('-chevron')) {
                                el.classList.add('hidden');
                            }
                        });
                        document.querySelectorAll('#course-accordion [id$="-chevron"]').forEach(el => {
                            el.style.transform = '';
                        });

                        // Open clicked (toggle)
                        if (!isOpen) {
                            panel.classList.remove('hidden');
                            chevron.style.transform = 'rotate(180deg)';
                        }
                    }
                </script>

            </div>
        </main>
    </div>
</div>

</x-app-layout>