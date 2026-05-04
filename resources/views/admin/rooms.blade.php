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

                <!-- PAGE HEADER -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Rooms</h1>
                        <p class="text-sm text-gray-500 mt-1">Manage classrooms and buildings</p>
                    </div>

                    <a href="{{ route('admin.rooms.create') }}"
                       class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition w-full sm:w-auto">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Room
                    </a>
                </div>

                <!-- SUCCESS MESSAGE -->
                @if(session('success'))
                    <div class="rounded-xl bg-green-50 border border-green-200 text-green-700 p-4 mb-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- SEARCH -->
                <form method="GET" action="{{ route('admin.rooms') }}" class="mb-4">
                    <div class="relative w-full sm:max-w-sm">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                               placeholder="Search rooms or buildings..."
                               class="pl-9 pr-4 py-2 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </form>

                <!-- ========== MOBILE CARD LIST (hidden on md+) ========== -->
                <div class="flex flex-col gap-3 md:hidden">
                    @forelse($rooms as $room)
                        <div class="bg-white rounded-2xl shadow-sm p-4">

                            <!-- Card Header -->
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $room->room_name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $room->room_building }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 shrink-0">
                                    {{ $room->courses_count ?? $room->courses()->count() }} course(s)
                                </span>
                            </div>

                            <!-- Card Actions -->
                            <div class="flex items-center gap-4 pt-3 border-t border-gray-100">
                                <a href="{{ route('admin.rooms.edit', $room) }}"
                                   class="text-sm text-blue-600 hover:text-blue-800 font-medium transition">
                                    Edit
                                </a>

                                <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST"
                                      onsubmit="return confirm('Delete this room? Courses assigned to it will be unlinked.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-sm text-red-500 hover:text-red-700 font-medium transition">
                                        Delete
                                    </button>
                                </form>
                            </div>

                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-sm p-8 text-center text-sm text-gray-400">
                            No rooms found.
                            <a href="{{ route('admin.rooms.create') }}" class="text-blue-600 hover:underline ml-1">Add one now.</a>
                        </div>
                    @endforelse

                    <!-- Mobile Pagination -->
                    @if($rooms->hasPages())
                        <div class="mt-2">
                            {{ $rooms->links() }}
                        </div>
                    @endif
                </div>

                <!-- ========== DESKTOP TABLE (hidden below md) ========== -->
                <div class="hidden md:block bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Room Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Building
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Courses Assigned
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($rooms as $room)
                                    <tr class="hover:bg-gray-50 transition">

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ $room->room_name }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-600">
                                                {{ $room->room_building }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                                {{ $room->courses_count ?? $room->courses()->count() }} course(s)
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.rooms.edit', $room) }}"
                                                   class="text-sm text-blue-600 hover:text-blue-800 font-medium transition">
                                                    Edit
                                                </a>

                                                <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST"
                                                      onsubmit="return confirm('Delete this room? Courses assigned to it will be unlinked.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-sm text-red-500 hover:text-red-700 font-medium transition">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-400">
                                            No rooms found.
                                            <a href="{{ route('admin.rooms.create') }}" class="text-blue-600 hover:underline ml-1">Add one now.</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Desktop Pagination -->
                    @if($rooms->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $rooms->links() }}
                        </div>
                    @endif
                </div>

            </main>
        </div>
    </div>
</x-app-layout>