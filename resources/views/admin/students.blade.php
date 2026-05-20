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
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        Students
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Manage student records and statuses
                    </p>
                </div>

                <!-- CARD -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 sm:p-6 space-y-6">

                    <!-- SEARCH + FILTER ROW -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                        <!-- SEARCH -->
                        <form method="GET" action="{{ route('admin.students') }}" class="w-full sm:w-80">
                            @if(request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                            <div class="flex items-center bg-gray-100 rounded-xl px-3 py-2">
                                <input name="search" value="{{ $search ?? '' }}"
                                    class="bg-transparent w-full outline-none text-sm px-2"
                                    placeholder="Search students...">
                                <button class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-sm">
                                    Search
                                </button>
                            </div>
                        </form>

                        <!-- STATUS FILTER TABS -->
                        <div class="flex items-center gap-1 bg-gray-100 rounded-xl p-1 self-start sm:self-auto">
                            @php
                                $tabs = [
                                    ''           => ['label' => 'All',        'count' => $totalCount ?? 0,      'activeClass' => 'bg-white text-gray-900 shadow-sm',   'badgeActive' => 'bg-gray-200 text-gray-600'],
                                    'verified'   => ['label' => 'Verified',   'count' => $verifiedCount ?? 0,   'activeClass' => 'bg-white text-green-700 shadow-sm',  'badgeActive' => 'bg-green-100 text-green-700'],
                                    'unverified' => ['label' => 'Unverified', 'count' => $unverifiedCount ?? 0, 'activeClass' => 'bg-white text-yellow-700 shadow-sm', 'badgeActive' => 'bg-yellow-100 text-yellow-700'],
                                    'withdrawn'  => ['label' => 'Withdrawn',  'count' => $withdrawnCount ?? 0,  'activeClass' => 'bg-white text-red-600 shadow-sm',    'badgeActive' => 'bg-red-100 text-red-600'],
                                ];
                                $currentStatus = $status ?? '';
                            @endphp

                            @foreach($tabs as $val => $tab)
                                <a href="{{ route('admin.students', array_filter(['search' => $search, 'status' => $val])) }}"
                                   class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium transition-all duration-150
                                          {{ $currentStatus === $val ? $tab['activeClass'] : 'text-gray-500 hover:text-gray-700' }}">
                                    {{ $tab['label'] }}
                                    <span class="rounded-full px-1.5 py-0.5 text-[11px] font-semibold leading-none
                                        {{ $currentStatus === $val ? $tab['badgeActive'] : 'bg-gray-200 text-gray-500' }}">
                                        {{ $tab['count'] }}
                                    </span>
                                </a>
                            @endforeach
                        </div>

                    </div>

                    {{-- EMPTY STATE: no students at all --}}
                    @if(($totalCount ?? 0) === 0 && !($search ?? ''))

                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="bg-gray-100 rounded-full p-5 mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700">No students yet</h3>
                            <p class="text-sm text-gray-400 mt-1 max-w-xs">
                                Student records will appear here once they register.
                            </p>
                        </div>

                    @else

                        <!-- TABLE (DESKTOP) -->
                        <div class="hidden md:block overflow-x-auto border rounded-xl">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="p-3 text-left">ID</th>
                                        <th class="p-3 text-left">Student</th>
                                        <th class="p-3 text-left">Program</th>
                                        <th class="p-3 text-left">Status</th>
                                        <th class="p-3 text-left">Created</th>
                                        <th class="p-3 text-left">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y">
                                    @forelse($students as $student)
                                        <tr class="hover:bg-gray-50">

                                            <td class="p-3">
                                                {{ $student->student_number ?? '—' }}
                                            </td>

                                            <td class="p-3">
                                                {{ optional($student->profile)->first_name }}
                                                {{ optional($student->profile)->last_name }}
                                            </td>

                                            <td class="p-3">
                                                {{ optional($student->programRelation)->code ?? 'Unknown' }}
                                            </td>

                                            <td class="p-3">
                                                @if($student->is_withdrawn)
                                                    <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-600">Withdrawn</span>
                                                @elseif($student->is_verified)
                                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Verified</span>
                                                @else
                                                    <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Unverified</span>
                                                @endif
                                            </td>

                                            <td class="p-3">
                                                {{ $student->created_at?->format('M d, Y') }}
                                            </td>

                                            <td class="p-3">
                                                <a href="{{ route('admin.students.edit', $student) }}"
                                                    class="text-blue-600 hover:underline">
                                                    Edit
                                                </a>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center p-6 text-gray-400">
                                                @if($search ?? '')
                                                    No students matched your search.
                                                @else
                                                    No {{ $currentStatus ? ucfirst($currentStatus) : '' }} students found.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- MOBILE CARDS -->
                        <div class="md:hidden space-y-4">
                            @forelse($students as $student)
                                <div class="border rounded-xl p-4">

                                    <div class="flex justify-between items-start">
                                        <h3 class="font-semibold">
                                            {{ optional($student->profile)->first_name }}
                                            {{ optional($student->profile)->last_name }}
                                        </h3>

                                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                                            {{ $student->student_number ?? '—' }}
                                        </span>
                                    </div>

                                    <div class="mt-2 text-sm text-gray-600 space-y-1">
                                        <p><strong>Program:</strong> {{ optional($student->programRelation)->name ?? 'Unknown' }}</p>
                                        <p>
                                            <strong>Status:</strong>
                                            @if($student->is_withdrawn)
                                                <span class="text-red-600">Withdrawn</span>
                                            @elseif($student->is_verified)
                                                <span class="text-green-700">Verified</span>
                                            @else
                                                <span class="text-yellow-700">Unverified</span>
                                            @endif
                                        </p>
                                    </div>

                                    <a href="{{ route('admin.students.edit', $student) }}"
                                        class="text-blue-600 text-sm mt-3 inline-block">
                                        Edit →
                                    </a>

                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-400 text-sm">
                                    @if($search ?? '')
                                        No students matched your search.
                                    @else
                                        No {{ $currentStatus ? ucfirst($currentStatus) : '' }} students found.
                                    @endif
                                </div>
                            @endforelse
                        </div>

                        <!-- PAGINATION -->
                        <div>
                            {{ $students->links() }}
                        </div>

                    @endif

                </div>
            </div>

        </main>
    </div>
</div>
</x-app-layout>