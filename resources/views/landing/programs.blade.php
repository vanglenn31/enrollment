<x-guest-layout>
    @include('layouts.landing_nav')

    <!-- HEADER -->
    <section class="relative bg-gradient-to-br from-blue-900 via-blue-700 to-blue-500 text-white text-center py-20 px-4 overflow-hidden">
        <!-- Decorative circles -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full translate-x-1/3 translate-y-1/3"></div>
        <div class="absolute top-1/2 left-1/4 w-24 h-24 bg-blue-400/20 rounded-full blur-xl"></div>

        <div class="relative z-10">
            <span class="inline-block bg-white/20 backdrop-blur-sm text-white text-xs font-semibold px-4 py-1.5 rounded-full mb-4 tracking-widest uppercase">
                University of International Mindanao
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold mt-2 tracking-tight">Academic Programs</h1>
            <p class="mt-4 text-blue-100 text-sm md:text-base max-w-xl mx-auto leading-relaxed">
                Discover programs designed to advance your career and unlock your full potential.
            </p>
        </div>
    </section>

    <!-- SEARCH & FILTER -->
    <section class="bg-white border-b sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <form method="GET" action="{{ route('programs') }}" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">

                <!-- Search input -->
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                    </span>
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by program name or code..."
                        class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <!-- Department dropdown -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                        </svg>
                    </span>
                    <select name="department"
                        class="w-full sm:w-52 pl-9 pr-8 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition appearance-none cursor-pointer">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                    <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 pointer-events-none">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </div>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-colors duration-200 whitespace-nowrap shadow-sm">
                    Search
                </button>

                @if(request('search') || request('department'))
                    <a href="{{ route('programs') }}"
                        class="text-sm text-gray-500 hover:text-red-500 transition-colors flex items-center justify-center gap-1 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear
                    </a>
                @endif

            </form>
        </div>
    </section>

    <!-- PROGRAMS GRID -->
    <section class="max-w-7xl mx-auto px-4 py-10">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">
                @if(request('search') || request('department'))
                    Results
                    @if(request('search')) for <span class="text-blue-600">"{{ request('search') }}"</span>@endif
                    @if(request('department')) in <span class="text-blue-600">{{ request('department') }}</span>@endif
                @else
                    Featured Programs
                @endif
            </h2>
            <span class="text-sm text-gray-400">{{ $programs->total() }} program{{ $programs->total() !== 1 ? 's' : '' }} found</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @forelse ($programs as $program)
                <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col">

                    <!-- Card Top Accent -->
                    <div class="h-1.5 bg-gradient-to-r from-blue-500 to-blue-400"></div>

                    <div class="p-5 flex flex-col flex-1 gap-3">

                        <!-- Code Badge -->
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full tracking-wide">
                                {{ $program->code }}
                            </span>
                            <!-- Status dot -->
                            <span class="flex items-center gap-1.5 text-xs font-medium {{ $program->status === 'active' ? 'text-emerald-600' : 'text-gray-400' }}">
                                <span class="w-1.5 h-1.5 rounded-full inline-block {{ $program->status === 'active' ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                {{ ucfirst($program->status) }}
                            </span>
                        </div>

                        <!-- Program Name -->
                        <h3 class="font-bold text-gray-800 text-base leading-snug group-hover:text-blue-700 transition-colors">
                            {{ $program->name }}
                        </h3>

                        <!-- Department -->
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="truncate">{{ $program->department_name ?? 'No Department' }}</span>
                        </div>

                        <!-- Spacer -->
                        <div class="flex-1"></div>

                        <!-- Apply Button -->
                        <a href="{{ route('register') }}"
                            class="mt-2 block text-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors duration-200 shadow-sm shadow-blue-200">
                            Apply Now
                        </a>

                    </div>
                </div>

            @empty
                <div class="col-span-full">
                    <div class="flex flex-col items-center justify-center py-20 text-center text-gray-400">
                        <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-lg font-semibold text-gray-500">No programs found</p>
                        <p class="text-sm mt-1">Try a different search keyword.</p>
                        <a href="{{ route('programs') }}"
                            class="mt-4 inline-block text-sm text-blue-600 hover:underline font-medium">
                            View all programs →
                        </a>
                    </div>
                </div>
            @endforelse

        </div>

        <!-- PAGINATION -->
        @if ($programs->hasPages())
            <div class="mt-10 flex justify-center">
                <nav class="flex items-center gap-1.5" aria-label="Pagination">

                    {{-- Previous --}}
                    @if ($programs->onFirstPage())
                        <span class="px-3 py-2 rounded-lg text-sm text-gray-300 bg-gray-50 cursor-not-allowed select-none border border-gray-100">
                            ← Prev
                        </span>
                    @else
                        <a href="{{ $programs->previousPageUrl() }}"
                            class="px-3 py-2 rounded-lg text-sm text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-600 border border-gray-200 transition-colors font-medium">
                            ← Prev
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($programs->getUrlRange(1, $programs->lastPage()) as $page => $url)
                        @if ($page == $programs->currentPage())
                            <span class="px-3.5 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 border border-blue-600 shadow-sm shadow-blue-200">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3.5 py-2 rounded-lg text-sm text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-600 border border-gray-200 transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($programs->hasMorePages())
                        <a href="{{ $programs->nextPageUrl() }}"
                            class="px-3 py-2 rounded-lg text-sm text-gray-600 bg-white hover:bg-blue-50 hover:text-blue-600 border border-gray-200 transition-colors font-medium">
                            Next →
                        </a>
                    @else
                        <span class="px-3 py-2 rounded-lg text-sm text-gray-300 bg-gray-50 cursor-not-allowed select-none border border-gray-100">
                            Next →
                        </span>
                    @endif

                </nav>
            </div>

            <p class="text-center text-xs text-gray-400 mt-3">
                Showing {{ $programs->firstItem() }}–{{ $programs->lastItem() }} of {{ $programs->total() }} programs
            </p>
        @endif

    </section>

    @include('layouts.footer')

</x-guest-layout>