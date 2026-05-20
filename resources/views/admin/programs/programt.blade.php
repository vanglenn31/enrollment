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
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Programs</h1>
                            <p class="text-sm text-gray-500 mt-1">
                                Manage academic programs efficiently
                            </p>
                        </div>

                        <a href="{{ route('admin.programs.create') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-blue-700 transition">
                            + Add Program
                        </a>
                    </div>

                    <!-- SUCCESS MESSAGE -->
                    @if(session('success'))
                        <div class="rounded-2xl bg-emerald-100 border border-emerald-200 text-emerald-700 p-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- CARD -->
                    <div class="bg-white rounded-2xl shadow-sm border p-4 sm:p-6 space-y-6">

                        <!-- SEARCH + FILTER ROW -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                            {{-- Search --}}
                            <form method="GET" action="{{ route('admin.programs.programs') }}" class="w-full sm:w-80">
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                <div class="flex items-center bg-gray-100 rounded-xl px-3 py-2">
                                    <input name="search" value="{{ $search ?? '' }}"
                                           type="text"
                                           placeholder="Search programs..."
                                           class="bg-transparent w-full outline-none text-sm px-2">
                                    <button class="bg-slate-900 text-white text-sm px-4 py-1.5 rounded-lg">
                                        Search
                                    </button>
                                </div>
                            </form>

                            {{-- Status filter tabs --}}
                            <div class="flex items-center gap-1 bg-gray-100 rounded-xl p-1 self-start sm:self-auto">
                                @php
                                    $tabs = [
                                        ''         => ['label' => 'All',      'count' => $totalCount ?? 0,    'active' => 'bg-white text-gray-900 shadow-sm', 'idle' => 'text-gray-500 hover:text-gray-700'],
                                        'active'   => ['label' => 'Active',   'count' => $activeCount ?? 0,   'active' => 'bg-white text-green-700 shadow-sm', 'idle' => 'text-gray-500 hover:text-gray-700'],
                                        'inactive' => ['label' => 'Inactive', 'count' => $inactiveCount ?? 0, 'active' => 'bg-white text-red-600 shadow-sm',   'idle' => 'text-gray-500 hover:text-gray-700'],
                                    ];
                                    $currentStatus = $status ?? '';
                                @endphp

                                @foreach($tabs as $val => $tab)
                                    <a href="{{ route('admin.programs.programs', array_filter(['search' => $search, 'status' => $val])) }}"
                                       class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium transition-all duration-150
                                              {{ $currentStatus === $val ? $tab['active'] : $tab['idle'] }}">
                                        {{ $tab['label'] }}
                                        <span class="rounded-full px-1.5 py-0.5 text-[11px] font-semibold leading-none
                                            {{ $currentStatus === $val
                                                ? ($val === 'active' ? 'bg-green-100 text-green-700' : ($val === 'inactive' ? 'bg-red-100 text-red-600' : 'bg-gray-200 text-gray-600'))
                                                : 'bg-gray-200 text-gray-500' }}">
                                            {{ $tab['count'] }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>

                        </div>

                        {{-- EMPTY STATE: no programs exist at all --}}
                        @if(($totalCount ?? 0) === 0 && !($search ?? ''))

                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="bg-gray-100 rounded-full p-5 mb-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-700">No programs yet</h3>
                                <p class="text-sm text-gray-400 mt-1 max-w-xs">
                                    Get started by adding your first academic program.
                                </p>
                                <a href="{{ route('admin.programs.create') }}"
                                   class="mt-5 inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition">
                                    + Add Program
                                </a>
                            </div>

                        @else

                            <!-- DESKTOP TABLE -->
                            <div class="hidden md:block overflow-x-auto border rounded-xl">
                                <table class="min-w-full text-sm text-gray-700">
                                    <thead class="bg-gray-50 text-gray-600">
                                        <tr>
                                            <th class="py-3 px-4 text-left">Program</th>
                                            <th class="py-3 px-4 text-left">Department</th>
                                            <th class="py-3 px-4 text-left">Code</th>
                                            <th class="py-3 px-4 text-left">Status</th>
                                            <th class="py-3 px-4 text-left">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y">
                                        @forelse($programs as $program)
                                            <tr class="hover:bg-gray-50">

                                                <td class="py-3 px-4 font-medium">
                                                    {{ $program->name }}
                                                </td>

                                                <td class="py-3 px-4">
                                                    {{ optional($program->department)->name ?? 'Unassigned' }}
                                                </td>

                                                <td class="py-3 px-4">
                                                    {{ $program->code }}
                                                </td>

                                                <td class="py-3 px-4">
                                                    <span class="px-2 py-1 rounded-full text-xs
                                                        {{ $program->status === 'active'
                                                            ? 'bg-green-100 text-green-700'
                                                            : 'bg-red-100 text-red-700' }}">
                                                        {{ ucfirst($program->status) }}
                                                    </span>
                                                </td>

                                                <td class="py-3 px-4 space-x-3">

                                                    <a href="{{ route('admin.programs.edit', $program) }}"
                                                       class="text-blue-600 hover:underline">
                                                        Edit
                                                    </a>

                                                    <form action="{{ strtolower($program->status) === 'active'
                                                        ? route('admin.programs.deactivate', $program)
                                                        : route('admin.programs.activate', $program) }}"
                                                        method="POST"
                                                        class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            onclick="return confirm('{{ strtolower($program->status) === 'active' ? 'Deactivate' : 'Activate' }} this program?')"
                                                            class="{{ strtolower($program->status) === 'active'
                                                                ? 'text-red-600 hover:underline'
                                                                : 'text-green-600 hover:underline' }}">
                                                            {{ strtolower($program->status) === 'active' ? 'Deactivate' : 'Activate' }}
                                                        </button>
                                                    </form>

                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-10 text-gray-400">
                                                    @if($search ?? '')
                                                        No programs matched your search.
                                                    @else
                                                        No {{ $currentStatus ? ucfirst($currentStatus) : '' }} programs found.
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- MOBILE VIEW -->
                            <div class="md:hidden space-y-4">
                                @forelse($programs as $program)

                                    <div class="border rounded-xl p-4 shadow-sm">

                                        <div class="flex justify-between items-start">
                                            <h3 class="font-semibold text-gray-900">
                                                {{ $program->name }}
                                            </h3>

                                            <span class="text-xs px-2 py-1 rounded-full
                                                {{ $program->status === 'active'
                                                    ? 'bg-green-100 text-green-700'
                                                    : 'bg-red-100 text-red-700' }}">
                                                {{ ucfirst($program->status) }}
                                            </span>
                                        </div>

                                        <div class="text-sm text-gray-600 mt-2 space-y-1">
                                            <p><strong>Department:</strong> {{ optional($program->department)->name ?? 'Unassigned' }}</p>
                                            <p><strong>Code:</strong> {{ $program->code }}</p>
                                        </div>

                                        <div class="mt-3 flex gap-3">

                                            <a href="{{ route('admin.programs.edit', $program) }}"
                                               class="text-blue-600 text-sm">
                                                Edit
                                            </a>

                                            <form action="{{ strtolower($program->status) === 'active'
                                                ? route('admin.programs.deactivate', $program)
                                                : route('admin.programs.activate', $program) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="{{ strtolower($program->status) === 'active'
                                                        ? 'text-red-600 text-sm'
                                                        : 'text-green-600 text-sm' }}">
                                                    {{ strtolower($program->status) === 'active' ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>

                                        </div>

                                    </div>

                                @empty
                                    <div class="text-center py-6 text-gray-400 text-sm">
                                        @if($search ?? '')
                                            No programs matched your search.
                                        @else
                                            No {{ $currentStatus ? ucfirst($currentStatus) : '' }} programs found.
                                        @endif
                                    </div>
                                @endforelse
                            </div>

                            <!-- PAGINATION -->
                            <div>
                                {{ $programs->links() }}
                            </div>

                        @endif

                    </div>

                </div>

            </main>
        </div>
    </div>
</x-app-layout>