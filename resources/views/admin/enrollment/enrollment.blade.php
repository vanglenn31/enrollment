<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
            @include('layouts.' . auth()->user()->role->role . '_side_bar')
        </aside>

        <!-- MAIN -->
        <div class="flex-1 lg:ml-64 flex flex-col">

            <!-- NAV -->
            <header class="sticky top-0 z-30 bg-white shadow-sm">
                @include('layouts.navigation')
            </header>

            <!-- CONTENT -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">

                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- PAGE HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Student Enrollment</h1>
                            <p class="text-sm text-gray-500 mt-1">Assign verified students to courses</p>
                        </div>
                    </div>

                    <!-- SUCCESS -->
                    @if(session('success'))
                        <div class="rounded-2xl bg-emerald-100 border border-emerald-200 text-emerald-700 p-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- CARD -->
                    <div class="bg-white rounded-2xl shadow-sm border p-4 sm:p-6 space-y-6">

                        <!-- TOP BAR -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Verified Students</h2>
                                <p class="text-sm text-gray-500">Students eligible for course enrollment</p>
                            </div>

                            <!-- SEARCH -->
                            <form method="GET" action="{{ route('admin.enrollment.enroll') }}" class="w-full sm:w-80">
                                <div class="flex items-center bg-gray-100 rounded-xl px-3 py-2">
                                    <input name="search" value="{{ $search ?? '' }}"
                                        class="bg-transparent w-full outline-none text-sm px-2"
                                        placeholder="Search students...">
                                    <button class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-sm">
                                        Search
                                    </button>
                                </div>
                            </form>

                        </div>

                        <!-- EMPTY STATE -->
                        @if($verifiedStudents->isEmpty())

                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="bg-gray-100 rounded-full p-5 mb-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-700">No verified students</h3>
                                <p class="text-sm text-gray-400 mt-1 max-w-xs">
                                    @if($search ?? '')
                                        No students matched your search.
                                    @else
                                        Verified students will appear here once approved in the Students page.
                                    @endif
                                </p>
                            </div>

                        @else

                            <!-- STUDENT CARDS -->
                            <div class="space-y-3">

                                @foreach($verifiedStudents as $student)

                                    @php
                                        $enrolled = $student->studentEnrollments->whereNotNull('course_id');
                                    @endphp

                                    <div class="border rounded-xl p-4 sm:p-5 hover:bg-gray-50 transition">

                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                                            <!-- STUDENT INFO -->
                                            <div>
                                                <h3 class="font-semibold text-gray-900">
                                                    {{ $student->profile->first_name }} {{ $student->profile->last_name }}
                                                </h3>
                                                <p class="text-sm text-gray-500 mt-0.5">
                                                    ID: <span class="font-medium text-gray-700">{{ $student->student_number }}</span>
                                                    &bull; {{ $student->programRelation->name ?? 'No Program' }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    {{ $enrolled->count() }} course(s) enrolled
                                                </p>
                                            </div>

                                            <!-- BADGE + ACTION -->
                                            <div class="flex items-center gap-2 shrink-0">

                                                @if($enrolled->isEmpty())
                                                    <span class="text-xs bg-orange-100 text-orange-700 px-2.5 py-1 rounded-full font-medium">
                                                        No courses
                                                    </span>
                                                @else
                                                    <span class="text-xs bg-green-100 text-green-700 px-2.5 py-1 rounded-full font-medium">
                                                        Enrolled
                                                    </span>
                                                @endif

                                                <a href="{{ route('admin.enrollment.assign', $student) }}"
                                                    class="bg-blue-600 text-white text-sm px-4 py-1.5 rounded-lg hover:bg-blue-700 transition">
                                                    Manage
                                                </a>

                                            </div>

                                        </div>

                                        <!-- COURSE TAGS -->
                                        @if($enrolled->isNotEmpty())
                                            <div class="mt-3 pt-3 border-t flex flex-wrap gap-2">
                                                @foreach($enrolled as $enrollment)
                                                    <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full">
                                                        {{ $enrollment->course->course_code }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif

                                    </div>

                                @endforeach

                            </div>

                            <!-- PAGINATION -->
                            <div>
                                {{ $verifiedStudents->links() }}
                            </div>

                        @endif

                    </div>

                </div>

            </main>
        </div>
    </div>
</x-app-layout>