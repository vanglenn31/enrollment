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

                    <!-- PAGE HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Professors</h1>
                            <p class="text-sm text-gray-500 mt-1">Manage faculty accounts</p>
                        </div>
                        <a href="{{ route('admin.professors.create') }}"
                           class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow shrink-0">
                            + Add Professor
                        </a>
                    </div>

                    <!-- SUCCESS -->
                    @if(session('success'))
                        <div class="rounded-2xl bg-emerald-100 border border-emerald-200 text-emerald-700 p-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- MAIN CARD -->
                    <div class="bg-white rounded-2xl shadow-sm border p-4 sm:p-6 space-y-6">

                        <!-- SEARCH + FILTER ROW -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                            <!-- SEARCH -->
                            <form method="GET" action="{{ route('admin.professors') }}" class="w-full sm:w-80">
                                @if($status)
                                    <input type="hidden" name="status" value="{{ $status }}">
                                @endif
                                <div class="flex items-center bg-gray-100 rounded-xl px-3 py-2">
                                    <input name="search"
                                           value="{{ $search ?? '' }}"
                                           placeholder="Search name or prof #..."
                                           class="bg-transparent w-full outline-none text-sm px-2 text-gray-700 placeholder-gray-400">
                                    @if($search)
                                        <a href="{{ route('admin.professors', array_filter(['status' => $status])) }}"
                                           class="text-gray-400 hover:text-gray-600 text-lg leading-none mr-1">&times;</a>
                                    @endif
                                    <button type="submit"
                                            class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-sm shrink-0">
                                        Search
                                    </button>
                                </div>
                            </form>

                            <!-- STATUS FILTER TABS -->
                            <div class="flex items-center gap-1 bg-gray-100 rounded-xl p-1 self-start sm:self-auto">
                                @php
                                    $totalCount = ($totalActive ?? 0) + ($totalInactive ?? 0);
                                    $tabs = [
                                        ''         => ['label' => 'All',      'count' => $totalCount,        'active' => 'bg-white text-gray-900 shadow-sm',  'idle' => 'text-gray-500 hover:text-gray-700'],
                                        'active'   => ['label' => 'Active',   'count' => $totalActive ?? 0,  'active' => 'bg-white text-green-700 shadow-sm', 'idle' => 'text-gray-500 hover:text-gray-700'],
                                        'inactive' => ['label' => 'Inactive', 'count' => $totalInactive ?? 0,'active' => 'bg-white text-red-600 shadow-sm',   'idle' => 'text-gray-500 hover:text-gray-700'],
                                    ];
                                    $currentStatus = $status ?? '';
                                @endphp

                                @foreach($tabs as $val => $tab)
                                    <a href="{{ route('admin.professors', array_filter(['search' => $search, 'status' => $val ?: null])) }}"
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

                        {{-- EMPTY STATE: no professors at all --}}
                        @if((($totalActive ?? 0) + ($totalInactive ?? 0)) === 0 && !($search ?? ''))

                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="bg-gray-100 rounded-full p-5 mb-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-700">No professors yet</h3>
                                <p class="text-sm text-gray-400 mt-1 max-w-xs">Get started by adding your first faculty member.</p>
                                <a href="{{ route('admin.professors.create') }}"
                                   class="mt-5 inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition">
                                    + Add Professor
                                </a>
                            </div>

                        @else

                            {{-- MOBILE: stacked cards --}}
                            <div class="md:hidden divide-y divide-gray-100 border rounded-xl overflow-hidden">

                                @forelse($professors as $professor)
                                    @php
                                        $profile   = $professor->profile;
                                        $prof      = $profile?->professor;
                                        $isActive  = $prof?->status === 'active';
                                        $firstName = $profile?->first_name ?? '';
                                        $lastName  = $profile?->last_name  ?? '';
                                        $initials  = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                    @endphp

                                    <div class="px-4 py-4 space-y-3 hover:bg-gray-50">

                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center text-sm font-bold text-white
                                                        {{ $isActive ? 'bg-indigo-500' : 'bg-gray-300' }}">
                                                {{ $initials ?: '?' }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-900 truncate">{{ $firstName }} {{ $lastName }}</p>
                                                <p class="text-xs text-gray-400 font-mono">{{ $prof?->professor_number ?? '—' }}</p>
                                            </div>
                                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold shrink-0
                                                         {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-green-500' : 'bg-red-400' }}"></span>
                                                {{ ucfirst($prof?->status ?? '—') }}
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                                            <div>
                                                <p class="text-gray-400 uppercase tracking-wide font-medium mb-0.5">Email</p>
                                                <p class="text-gray-700 truncate">{{ $professor->profile?->user?->email ?? '—' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-400 uppercase tracking-wide font-medium mb-0.5">Department</p>
                                                <p class="text-gray-700">{{ $prof?->department?->name ?? '—' }}</p>
                                            </div>
                                        </div>

                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.professors.edit', $professor) }}"
                                               class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                                                Edit
                                            </a>

                                            @if($isActive)
                                                <form method="POST" action="{{ route('admin.professors.deactivate', $professor->id) }}" class="flex-1">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                            onclick="return confirm('Deactivate this professor?')"
                                                            class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-red-50 border border-red-100 text-xs font-medium text-red-600 hover:bg-red-100 transition-colors">
                                                        Deactivate
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.professors.activate', $professor->id) }}" class="flex-1">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                            onclick="return confirm('Activate this professor?')"
                                                            class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-green-50 border border-green-100 text-xs font-medium text-green-700 hover:bg-green-100 transition-colors">
                                                        Activate
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                    </div>

                                @empty
                                    <div class="py-12 flex flex-col items-center gap-2 text-gray-400">
                                        <svg class="w-10 h-10 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <p class="text-sm font-medium">
                                            @if($search ?? '')
                                                No professors matched your search.
                                            @else
                                                No {{ $currentStatus ? ucfirst($currentStatus) : '' }} professors found.
                                            @endif
                                        </p>
                                        @if($search || $status)
                                            <a href="{{ route('admin.professors') }}" class="text-xs text-blue-500 hover:underline">Clear filters</a>
                                        @endif
                                    </div>
                                @endforelse

                            </div>

                            {{-- DESKTOP: full table --}}
                            <div class="hidden md:block overflow-x-auto border rounded-xl">
                                <table class="min-w-full text-sm">

                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                            <th class="px-5 py-3.5 text-left">Professor</th>
                                            <th class="px-4 py-3.5 text-left">Prof #</th>
                                            <th class="px-4 py-3.5 text-left">Email</th>
                                            <th class="px-4 py-3.5 text-left">Department</th>
                                            <th class="px-4 py-3.5 text-left">Status</th>
                                            <th class="px-5 py-3.5 text-right">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-50">

                                        @forelse($professors as $professor)
                                            @php
                                                $profile   = $professor->profile;
                                                $prof      = $profile?->professor;
                                                $isActive  = $prof?->status === 'active';
                                                $firstName = $profile?->first_name ?? '';
                                                $lastName  = $profile?->last_name  ?? '';
                                                $initials  = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                            @endphp

                                            <tr class="hover:bg-gray-50 transition-colors">

                                                <td class="px-5 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-9 h-9 rounded-xl shrink-0 flex items-center justify-center text-xs font-bold text-white
                                                                    {{ $isActive ? 'bg-indigo-500' : 'bg-gray-300' }}">
                                                            {{ $initials ?: '?' }}
                                                        </div>
                                                        <span class="font-medium text-gray-900">{{ $firstName }} {{ $lastName }}</span>
                                                    </div>
                                                </td>

                                                <td class="px-4 py-4 font-mono text-xs text-gray-500">
                                                    {{ $prof?->professor_number ?? '—' }}
                                                </td>

                                                <td class="px-4 py-4 text-gray-600">
                                                    {{ $professor->profile?->user?->email ?? '—' }}
                                                </td>

                                                <td class="px-4 py-4 text-gray-600">
                                                    {{ $prof?->department?->name ?? '—' }}
                                                </td>

                                                <td class="px-4 py-4">
                                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold
                                                                 {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-green-500' : 'bg-red-400' }}"></span>
                                                        {{ ucfirst($prof?->status ?? 'unknown') }}
                                                    </span>
                                                </td>

                                                <td class="px-5 py-4">
                                                    <div class="flex items-center justify-end gap-2">

                                                        <a href="{{ route('admin.professors.edit', $professor) }}"
                                                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                                                            Edit
                                                        </a>

                                                        @if($isActive)
                                                            <form method="POST"
                                                                  action="{{ route('admin.professors.deactivate', $professor->id) }}"
                                                                  class="inline">
                                                                @csrf @method('PATCH')
                                                                <button type="submit"
                                                                        onclick="return confirm('Deactivate this professor?')"
                                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 border border-red-100 text-xs font-medium text-red-600 hover:bg-red-100 transition-colors">
                                                                    Deactivate
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form method="POST"
                                                                  action="{{ route('admin.professors.activate', $professor->id) }}"
                                                                  class="inline">
                                                                @csrf @method('PATCH')
                                                                <button type="submit"
                                                                        onclick="return confirm('Activate this professor?')"
                                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-green-50 border border-green-100 text-xs font-medium text-green-700 hover:bg-green-100 transition-colors">
                                                                    Activate
                                                                </button>
                                                            </form>
                                                        @endif

                                                    </div>
                                                </td>

                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="6" class="py-12 text-center">
                                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                                        <svg class="w-10 h-10 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <p class="text-sm font-medium">
                                                            @if($search ?? '')
                                                                No professors matched your search.
                                                            @else
                                                                No {{ $currentStatus ? ucfirst($currentStatus) : '' }} professors found.
                                                            @endif
                                                        </p>
                                                        @if($search || $status)
                                                            <a href="{{ route('admin.professors') }}" class="text-xs text-blue-500 hover:underline">Clear filters</a>
                                                        @endif
                                                    </div>
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

                        @endif

                    </div>

                </div>
            </main>
        </div>
    </div>
</x-app-layout>