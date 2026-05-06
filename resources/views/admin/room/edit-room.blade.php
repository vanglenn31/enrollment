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

                    <!-- PAGE HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Edit Room</h1>
                            <p class="text-sm text-gray-500 mt-1">Update room details</p>
                        </div>

                        <a href="{{ route('admin.rooms.index') }}"
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
                    <form action="{{ route('admin.rooms.update', $room) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Room Name -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">Room Name</label>
                                <input type="text" name="room_name" required
                                       value="{{ old('room_name', $room->room_name) }}"
                                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Building -->
                            <div>
                                <label class="text-sm font-medium text-gray-700">Building</label>
                                <input type="text" name="room_building" required
                                       value="{{ old('room_building', $room->room_building) }}"
                                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            </div>

                        </div>

                        <!-- BUTTON -->
                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm transition">
                                Update Room
                            </button>
                        </div>

                    </form>

                </div>
            </main>
        </div>
    </div>
</x-app-layout>