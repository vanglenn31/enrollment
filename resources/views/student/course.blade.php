<x-app-layout>
    <div class="grid grid-cols-5 2xl:grid-cols-6 gap-x-0 gap-y-0">
        <div class="hidden md:block col-span-1 fixed h-screen z-30">
            @include('layouts.student_side_bar')
        </div>
        <div class="col-span-5 col-start-2 z-20">
            @include('layouts.navigation')
        </div>
        
        <div class="col-span-4 col-start-2 p-6 z-30 w-full">
            <h1 class="text-2xl font-bold mb-4">Course</h1>
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold">Subject Enlistment</h2>
                    <p class="text-gray-500 text-sm">Browse and select subjects for the semester</p>
                </div>

                <!-- Tabs -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <button class="px-4 py-2 text-sm bg-gray-100 rounded-lg">Browse Subjects</button>
                    <button class="px-4 py-2 text-sm bg-gray-100 rounded-lg">My Subjects</button>
                    <button class="px-4 py-2 text-sm bg-gray-100 rounded-lg">Schedule View</button>
                </div>

                <!-- Filters -->
                <div class=" gap-3 mb-6">
                    <input type="text" placeholder="Search subjects..."
                        class="w-full border rounded-lg px-3 py-2 text-sm">

                    <!-- <select class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option>All Departments</option>
                    </select>

                    <select class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option>All Year Levels</option>
                    </select> -->
                </div>

                <!-- //Course list -->
                <div class="space-y-4">

                    <!-- // Subject Item -->
                    <div class="border rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-50 hover:bg-blue-100 transition">

                        <!-- // Left Info -->
                        <div class="flex-1">
                            <h2 class="font-semibold text-sm sm:text-base">
                                CS101 - Introduction to Programming
                            </h2>

                            <p class="text-xs text-gray-500 mt-1">
                                Mon/Wed 8:00 AM • Prof. Johnson • Lab Fee: ₱500
                            </p>
                        </div>

                        <!-- // Right Actions -->
                        <div class="flex items-center justify-between md:justify-end gap-3">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">
                                25 slots
                            </span>

                            <button class="bg-black text-white px-4 py-2 text-xs rounded-lg">
                                Enlist
                            </button>
                        </div>
                    </div>

                </div>

            </div>

        
        </div>
        

</x-app-layout>