<x-app-layout>
    <div class="grid grid-cols-5 2xl:grid-cols-6 gap-x-0 gap-y-0">
        <div class="col-span-1 fixed h-screen z-30 w-fit lg:w-auto">
            @include('layouts.registrar_side_bar')
        </div>
        <div class="col-span-5 sticky top-0 z-50 col-start-2 z-20">@include('layouts.navigation')</div>
        <div class="col-span-4 col-start-2 p-6 z-10 md:z-10 w-full">
            <div class="min-h-screen bg-gray-100 p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h1 class="text-3xl font-semibold text-gray-900">Registrar Dashboard</h1>
                                <p class="mt-2 text-sm text-gray-500">Quick overview of school operations and registration metrics.</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-full border border-blue-600 px-4 py-2 text-sm font-semibold text-blue-600 hover:bg-blue-50">Back to main dashboard</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                        @foreach([
                            ['title' => 'Students', 'value' => $studentCount ?? 0, 'color' => 'bg-blue-50', 'text' => 'text-blue-700'],
                            ['title' => 'Courses', 'value' => $courseCount ?? 0, 'color' => 'bg-green-50', 'text' => 'text-green-700'],
                            ['title' => 'Departments', 'value' => $departmentCount ?? 0, 'color' => 'bg-purple-50', 'text' => 'text-purple-700'],
                            ['title' => 'Programs', 'value' => $programCount ?? 0, 'color' => 'bg-yellow-50', 'text' => 'text-yellow-700'],
                            ['title' => 'Professors', 'value' => $professorCount ?? 0, 'color' => 'bg-sky-50', 'text' => 'text-sky-700'],
                            ['title' => 'Registrars', 'value' => $registrarCount ?? 0, 'color' => 'bg-fuchsia-50', 'text' => 'text-fuchsia-700'],
                        ] as $card)
                        <div class="rounded-3xl p-6 shadow-sm {{ $card['color'] }}">
                            <p class="text-sm font-medium {{ $card['text'] }}">{{ $card['title'] }}</p>
                            <p class="mt-4 text-3xl font-semibold text-gray-900">{{ $card['value'] }}</p>
                        </div>
                        @endforeach
                    </div>

                    <!-- <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="bg-white rounded-3xl shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900">Registrar Actions</h2>
                            <p class="mt-3 text-sm text-gray-500">Manage student enrollments, verify documents, and keep registration records up to date.</p>
                            <div class="mt-5 space-y-3">
                                <a href="{{ route('registrar.students') }}" class="block rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100">View Students</a>
                                <a href="{{ route('registrar.course') }}" class="block rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100">Manage Courses</a>
                                <a href="{{ route('registrar.department') }}" class="block rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100">Review Departments</a>
                            </div>
                        </div> -->

                        <div class="bg-white rounded-3xl shadow-sm p-6 lg:col-span-2">
                            <h2 class="text-lg font-semibold text-gray-900">Latest Registration Summary</h2>
                            <div class="mt-5 space-y-4 text-sm text-gray-600">
                                <p>Registrar dashboards show real-time counts for active resources and help coordinate student records.</p>
                                <p>Use the links above to access enrollment, courses and department management quickly.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
