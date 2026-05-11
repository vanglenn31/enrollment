<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
            @include('layouts.' . auth()->user()->role->role . '_side_bar')
        </aside>

        <!-- MAIN AREA -->
        <div class="flex-1 lg:ml-64 flex flex-col">

            <!-- HEADER -->
            <header class="sticky top-0 z-30 bg-white shadow-sm">
                @include('layouts.navigation')
            </header>

            <!-- CONTENT -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto w-full space-y-6">

                    <!-- BACK + TITLE -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <a href="{{ route('admin.course.course') }}"
                               class="text-sm text-blue-600 hover:text-blue-800 mb-1 inline-block">
                                ← Back to Courses
                            </a>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                                {{ $course->course_name }}
                            </h1>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $course->course_code }}
                                @if($course->program)
                                    &nbsp;·&nbsp; {{ $course->program->name }}
                                @endif
                            </p>
                        </div>

                        <a href="{{ route('admin.course.edit', $course) }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-gray-800 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-gray-900 transition self-start sm:self-auto">
                            Edit Course
                        </a>
                    </div>

                    <!-- SUCCESS / ERROR -->
                    @if(session('success'))
                        <div class="rounded-xl bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- COURSE INFO CARDS -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">

                        <div class="bg-white rounded-2xl shadow-sm border p-4 text-center">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Slots</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $course->slots }}</p>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border p-4 text-center">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Enrolled</p>
                            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $enrolledCount }}</p>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border p-4 text-center">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Available</p>
                            <p class="text-2xl font-bold mt-1 {{ $availableSlots === 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $availableSlots }}
                            </p>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border p-4 text-center">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Schedule</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1">
                                {{ $course->schedule_type ?? '—' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                @if($course->start_time && $course->end_time)
                                    {{ \Carbon\Carbon::parse($course->start_time)->format('g:i A') }}
                                    –
                                    {{ \Carbon\Carbon::parse($course->end_time)->format('g:i A') }}
                                @else
                                    No time set
                                @endif
                            </p>
                        </div>

                    </div>

                    <!-- ENROLLED STUDENTS TABLE -->
                    <div class="bg-white rounded-2xl shadow-sm border p-4 sm:p-6">

                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Enrolled Students</h2>

                        @if($course->enrolledCourses->isEmpty())
                            <div class="text-center py-12 text-gray-400">
                                <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                                </svg>
                                <p class="text-sm">No students enrolled in this course yet.</p>
                            </div>
                        @else

                            <!-- DESKTOP TABLE -->
                            <div class="hidden md:block overflow-x-auto rounded-xl border">
                                <table class="min-w-full text-sm text-gray-700">
                                    <thead class="bg-gray-50 text-gray-600">
                                        <tr>
                                            <th class="py-3 px-4 text-left">#</th>
                                            <th class="py-3 px-4 text-left">Student</th>
                                            <th class="py-3 px-4 text-left">Student ID</th>
                                            <th class="py-3 px-4 text-left">Professor</th>
                                            <th class="py-3 px-4 text-left">Room</th>
                                            <th class="py-3 px-4 text-left">Price</th>
                                            <th class="py-3 px-4 text-left">Grade</th>
                                            <th class="py-3 px-4 text-left">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($course->enrolledCourses as $index => $enrolled)
                                            @php
                                                $student = optional(optional(optional($enrolled->studentEnrollment)->student)->profile);
                                                $studentModel = optional(optional($enrolled->studentEnrollment)->student);
                                            @endphp
                                            <tr class="hover:bg-gray-50">
                                                <td class="py-3 px-4 text-gray-400">{{ $index + 1 }}</td>

                                                <td class="py-3 px-4 font-medium">
                                                    {{ $student->first_name ?? '—' }}
                                                    {{ $student->last_name ?? '' }}
                                                </td>

                                                <td class="py-3 px-4 text-gray-500">
                                                    {{ $studentModel->student_number ?? '—' }}
                                                </td>

                                                <td class="py-3 px-4">
                                                    @if($enrolled->professor && $enrolled->professor->profile)
                                                        {{ $enrolled->professor->profile->first_name }}
                                                        {{ $enrolled->professor->profile->last_name }}
                                                    @else
                                                        <span class="text-gray-400">—</span>
                                                    @endif
                                                </td>

                                                <td class="py-3 px-4">
                                                    {{ optional($enrolled->room)->room_name ?? '—' }}
                                                </td>

                                                <td class="py-3 px-4">
                                                    ₱{{ number_format($enrolled->course_price, 2) }}
                                                </td>

                                                <!-- GRADE -->
                                                <td class="py-3 px-4">
                                                    @if($enrolled->grade !== null)
                                                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                                                            {{ $enrolled->grade >= 75
                                                                ? 'bg-green-100 text-green-700'
                                                                : 'bg-red-100 text-red-700' }}">
                                                            {{ number_format($enrolled->grade, 2) }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400 text-xs">No grade yet</span>
                                                    @endif
                                                </td>

                                                <!-- GRADE FORM -->
                                                <td class="py-3 px-4">
                                                    <form method="POST"
                                                          action="{{ route('admin.enrolled-course.grade', $enrolled) }}"
                                                          class="flex items-center gap-2">
                                                        @csrf
                                                        @method('PATCH')

                                                        <input type="number"
                                                               name="grade"
                                                               step="0.01"
                                                               min="0"
                                                               max="100"
                                                               value="{{ $enrolled->grade }}"
                                                               placeholder="0–100"
                                                               class="w-24 rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">

                                                        <button type="submit"
                                                                class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded-lg transition">
                                                            Save
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- MOBILE CARDS -->
                            <div class="md:hidden space-y-4">
                                @foreach($course->enrolledCourses as $index => $enrolled)
                                    @php
                                        $student = optional(optional(optional($enrolled->studentEnrollment)->student)->profile);
                                        $studentModel = optional(optional($enrolled->studentEnrollment)->student);
                                    @endphp
                                    <div class="border rounded-xl p-4 shadow-sm space-y-3">

                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-semibold text-gray-900">
                                                    {{ $student->first_name ?? '—' }}
                                                    {{ $student->last_name ?? '' }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $studentModel->student_id ?? 'No ID' }}
                                                </p>
                                            </div>
                                            @if($enrolled->grade !== null)
                                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                                                    {{ $enrolled->grade >= 75
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-red-100 text-red-700' }}">
                                                    {{ number_format($enrolled->grade, 2) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">No grade</span>
                                            @endif
                                        </div>

                                        <div class="text-sm text-gray-600 space-y-1">
                                            <p><strong>Price:</strong> ₱{{ number_format($enrolled->course_price, 2) }}</p>
                                            <p><strong>Room:</strong> {{ optional($enrolled->room)->room_name ?? '—' }}</p>
                                        </div>

                                        <form method="POST"
                                              action="{{ route('admin.enrolled-course.grade', $enrolled) }}"
                                              class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')

                                            <input type="number"
                                                   name="grade"
                                                   step="0.01"
                                                   min="0"
                                                   max="100"
                                                   value="{{ $enrolled->grade }}"
                                                   placeholder="Enter grade (0–100)"
                                                   class="flex-1 rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">

                                            <button type="submit"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-4 py-2 rounded-lg transition">
                                                Save
                                            </button>
                                        </form>

                                    </div>
                                @endforeach
                            </div>

                        @endif
                    </div>

                </div>
            </main>
        </div>
    </div>
</x-app-layout>
