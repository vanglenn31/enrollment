<x-app-layout>
    <div class="grid grid-cols-5 2xl:grid-cols-6 gap-x-0 gap-y-0">
        <div class="col-span-1 fixed h-screen z-30 w-fit lg:w-auto">
            @include('layouts.admin_side_bar')
        </div>
        <div class="col-span-5 sticky top-0 z-50 col-start-2 z-20">
            @include('layouts.navigation')
        </div>
        <div class="col-span-4 col-start-2 p-6 z-10 md:z-10 w-full">

<div class="min-h-screen bg-gray-100 p-4 sm:p-6 lg:p-8">

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
            ['title' => 'Total Students', 'value' =>  $studentCount ?? 0 ],
            ['title' => 'New Applications', 'value' => '89'],
            ['title' => 'Pending Reviews', 'value' => '156'],
            ['title' => 'Active Programs', 'value' => '24'],
        ] as $card)
        <div class="bg-white p-4 rounded-xl shadow-sm">
            <p class="text-sm text-gray-500">{{ $card['title'] }}</p>
            <h2 class="text-xl font-bold text-gray-800 mt-1 text-end">{{ $card['value'] }}</h2>
        </div>
        @endforeach
    </div>

    <!-- Charts Section -->
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
            <div class="flex items-center justify-center h-64">
                <!-- Placeholder circle -->
                <div class="w-40 h-40 rounded-full bg-gradient-to-r from-blue-500 to-purple-500">
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
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
            <li class="flex items-start justify-between">
                <p class="text-sm text-gray-600">{{ $activity }}</p>
                <span class="text-xs text-gray-400">Just now</span>
            </li>
            @endforeach
        </ul>
    </div>

</div>
        </div>
</x-app-layout>