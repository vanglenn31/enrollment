<x-app-layout>
    <div class="grid grid-cols-5 2xl:grid-cols-6 gap-x-0 gap-y-0">
        <div class="col-span-1 fixed h-screen z-30 w-fit lg:w-auto">
            @include('layouts.professor_side_bar')
        </div>
        <div class="col-span-5 sticky top-0 z-50 col-start-2 z-20">@include('layouts.navigation')</div>
        <div class="col-span-4 col-start-2 p-6 z-10 md:z-10 w-full">
            <div class="min-h-screen bg-gray-100 p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h1 class="text-3xl font-semibold text-gray-900">Professor Dashboard</h1>
                                <p class="mt-2 text-sm text-gray-500">Your teaching summary and current class schedule.</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Hello, {{ optional(optional($professor)->profile)->first_name ?? auth()->user()->email }}</p>
                                <p class="text-sm text-gray-500">{{ optional($professor)->professor_number ? 'Professor ID: ' . $professor->professor_number : '' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-6 rounded-3xl shadow-sm">
                            <p class="text-sm text-gray-500">Classes Assigned</p>
                            <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $classCount }}</p>
                        </div>
                        <div class="bg-white p-6 rounded-3xl shadow-sm">
                            <p class="text-sm text-gray-500">Total Enrolled Students</p>
                            <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $studentCount }}</p>
                        </div>
                        <div class="bg-white p-6 rounded-3xl shadow-sm">
                            <p class="text-sm text-gray-500">Programs Covered</p>
                            <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $programCount }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">My Current Classes</h2>
                            <a href="{{ route('professor.course') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View full schedule</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($courses as $course)
                                <div class="rounded-3xl border border-gray-200 p-4">
                                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
                                        <div>
                                            <p class="text-sm text-gray-500">{{ optional($course->program)->name ?? 'General Education' }}</p>
                                            <h3 class="text-xl font-semibold text-gray-900">{{ $course->course_name }} ({{ $course->course_code }})</h3>
                                            <p class="mt-1 text-sm text-gray-600">Units: {{ $course->units }} · Schedule: {{ $course->time ?? 'TBA' }}</p>
                                        </div>
                                        <div class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
                                            {{ $course->student_enrollments_count }} students
                                        </div>
                                    </div>
                                    <p class="mt-3 text-sm text-gray-600">{{ $course->description ?? 'No class description provided yet.' }}</p>
                                </div>
                            @empty
                                <div class="rounded-3xl border border-gray-200 p-6 text-center text-gray-600">
                                    <p>You are not assigned to any courses yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
