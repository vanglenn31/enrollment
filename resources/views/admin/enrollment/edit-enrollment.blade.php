<x-app-layout>

<div class="flex min-h-screen bg-gray-100">

    <!-- SIDEBAR -->
    <aside class="hidden lg:block fixed inset-y-0 left-0 w-64 z-30">
        @include('layouts.admin_side_bar')
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="flex-1 w-full lg:ml-64 flex flex-col">

        <!-- NAV -->
        <header class="sticky top-0 z-50">
            @include('layouts.navigation')
        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">

            <div class="max-w-4xl mx-auto">

                <!-- HEADER -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">
                            Edit Course Assignment
                        </h1>

                        <p class="mt-2 text-sm text-gray-600">
                            Student:
                            <strong>
                                {{ $studentEnrollment->student->profile->first_name }}
                                {{ $studentEnrollment->student->profile->last_name }}
                            </strong>
                            • Student ID:
                            <strong>{{ $studentEnrollment->student->student_number }}</strong>
                        </p>
                    </div>

                    <a href="{{ route('admin.enrollment.assign', $studentEnrollment->student) }}"
                       class="text-sm text-blue-600 hover:text-blue-800">
                        Back to student courses
                    </a>

                </div>

                <!-- CARD -->
                <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-6">

                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Current assignment
                    </h2>

                    <div class="rounded-3xl border border-gray-200 bg-slate-50 p-4 mb-6">

                        <p class="font-semibold text-gray-900">
                            {{ $studentEnrollment->course->course_code }}
                            - {{ $studentEnrollment->course->course_name }}
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $studentEnrollment->course->units }} units •
                            ₱{{ number_format($studentEnrollment->course->course_price ?? 0, 2) }}
                        </p>

                    </div>

                    <!-- FORM -->
                    <form action="{{ route('admin.enrollment.update', $studentEnrollment) }}"
                          method="POST"
                          class="space-y-5">

                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Replace with course
                            </label>

                            <select name="course_id"
                                class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                                required>

                                <option value="">Choose a new course...</option>

                                @foreach($availableCourses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ $course->id === $studentEnrollment->course_id ? 'selected' : '' }}>
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
                            class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                            Update Course
                        </button>

                    </form>

                </div>

            </div>

        </main>

    </div>

</div>

</x-app-layout>