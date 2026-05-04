<x-app-layout>

<div class="flex min-h-screen bg-gray-100">

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

            <div class="max-w-4xl mx-auto">

                <!-- HEADER -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">
                            Assign Courses
                        </h1>

                        <p class="mt-2 text-sm text-gray-600">
                            Student:
                            <strong>{{ $student->profile->first_name }} {{ $student->profile->last_name }}</strong>
                            • Student ID:
                            <strong>{{ $student->student_number }}</strong>
                            • Program:
                            <strong>{{ $student->programRelation->name }}</strong>
                        </p>
                    </div>

                    <a href="{{ route('admin.enrollment') }}"
                       class="text-sm text-blue-600 hover:text-blue-800">
                        Back to enrollment
                    </a>

                </div>

                <!-- SUCCESS -->
                @if(session('success'))
                    <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- GRID -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- CURRENT COURSES -->
                    <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-6">

                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Currently Enrolled Courses
                        </h2>

                        @if($student->studentEnrollments->whereNotNull('course_id')->isEmpty())

                            <div class="rounded-2xl border border-dashed border-gray-300 p-6 text-center text-gray-500 text-sm">
                                No courses assigned yet.
                            </div>

                        @else

                            <div class="space-y-3">

                                @foreach($student->studentEnrollments->whereNotNull('course_id') as $enrollment)

                                    <div class="rounded-2xl border border-gray-200 p-4 bg-slate-50 flex flex-col sm:flex-row sm:justify-between gap-3">

                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">
                                                {{ $enrollment->course->course_code }}
                                            </p>

                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $enrollment->course->course_name }}
                                            </p>

                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $enrollment->course->units }} units •
                                                ₱{{ number_format($enrollment->course->course_price ?? 0, 2) }}
                                            </p>
                                        </div>

                                        <div class="flex gap-2 items-center">

                                            <a href="{{ route('admin.enrollment.edit', $enrollment) }}"
                                               class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-700">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.enrollment.remove', $enrollment) }}"
                                                  method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 text-sm font-medium"
                                                    onclick="return confirm('Remove this course?')">
                                                    Remove
                                                </button>

                                            </form>
                                                
                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @endif

                    </div>

                    <!-- ADD COURSE -->
                    <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-6">

                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            Add Courses
                        </h2>

                        @if($availableCourses->isEmpty())

                            <div class="rounded-2xl border border-dashed border-gray-300 p-6 text-center text-gray-500 text-sm">
                                All courses for this program have been assigned.
                            </div>

                        @else

                            <form action="{{ route('admin.enrollment.store', $student) }}"
                                  method="POST"
                                  class="space-y-4">

                                @csrf

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Select Course
                                    </label>

                                    <select name="course_id"
                                        class="w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                                        required>

                                        <option value="">Choose a course...</option>

                                        @foreach($availableCourses as $course)
                                            <option value="{{ $course->id }}">
                                                {{ $course->course_code }} - {{ $course->course_name }}
                                                ({{ $course->units }} units)
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('course_id')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror

                                </div>

                                <button type="submit"
                                    class="w-full rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                                    Assign Course
                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

</x-app-layout>