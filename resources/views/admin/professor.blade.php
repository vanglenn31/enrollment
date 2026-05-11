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
                <div class="max-w-7xl mx-auto space-y-5">

                    <!-- PAGE HEADER -->
                    <div class="flex items-center justify-between ">
                        <div>
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Professors</h1>
                            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Manage faculty accounts</p>
                        </div>
                        <a href="{{ route('admin.professors.create') }}"
                           class="inline-flex items-center gap-1.5 bg-gray-900 text-white px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-gray-700 transition-colors shadow-sm shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="hidden sm:inline">Add Professor</span>
                            <span class="sm:hidden">Add</span>
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 text-sm">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- STAT CHIPS -->
                    <div class="grid grid-cols-3 gap-2 sm:flex sm:flex-wrap sm:gap-3">
                        <div class="flex flex-col sm:flex-row items-center gap-1 sm:gap-2 rounded-xl bg-white border border-gray-100 shadow-sm px-3 sm:px-4 py-2.5 text-xs sm:text-sm text-center sm:text-left">
                            <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                            <span class="text-gray-500">Active</span>
                            <span class="font-bold text-gray-900">{{ $totalActive }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center gap-1 sm:gap-2 rounded-xl bg-white border border-gray-100 shadow-sm px-3 sm:px-4 py-2.5 text-xs sm:text-sm text-center sm:text-left">
                            <span class="w-2 h-2 rounded-full bg-red-400 shrink-0"></span>
                            <span class="text-gray-500">Inactive</span>
                            <span class="font-bold text-gray-900">{{ $totalInactive }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center gap-1 sm:gap-2 rounded-xl bg-white border border-gray-100 shadow-sm px-3 sm:px-4 py-2.5 text-xs sm:text-sm text-center sm:text-left">
                            <span class="w-2 h-2 rounded-full bg-gray-400 shrink-0"></span>
                            <span class="text-gray-500">Total</span>
                            <span class="font-bold text-gray-900">{{ $totalActive + $totalInactive }}</span>
                        </div>
                    </div>

                    <!-- MAIN CARD -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                        <!-- FILTER + SEARCH -->
                        <div class="px-4 sm:px-5 pt-4 sm:pt-5 pb-4 border-b border-gray-100 space-y-3">

                            <!-- STATUS TABS — full-width on mobile -->
                            <div class="flex gap-1 bg-gray-100 rounded-xl p-1 w-full sm:w-fit">
                                @foreach(['' => 'All', 'active' => 'Active', 'inactive' => 'Inactive'] as $val => $label)
                                    <a href="{{ route('admin.professors', array_filter(['search' => $search, 'status' => $val ?: null])) }}"
                                       class="flex-1 sm:flex-none text-center px-3 sm:px-4 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition-colors
                                              {{ ($status ?? '') === $val
                                                  ? 'bg-white text-gray-900 shadow-sm'
                                                  : 'text-gray-500 hover:text-gray-700' }}">
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </div>

                            <!-- SEARCH -->
                            <form method="GET" action="{{ route('admin.professors') }}" class="flex gap-2">
                                @if($status)
                                    <input type="hidden" name="status" value="{{ $status }}">
                                @endif
                                <div class="flex-1 flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 sm:px-4 py-2 sm:py-2.5">
                                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input name="search"
                                           value="{{ $search ?? '' }}"
                                           placeholder="Search name or prof #..."
                                           class="bg-transparent w-full outline-none text-sm text-gray-700 placeholder-gray-400">
                                    @if($search)
                                        <a href="{{ route('admin.professors', array_filter(['status' => $status])) }}"
                                           class="text-gray-400 hover:text-gray-600 text-lg leading-none shrink-0">&times;</a>
                                    @endif
                                </div>
                                <button type="submit"
                                        class="bg-gray-900 text-white px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-gray-700 transition-colors shrink-0">
                                    Search
                                </button>
                            </form>

                        </div>

                        {{-- ── MOBILE: stacked cards (< md) ── --}}
                        <div class="md:hidden divide-y divide-gray-100">

                            @forelse($professors as $professor)
                                @php
                                    $profile   = $professor->profile;
                                    $prof      = $profile?->professor;
                                    $isActive  = $prof?->status === 'active';
                                    $firstName = $profile?->first_name ?? '';
                                    $lastName  = $profile?->last_name  ?? '';
                                    $initials  = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                @endphp

                                <div class="px-4 py-4 space-y-3">

                                    {{-- Avatar + name + status --}}
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

                                    {{-- Details --}}
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

                                    {{-- Actions --}}
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.professors.edit', $professor) }}"
                                           class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828A2 2 0 0110 16.414H8v-2a2 2 0 01.586-1.414z"/>
                                            </svg>
                                            Edit
                                        </a>

                                        @if($isActive)
                                            <form method="POST" action="{{ route('admin.professors.deactivate', $professor->id) }}" class="flex-1">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                        onclick="return confirm('Deactivate this professor?')"
                                                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-red-50 border border-red-100 text-xs font-medium text-red-600 hover:bg-red-100 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    </svg>
                                                    Deactivate
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.professors.activate', $professor->id) }}" class="flex-1">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                        onclick="return confirm('Activate this professor?')"
                                                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-green-50 border border-green-100 text-xs font-medium text-green-700 hover:bg-green-100 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Activate
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                </div>

                            @empty
                                <div class="py-16 flex flex-col items-center gap-2 text-gray-400">
                                    <svg class="w-10 h-10 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <p class="text-sm font-medium">No professors found</p>
                                    @if($search || $status)
                                        <a href="{{ route('admin.professors') }}" class="text-xs text-indigo-500 hover:underline">Clear filters</a>
                                    @endif
                                </div>
                            @endforelse

                        </div>

                        {{-- ── DESKTOP: full table (md+) ── --}}
                        <div class="hidden md:block overflow-x-auto">
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
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828A2 2 0 0110 16.414H8v-2a2 2 0 01.586-1.414z"/>
                                                        </svg>
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
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/>
                                                                </svg>
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
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                Activate
                                                            </button>
                                                        </form>
                                                    @endif

                                                </div>
                                            </td>

                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-16 text-center">
                                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                                    <svg class="w-10 h-10 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    <p class="text-sm font-medium">No professors found</p>
                                                    @if($search || $status)
                                                        <a href="{{ route('admin.professors') }}" class="text-xs text-indigo-500 hover:underline">Clear filters</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                        <!-- PAGINATION -->
                        @if($professors->hasPages())
                            <div class="px-4 sm:px-5 py-4 border-t border-gray-100">
                                {{ $professors->links() }}
                            </div>
                        @endif

                    </div>

                </div>
            </main>
        </div>
    </div>
</x-app-layout>