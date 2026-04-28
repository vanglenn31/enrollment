<x-app-layout>
    <div class="grid grid-cols-5 2xl:grid-cols-6 gap-x-0 gap-y-0">
        <div class="col-span-1 fixed h-screen z-30 w-fit lg:w-auto">
            @include('layouts.student_side_bar')
        </div>
        <div class="col-span-5 col-start-2 z-20">
            @include('layouts.navigation')
        </div>
        
        <div class="col-span-4 col-start-2 p-6 z-10 md:z-30 w-full">
            <h1 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->profile->first_name }}! <span class="text-xs sm:text-sm bg-blue-100 text-blue-600 px-3 py-1 rounded-full">
                {{ Auth::user()->profile->student->status }}
            </span></h1>
            
            <p class="text-gray-700">This is your student dashboard. Here you can view your academics progress.</p>
            <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        
        
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white p-4 rounded-lg shadow">Current GPA</div>
        <div class="bg-white p-4 rounded-lg shadow">Credits Earned</div>
        <div class="bg-white p-4 rounded-lg shadow">Academic Year</div>
        <div class="bg-white p-4 rounded-lg shadow">Current Courses</div>

    </div>

    <!-- COURSES -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 space-y-4">

    <h2 class="font-semibold">Current Courses</h2>

    <div class="space-y-4">

        @forelse($enrollments as $enrollment)

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">

                <!-- COURSE INFO -->
                <div>
                    <p class="font-medium">
                        {{ $enrollment->course->course_code }} - 
                        {{ $enrollment->course->course_name }}
                    </p>

                    <p class="text-sm text-gray-500">
                        {{ $enrollment->course->credits ?? 3 }} credits
                    </p>
                </div>

                <!-- PROGRESS BAR -->
                <div class="w-full sm:w-1/2">
                    <div class="bg-gray-200 h-2 rounded-full">
                        <div 
                            class="bg-blue-500 h-2 rounded-full"
                            style="width: {{ $enrollment->progress ?? 0 }}%">
                        </div>
                    </div>
                </div>

            </div>

        @empty

            <!-- EMPTY STATE -->
            <p class="text-gray-500 text-sm">
                No enrolled courses yet.
            </p>

        @endforelse

    </div>
</div>

</div>

    
    </div>
    

</x-app-layout>