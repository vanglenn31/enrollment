<nav x-data="{ open: false }"
    class="sticky top-0 z-50 w-full h-16 sm:h-16 lg:h-20 bg-first shadow-md px-3 sm:px-4 lg:px-8">

    <div class="w-full mx-auto h-full flex items-center justify-between gap-2">

        <!-- LEFT: Branding -->
        <div class="flex-1 flex items-center gap-3">
            <!-- Logo mark -->
            <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="UIM logo" class="w-full h-full">
            </div>
            <div>
                <h1 class="text-gray-900 font-bold text-sm sm:text-base lg:text-lg leading-tight">
                    University of International Mindanao
                </h1>
                <p class="text-gray-600 text-[10px] sm:text-xs">
                    Enrollment Management System
                </p>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="flex items-center gap-2 sm:gap-3">

            <!-- Bell Notification -->
            <button class="hidden sm:flex p-2 rounded-full hover:bg-white/20 transition relative">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0"/>
                </svg>
            </button>

            <!-- Profile Dropdown (Desktop) -->
            <div class="hidden sm:flex items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2.5 px-2 sm:px-3 py-1.5 rounded-xl bg-white/20 hover:bg-white/30 transition border border-white/20">

                            {{-- User Initials Avatar --}}
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-gray-700 flex items-center justify-center flex-shrink-0 text-white font-bold text-sm select-none">
                                @if(Auth::user()->role?->role === 'admin')
                                    AD
                                @else
                                    {{ strtoupper(substr(Auth::user()->profile?->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->profile?->last_name ?? '', 0, 1)) }}
                                @endif
                            </div>

                            <span class="hidden md:block text-sm font-medium text-gray-800 max-w-[120px] truncate">
                                @if(Auth::user()->role?->role === 'admin')
                                    Admin
                                @else
                                    {{ Auth::user()->profile?->first_name }} {{ Auth::user()->profile?->last_name }}
                                @endif
                            </span>

                            <svg class="h-4 w-4 text-gray-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- Mini user card at top of dropdown --}}
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-full bg-gray-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                    @if(Auth::user()->role?->role === 'admin')
                                        AD
                                    @else
                                        {{ strtoupper(substr(Auth::user()->profile?->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->profile?->last_name ?? '', 0, 1)) }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">
                                        @if(Auth::user()->role?->role === 'admin')
                                            Admin
                                        @else
                                            {{ Auth::user()->profile?->first_name }} {{ Auth::user()->profile?->last_name }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Profile
                            </div>
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <div class="flex items-center gap-2 text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Log Out
                                </div>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="sm:hidden">
                <button @click="open = !open"
                    class="p-2 rounded-md hover:bg-white/20 transition">
                    <svg class="h-6 w-6 text-gray-800" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open}" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open}" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- MOBILE DROPDOWN MENU -->
    <div x-show="open" x-transition
        class="sm:hidden px-3 pb-3 space-y-1 bg-white shadow-lg rounded-b-xl border-t border-gray-100">

        @php $role = auth()->user()->role->role; @endphp

        {{-- Mobile user card --}}
        <div class="flex items-center gap-3 px-3 py-3 border-b border-gray-100 mb-1">
            <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                @if($role === 'admin')
                    AD
                @else
                    {{ strtoupper(substr(Auth::user()->profile?->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->profile?->last_name ?? '', 0, 1)) }}
                @endif
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate">
                    @if($role === 'admin') Admin
                    @else {{ Auth::user()->profile?->first_name }} {{ Auth::user()->profile?->last_name }}
                    @endif
                </p>
                <p class="text-xs text-gray-400 capitalize">{{ $role }}</p>
            </div>
        </div>

        @if($role === 'admin')
            <x-responsive-nav-link :href="route('admin.dashboard')">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.course')">Courses</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.department.department')">Department</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.programs.programs')">Programs</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.students')">Students</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.enrollment')">Enrollment</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.professors')">Professors</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.registrars')">Registrar</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.payments')">Payments</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.rooms')">Rooms</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.terms.index')">Terms</x-responsive-nav-link>
        @endif

        <div class="border-t border-gray-100 pt-1 mt-1">
            <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    Log Out
                </x-responsive-nav-link>
            </form>
        </div>

    </div>

</nav>