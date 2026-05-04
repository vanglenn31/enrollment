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
                <div class="max-w-7xl mx-auto w-full">

                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">
                            Welcome back, Admin!
                        </h1>
                        <button class="mt-3 sm:mt-0 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                            Generate Report
                        </button>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        @foreach([
                            ['title' => 'Total Students', 'value' => $studentCount ?? 0],
                            ['title' => 'New Applications', 'value' => $newStudentsThisMonth ?? 0],
                            ['title' => 'Pending Reviews', 'value' => $pendingReviews ?? 0],
                            ['title' => 'Active Programs', 'value' => $activePrograms ?? 0],
                        ] as $card)
                        <div class="bg-white p-4 rounded-xl shadow-sm">
                            <p class="text-sm text-gray-500">{{ $card['title'] }}</p>
                            <h2 class="text-xl font-bold text-gray-800 mt-1 text-end">
                                {{ $card['value'] }}
                            </h2>
                        </div>
                        @endforeach
                    </div>

                    <!-- Charts -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                        <!-- Enrollment Trends -->
                        <div class="bg-white p-4 rounded-xl shadow-sm lg:col-span-2">
                            <h2 class="text-sm font-semibold text-gray-700 mb-4">
                                Enrollment Trends
                            </h2>
                            <div class="h-64 flex items-end space-x-2">
                                @foreach([40, 50, 60, 80, 100, 120] as $value)
                                <div class="flex-1 bg-green-500 rounded-t"
                                     style="height: {{ $value }}%">
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Students by Program -->
                        <div class="bg-white p-4 rounded-xl shadow-sm">
                            <h2 class="text-sm font-semibold text-gray-700 mb-4">
                                Students by Program
                            </h2>
                            <div class="space-y-3">
                                @forelse($studentsByProgram ?? [] as $program)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">
                                        {{ $program->name }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-800">
                                        {{ $program->students_count }}
                                    </span>
                                </div>
                                @empty
                                <p class="text-sm text-gray-500">
                                    No programs available
                                </p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Activity -->
                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <h2 class="text-sm font-semibold text-gray-700 mb-4">
                            Recent Activity
                        </h2>

                        <ul class="space-y-4">
                            @foreach([
                                'New application received from John Smith',
                                'Payment overdue for student ID: STU123',
                                'Sarah Wilson enrolled in Computer Science',
                                'Document verification completed'
                            ] as $activity)
                            <li class="flex justify-between">
                                <p class="text-sm text-gray-600">{{ $activity }}</p>
                                <span class="text-xs text-gray-400">Just now</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </main>
        </div>
    </div>
</x-app-layout>