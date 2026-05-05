<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
            @include('layouts.' . auth()->user()->role->role . '_side_bar')
        </aside>

        <!-- MAIN AREA -->
        <div class="flex-1 lg:ml-64 flex flex-col">

            <!-- HEADER -->
            <header class="sticky top-0 z-30 bg-white shadow-sm">
                @include('layouts.navigation')
            </header>

            <!-- CONTENT -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto w-full space-y-6">

                    {{-- ── PAGE HEADER ── --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Academic Terms</h1>
                            <p class="mt-1 text-sm text-gray-500">
                                Manage school years and semesters that group student enrollments
                            </p>
                        </div>

                        <a href="{{ route('admin.terms.create') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-blue-700 transition">
                            + New Term
                        </a>
                    </div>

                    {{-- ── ACTIVE TERM BANNER ── --}}
                    @if($activeTerm)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3
                                    bg-green-50 border border-green-200 rounded-2xl px-5 py-4">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-green-100">
                                    {{-- calendar icon --}}
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-semibold text-green-800">Active Term: {{ $activeTerm->label }}</p>
                                    <p class="text-xs text-green-600 mt-0.5">
                                        @if($activeTerm->start_date && $activeTerm->end_date)
                                            {{ $activeTerm->start_date->format('M d, Y') }} – {{ $activeTerm->end_date->format('M d, Y') }}
                                            @php $days = $activeTerm->daysRemaining(); @endphp
                                            @if(!is_null($days))
                                                &bull;
                                                @if($days >= 0)
                                                    <span class="text-green-700">{{ $days }} day{{ $days !== 1 ? 's' : '' }} remaining</span>
                                                @else
                                                    <span class="text-red-600">{{ abs($days) }} day{{ abs($days) !== 1 ? 's' : '' }} overdue</span>
                                                @endif
                                            @endif
                                        @else
                                            No date range set
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- Enrollment toggle --}}
                            <form method="POST" action="{{ route('admin.terms.toggleEnrollment', $activeTerm) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="{{ $activeTerm->is_enrollment_open
                                            ? 'bg-red-100 text-red-700 hover:bg-red-200'
                                            : 'bg-blue-600 text-white hover:bg-blue-700' }}
                                            px-4 py-2 rounded-lg text-sm font-medium transition">
                                    {{ $activeTerm->is_enrollment_open ? '🔒 Close Enrollment' : '🔓 Open Enrollment' }}
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl px-5 py-4 text-yellow-800 text-sm font-medium">
                            ⚠️ No active term. Please activate a term so students can be enrolled.
                        </div>
                    @endif

                    {{-- ── FLASH MESSAGES ── --}}
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    {{-- ── CARD ── --}}
                    <div class="bg-white rounded-2xl shadow-sm border p-4 sm:p-6 space-y-6">

                        {{-- Search + Filter --}}
                        <form method="GET" action="{{ route('admin.terms.index') }}"
                              class="flex flex-col sm:flex-row gap-3">
                            <div class="flex items-center bg-gray-100 rounded-xl px-3 py-2 flex-1 sm:max-w-xs">
                                <input name="search" value="{{ $search ?? '' }}"
                                       placeholder="Search school year or semester…"
                                       class="bg-transparent w-full outline-none text-sm px-2">
                                <button class="bg-slate-900 text-white text-sm px-4 py-1.5 rounded-lg">
                                    Search
                                </button>
                            </div>

                            <select name="filter"
                                    onchange="this.form.submit()"
                                    class="rounded-xl border border-gray-200 text-sm px-3 py-2 bg-gray-50 focus:outline-none">
                                <option value="">All statuses</option>
                                <option value="upcoming" {{ ($filter ?? '') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="active"   {{ ($filter ?? '') === 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="ended"    {{ ($filter ?? '') === 'ended'    ? 'selected' : '' }}>Ended</option>
                            </select>
                        </form>

                        {{-- ── DESKTOP TABLE ── --}}
                        <div class="hidden md:block overflow-x-auto rounded-xl border">
                            <table class="min-w-full text-sm text-gray-700">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="py-3 px-4 text-left">Term</th>
                                        <th class="py-3 px-4 text-left">School Year</th>
                                        <th class="py-3 px-4 text-left">Semester</th>
                                        <th class="py-3 px-4 text-left">Start</th>
                                        <th class="py-3 px-4 text-left">End</th>
                                        <th class="py-3 px-4 text-left">Status</th>
                                        <th class="py-3 px-4 text-left">Enrollment</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y">
                                    @forelse($terms as $term)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4 font-medium">{{ $term->label }}</td>
                                            <td class="py-3 px-4">{{ $term->school_year }}</td>

                                            <td class="py-3 px-4">
                                                {{ match($term->semester) {
                                                    '1st'    => '1st Semester',
                                                    '2nd'    => '2nd Semester',
                                                    'summer' => 'Summer',
                                                    default  => $term->semester,
                                                } }}
                                            </td>

                                            <td class="py-3 px-4 text-gray-500">
                                                {{ $term->start_date?->format('M d, Y') ?? '—' }}
                                            </td>

                                            <td class="py-3 px-4 text-gray-500">
                                                {{ $term->end_date?->format('M d, Y') ?? '—' }}
                                            </td>

                                            {{-- STATUS BADGE --}}
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $term->statusBadgeClass() }}">
                                                    {{ ucfirst($term->status) }}
                                                </span>
                                            </td>

                                            {{-- ENROLLMENT TOGGLE --}}
                                            <td class="py-3 px-4">
                                                @if($term->status === 'active')
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                                        {{ $term->is_enrollment_open
                                                            ? 'bg-blue-100 text-blue-700'
                                                            : 'bg-gray-100 text-gray-500' }}">
                                                        {{ $term->is_enrollment_open ? 'Open' : 'Closed' }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-300 text-xs">—</span>
                                                @endif
                                            </td>

                                            {{-- ACTIONS --}}
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-3">

                                                    <a href="{{ route('admin.terms.edit', $term) }}"
                                                       class="text-blue-600 hover:underline text-sm">
                                                        Edit
                                                    </a>

                                                    @if($term->status === 'upcoming')
                                                        <form method="POST"
                                                              action="{{ route('admin.terms.activate', $term) }}">
                                                            @csrf @method('PATCH')
                                                            <button type="submit"
                                                                    onclick="return confirm('Activate this term? The current active term will be ended.')"
                                                                    class="text-green-600 hover:underline text-sm">
                                                                Activate
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($term->status === 'active')
                                                        <form method="POST"
                                                              action="{{ route('admin.terms.end', $term) }}">
                                                            @csrf @method('PATCH')
                                                            <button type="submit"
                                                                    onclick="return confirm('End this term? This cannot be undone.')"
                                                                    class="text-red-600 hover:underline text-sm">
                                                                End Term
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($term->status === 'upcoming')
                                                        <form method="POST"
                                                              action="{{ route('admin.terms.destroy', $term) }}">
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                    onclick="return confirm('Delete this term permanently?')"
                                                                    class="text-gray-400 hover:text-red-500 hover:underline text-sm">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-10 text-gray-400">
                                                No terms found. Create one to get started.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- ── MOBILE CARDS ── --}}
                        <div class="md:hidden space-y-4">
                            @forelse($terms as $term)
                                <div class="border rounded-xl p-4 shadow-sm space-y-2">

                                    <div class="flex items-center justify-between">
                                        <h3 class="font-semibold text-gray-800">{{ $term->label }}</h3>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $term->statusBadgeClass() }}">
                                            {{ ucfirst($term->status) }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-gray-500">
                                        {{ $term->start_date?->format('M d, Y') ?? 'No start date' }}
                                        –
                                        {{ $term->end_date?->format('M d, Y') ?? 'No end date' }}
                                    </p>

                                    @if($term->status === 'active')
                                        <p class="text-sm">
                                            Enrollment:
                                            <span class="font-medium {{ $term->is_enrollment_open ? 'text-blue-600' : 'text-gray-400' }}">
                                                {{ $term->is_enrollment_open ? 'Open' : 'Closed' }}
                                            </span>
                                        </p>
                                    @endif

                                    <div class="flex flex-wrap gap-3 pt-2">
                                        <a href="{{ route('admin.terms.edit', $term) }}"
                                           class="text-blue-600 text-sm">Edit</a>

                                        @if($term->status === 'upcoming')
                                            <form method="POST" action="{{ route('admin.terms.activate', $term) }}">
                                                @csrf @method('PATCH')
                                                <button onclick="return confirm('Activate this term?')"
                                                        class="text-green-600 text-sm">
                                                    Activate
                                                </button>
                                            </form>
                                        @endif

                                        @if($term->status === 'active')
                                            <form method="POST" action="{{ route('admin.terms.end', $term) }}">
                                                @csrf @method('PATCH')
                                                <button onclick="return confirm('End this term?')"
                                                        class="text-red-600 text-sm">
                                                    End Term
                                                </button>
                                            </form>
                                        @endif

                                        @if($term->status === 'upcoming')
                                            <form method="POST" action="{{ route('admin.terms.destroy', $term) }}">
                                                @csrf @method('DELETE')
                                                <button onclick="return confirm('Delete this term?')"
                                                        class="text-gray-400 text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-400 py-6">No terms found.</p>
                            @endforelse
                        </div>

                        {{-- PAGINATION --}}
                        <div>{{ $terms->links() }}</div>

                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>