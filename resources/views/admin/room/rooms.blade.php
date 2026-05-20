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

                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- PAGE HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Rooms</h1>
                            <p class="text-sm text-gray-500 mt-1">Manage classrooms and buildings</p>
                        </div>

                        <a href="{{ route('admin.rooms.create') }}"
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow shrink-0">
                            + Add Room
                        </a>
                    </div>

                    <!-- SUCCESS -->
                    @if(session('success'))
                        <div class="rounded-2xl bg-emerald-100 border border-emerald-200 text-emerald-700 p-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- CARD -->
                    <div class="bg-white rounded-2xl shadow-sm border p-4 sm:p-6 space-y-6">

                        <!-- TOP BAR -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Room List</h2>
                                <p class="text-sm text-gray-500">All registered classrooms and buildings</p>
                            </div>

                            <!-- SEARCH -->
                            <form method="GET" action="{{ route('admin.rooms.index') }}" class="w-full sm:w-80">
                                <div class="flex items-center bg-gray-100 rounded-xl px-3 py-2">
                                    <input type="text" name="search" value="{{ $search ?? '' }}"
                                           placeholder="Search rooms or buildings..."
                                           class="bg-transparent w-full outline-none text-sm px-2 text-gray-700 placeholder-gray-400">
                                    <button type="submit"
                                            class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-sm shrink-0">
                                        Search
                                    </button>
                                </div>
                            </form>

                        </div>

                        <!-- EMPTY STATE -->
                        @if(($totalCount ?? 0) === 0 && !($search ?? ''))

                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="bg-gray-100 rounded-full p-5 mb-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-700">No rooms yet</h3>
                                <p class="text-sm text-gray-400 mt-1 max-w-xs">
                                    Get started by adding your first classroom or building.
                                </p>
                                <a href="{{ route('admin.rooms.create') }}"
                                   class="mt-5 inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition">
                                    + Add Room
                                </a>
                            </div>

                        @else

                            <!-- MOBILE CARDS -->
                            <div class="md:hidden space-y-3">
                                @forelse($rooms as $room)
                                    <div class="border rounded-xl p-4 hover:bg-gray-50 transition">

                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $room->room_name }}</p>
                                                <p class="text-sm text-gray-500 mt-0.5">{{ $room->room_building }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 shrink-0">
                                                {{ $room->courses_count ?? $room->courses()->count() }} course(s)
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-4 pt-3 mt-3 border-t border-gray-100">
                                            <a href="{{ route('admin.rooms.edit', $room) }}"
                                               class="text-sm text-blue-600 hover:underline font-medium">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST"
                                                  onsubmit="return confirm('Delete this room? Courses assigned to it will be unlinked.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-sm text-red-500 hover:text-red-700 font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                @empty
                                    <div class="text-center py-10 text-gray-400 text-sm">
                                        @if($search ?? '')
                                            No rooms matched your search.
                                        @else
                                            No rooms found.
                                        @endif
                                    </div>
                                @endforelse
                            </div>

                            <!-- DESKTOP TABLE -->
                            <div class="hidden md:block overflow-x-auto border rounded-xl">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gray-50 text-gray-600">
                                        <tr>
                                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Room Name</th>
                                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Building</th>
                                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide">Courses Assigned</th>
                                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($rooms as $room)
                                            <tr class="hover:bg-gray-50 transition">

                                                <td class="px-5 py-4 font-medium text-gray-900">
                                                    {{ $room->room_name }}
                                                </td>

                                                <td class="px-5 py-4 text-gray-600">
                                                    {{ $room->room_building }}
                                                </td>

                                                <td class="px-5 py-4">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                                        {{ $room->courses_count ?? $room->courses()->count() }} course(s)
                                                    </span>
                                                </td>

                                                <td class="px-5 py-4 text-right">
                                                    <div class="flex items-center justify-end gap-3">
                                                        <a href="{{ route('admin.rooms.edit', $room) }}"
                                                           class="text-blue-600 hover:underline text-sm font-medium">
                                                            Edit
                                                        </a>

                                                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST"
                                                              onsubmit="return confirm('Delete this room? Courses assigned to it will be unlinked.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="text-red-500 hover:text-red-700 text-sm font-medium">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-5 py-12 text-center text-gray-400">
                                                    @if($search ?? '')
                                                        No rooms matched your search.
                                                    @else
                                                        No rooms found.
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- PAGINATION -->
                            <div>
                                {{ $rooms->links() }}
                            </div>

                        @endif

                    </div>

                </div>

            </main>
        </div>
    </div>
</x-app-layout>