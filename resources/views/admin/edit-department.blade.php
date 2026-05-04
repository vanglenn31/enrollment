<x-app-layout>
    <div class="flex">

        <!-- SIDEBAR -->
        <div class="hidden lg:block lg:w-64 fixed h-screen z-30">
            @include('layouts.' . auth()->user()->role->role . '_side_bar')
        </div>

        <!-- MAIN CONTENT -->
        <div class="flex-1 lg:ml-64 w-full">

            <!-- NAVBAR -->
            <div class="sticky top-0 z-50">
                @include('layouts.navigation')
            </div>

            <!-- PAGE CONTENT -->
            <div class="min-h-screen bg-gray-100 p-4 sm:p-6 lg:p-8">
                
                <!-- YOUR CONTENT HERE -->
                <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-sm p-6">
                    
                    <!-- Example Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">
                                Edit Department
                            </h1>
                            <p class="mt-1 text-sm text-gray-500">
                                Change the name of this department.
                            </p>
                        </div>

                        <a href="{{ route('admin.department') }}"
                           class="text-sm text-blue-600 hover:text-blue-800">
                           Back
                        </a>
                    </div>

                    <!-- FORM -->
                    <form class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Department name
                            </label>
                            <input type="text"
                                class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" value="{{ old('name', $department->name) }}"/>
                        </div>

                        <div class="flex justify-end">
                            <button class="bg-blue-600 text-white px-5 py-2.5 rounded-lg">
                                Save
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>