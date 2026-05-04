<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
            @include('layouts.' . auth()->user()->role->role . '_side_bar')
        </aside>

        <!-- MAIN -->
        <div class="flex-1 lg:ml-64 flex flex-col">

            <!-- HEADER -->
            <header class="sticky top-0 z-30 bg-white shadow-sm">
                @include('layouts.navigation')
            </header>

            <!-- CONTENT -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm p-4 sm:p-6">

                    <!-- HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">
                                Add Course
                            </h1>
                            <p class="text-sm text-gray-500 mt-1">
                                Create a course students can enroll in
                            </p>
                        </div>

                        <a href="{{ route('admin.course') }}"
                           class="text-sm text-blue-600 hover:text-blue-800">
                            Back
                        </a>
                    </div>

                    <!-- ERRORS -->
                    @if ($errors->any())
                        <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 p-4 mb-4">
                            <ul class="list-disc pl-5 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- FORM -->
                    <form action="{{ route('admin.courses.store') }}" method="POST"
                          class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Course Name -->
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-700">Course Name</label>
                                <input type="text" name="course_name" required
                                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Course Code -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">Course Code</label>
                                <input type="text" name="course_code" required
                                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Units -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">Units</label>
                                <input type="number" min="1" name="units" required
                                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" rows="3"
                                          class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                            </div>

                            <!-- Program -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">Program</label>
                                <select name="program_id"
                                        class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">General Education</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}">
                                            {{ $program->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Professor -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">Professor</label>
                                <select name="professor_id"
                                        class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Unassigned</option>
                                    @foreach($professors as $professor)
                                        <option value="{{ $professor->id }}">
                                            {{ optional($professor->profile)->first_name }}
                                            {{ optional($professor->profile)->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Room -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">Room</label>
                                <select name="room_id"
                                        class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">No Room Assigned</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}">
                                            {{ $room->room_name }} — {{ $room->room_building }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                           <div>
                                <label class="text-sm font-medium text-gray-700">Schedule Type</label>
                                <select name="schedule_type"
                                        class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select Schedule</option>
                                    <option value="MWF">MWF (Mon-Wed-Fri)</option>
                                    <option value="TTH">TTH (Tue-Thu-Sat)</option>
                                    <option value="DAILY">Daily (Mon-Sat)</option>
                                </select>
                            </div>

                            <!-- START TIME -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">Start Time</label>
                                <input type="time" name="start_time"
                                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- END TIME -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">End Time</label>
                                <input type="time" name="end_time"
                                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- PRICE -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">Price</label>
                                <input type="number" step="0.01" name="course_price"
                                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                        </div>

                        <!-- BUTTON -->
                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm">
                                Save Course
                            </button>
                        </div>

                    </form>

                </div>
            </main>
        </div>
    </div>
</x-app-layout>