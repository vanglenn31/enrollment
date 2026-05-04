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
                        <h1 class="text-3xl font-semibold text-gray-900">My Classes</h1>
                        <p class="mt-2 text-sm text-gray-500">See all courses assigned to you and their current enrollment counts.</p>
                    </div>
                    <div class="bg-white rounded-3xl shadow-sm p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm text-gray-700">
                                <thead class="border-b border-gray-200">
                                    <tr>
                                        <th class="py-3 px-4 font-medium">Course</th>
                                        <th class="py-3 px-4 font-medium">Code</th>
                                        <th class="py-3 px-4 font-medium">Program</th>
                                        <th class="py-3 px-4 font-medium">Units</th>
                                        <th class="py-3 px-4 font-medium">Schedule</th>
                                        <th class="py-3 px-4 font-medium">Students</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($courses as $course)
                                        <tr class="hover:bg-slate-50">
                                            <td class="py-4 px-4">{{ $course->course_name }}</td>
                                            <td class="py-4 px-4">{{ $course->course_code }}</td>
                                            <td class="py-4 px-4">{{ optional($course->program)->name ?? 'General Education' }}</td>
                                            <td class="py-4 px-4">{{ $course->units }}</td>
                                            <td class="py-4 px-4">{{ $course->time ?? 'TBA' }}</td>
                                            <td class="py-4 px-4">{{ $course->student_enrollments_count }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-4 px-4 text-center text-gray-500">No assigned classes found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
