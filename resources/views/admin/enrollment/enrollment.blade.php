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

                <!-- HEADER -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                            Student Enrollment
                        </h1>
                        <p class="text-sm text-gray-500">
                            Assign students to courses
                        </p>
                    </div>

                    <!-- SEARCH -->
                    <form method="GET" action="{{ route('admin.enrollment.enroll') }}" class="w-full sm:w-80">
                        <div class="flex bg-white rounded-xl border px-3 py-2">
                            <input name="search" value="{{ $search ?? '' }}"
                                class="bg-transparent w-full outline-none text-sm"
                                placeholder="Search students...">

                            <button class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-sm">
                                Search
                            </button>
                        </div>
                    </form>
                </div>

                <!-- EMPTY STATE -->
                @if($verifiedStudents->isEmpty())
                    <div class="bg-white rounded-2xl p-8 text-center">
                        <p class="text-gray-500">No verified students available</p>
                    </div>
                @else

                <!-- CARDS -->
                <div class="space-y-4">

                    @foreach($verifiedStudents as $student)

                        @php
                            $enrolled = $student->studentEnrollments->whereNotNull('course_id');
                        @endphp

                        <div class="bg-white rounded-2xl shadow-sm border p-5 hover:shadow-md transition">

                            <!-- TOP -->
                            <div class="flex flex-col md:flex-row md:justify-between gap-4">

                                <div>
                                    <h3 class="text-lg font-semibold">
                                        {{ $student->profile->first_name }} {{ $student->profile->last_name }}
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        ID: {{ $student->student_number }}
                                        • {{ $student->programRelation->name ?? 'No Program' }}
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $enrolled->count() }} course(s)
                                    </p>
                                </div>

                                <!-- STATUS -->
                                <div class="flex flex-col gap-2">

                                    @if($enrolled->isEmpty())
                                        <span class="text-xs bg-orange-100 text-orange-700 px-3 py-1 rounded-full">
                                            No courses
                                        </span>
                                    @else
                                        <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                            Enrolled
                                        </span>
                                    @endif

                                    <a href="{{ route('admin.enrollment.assign', $student) }}"
                                        class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg text-center">
                                        Manage
                                    </a>

                                </div>
                            </div>

                            <!-- COURSES -->
                            @if($enrolled->isNotEmpty())
                                <div class="mt-4 pt-4 border-t flex flex-wrap gap-2">

                                    @foreach($enrolled as $enrollment)
                                        <span class="text-xs bg-gray-100 px-3 py-1 rounded-full">
                                            {{ $enrollment->course->course_code }}
                                        </span>
                                    @endforeach

                                </div>
                            @endif

                        </div>

                    @endforeach

                </div>

                <!-- PAGINATION -->
                <div class="mt-6">
                    {{ $verifiedStudents->links() }}
                </div>

                @endif

            </div>

        </main>
    </div>
</div>
</x-app-layout>
