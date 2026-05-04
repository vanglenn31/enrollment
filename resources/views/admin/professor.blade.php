<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
            @include('layouts.' . auth()->user()->role->role . '_side_bar')
        </aside>

        <!-- MAIN -->
        <div class="flex-1 lg:ml-64 flex flex-col">

            <!-- NAV -->
            <header class="sticky top-0 z-30 bg-white shadow-sm">
                @include('layouts.navigation')
            </header>

            <!-- CONTENT -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">

                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                                Professors
                            </h1>
                            <p class="text-sm text-gray-500">
                                Manage faculty accounts
                            </p>
                        </div>

                        <a href="{{ route('admin.professors.create') }}"
                           class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold">
                            + Add Professor
                        </a>
                    </div>

                    <!-- CARD -->
                    <div class="bg-white rounded-2xl shadow-sm border p-4 sm:p-6 space-y-6">

                        <!-- TOP BAR -->
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                            <div>
                                <h2 class="text-lg font-semibold">Professor Directory</h2>
                                <p class="text-sm text-gray-500">
                                    Active and inactive faculty members
                                </p>
                            </div>

                            <!-- SEARCH -->
                            <form method="GET" action="{{ route('admin.professors') }}" class="w-full sm:w-80">
                                <div class="flex bg-gray-100 rounded-xl px-3 py-2">

                                    <input name="search"
                                           value="{{ $search ?? '' }}"
                                           placeholder="Search professors..."
                                           class="bg-transparent w-full outline-none text-sm">

                                    <button class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-sm">
                                        Search
                                    </button>

                                </div>
                            </form>

                        </div>

                        <!-- TABLE -->
                        <div class="overflow-x-auto border rounded-xl">
                            <table class="min-w-full text-sm">

                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="p-3 text-left">Name</th>
                                        <th class="p-3 text-left">Professor #</th>
                                        <th class="p-3 text-left">Email</th>
                                        <th class="p-3 text-left">Department</th>
                                        <th class="p-3 text-left">Status</th>
                                        <th class="p-3 text-left">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y">

                                    @forelse($professors as $professor)

                                        @php
                                            $profile = $professor->profile;
                                            $prof = $profile?->professor;
                                        @endphp

                                        <tr class="hover:bg-gray-50">

                                            <!-- NAME -->
                                            <td class="p-3">
                                                {{ $profile?->first_name }} {{ $profile?->last_name }}
                                            </td>

                                            <!-- PROFESSOR # -->
                                            <td class="p-3">
                                                {{ $prof?->professor_number ?? 'N/A' }}
                                            </td>

                                            <!-- EMAIL -->
                                            <td class="p-3">
                                                {{ $professor->profile?->user?->email ?? 'N/A' }}
                                            </td>

                                            <!-- DEPARTMENT -->
                                            <td class="p-3">
                                                {{ $prof?->department?->name ?? 'N/A' }}
                                            </td>

                                            <!-- STATUS -->
                                            <td class="p-3">
                                                <span class="{{ $prof?->status === 'active' ? 'text-green-600' : 'text-red-600' }} font-medium">
                                                    {{ ucfirst($prof?->status) }}
                                                </span>
                                            </td>

                                            <!-- ACTIONS -->
                                            <td class="p-3 flex gap-2">

                                                <a href="{{ route('admin.professors.edit', $professor) }}"
                                                   class="text-blue-600">
                                                    Edit
                                                </a>

                                                @if($prof?->status === 'active')

                                                    <form method="POST"
                                                          action="{{ route('admin.professors.deactivate', $professor->id) }}"
                                                          class="inline">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                                onclick="return confirm('Deactivate this professor?')"
                                                                class="px-3 py-1 bg-red-500 text-white rounded text-sm">
                                                            Deactivate
                                                        </button>
                                                    </form>

                                                @else

                                                    <form method="POST"
                                                          action="{{ route('admin.professors.activate', $professor->id) }}"
                                                          class="inline">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                                onclick="return confirm('Activate this professor?')"
                                                                class="px-3 py-1 bg-green-500 text-white rounded text-sm">
                                                            Activate
                                                        </button>
                                                    </form>

                                                @endif

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="6" class="text-center p-6 text-gray-400">
                                                No professors found
                                            </td>
                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>
                        </div>

                        <!-- PAGINATION -->
                        <div>
                            {{ $professors->links() }}
                        </div>

                    </div>

                </div>

            </main>
        </div>
    </div>
</x-app-layout>