<nav x-data="{ open: false }"
    class="sticky top-0 z-50 w-full h-16 sm:h-16 lg:h-20 xl:h-24 bg-first shadow-md px-3 sm:px-4 lg:px-8">

    <div class="w-full mx-auto h-full flex items-center justify-between gap-2">

        <!-- LEFT: Search -->
        <div class="flex-1 flex items-center">
            <div>
                <h1 class="text-black font-bold text-sm sm:text-base lg:text-lg xl:text-xl leading-tight">
                    University of International Mindanao
                </h1>
                <p class="text-white/70 text-[10px] sm:text-xs">
                    Enrollment Management System
                </p>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">

            <!-- Bell -->
            <button class="hidden sm:flex p-2 rounded-full hover:bg-gray-100 transition">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-7 lg:h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0"/>
                </svg>
            </button>

            <!-- Profile Dropdown -->
            <div class="hidden sm:flex items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-2 sm:px-3 py-1.5 sm:py-2 rounded-md bg-white hover:bg-gray-100 transition">

                            <img src="/profile.jpg"
                                 class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover"
                                 alt="Profile Picture">

                            <span class="hidden md:block text-sm lg:text-base font-medium">
                                @if(Auth::user()->role?->role === 'admin')
                                    admin
                                @else
                                    {{ Auth::user()->profile?->last_name }}
                                @endif
                            </span>

                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4-4-4a1 1 0 010-1.414z"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="sm:hidden">
                <button @click="open = !open"
                    class="p-2 rounded-md hover:bg-gray-100 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open}" class="inline-flex"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open}" class="hidden"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- MOBILE DROPDOWN -->
    <div x-show="open" x-transition
     class="sm:hidden px-3 pb-3 space-y-2 bg-white shadow-md rounded-b-lg">

    <!-- NAVIGATION LINKS BASED ON ROLE -->
    @php
        $role = auth()->user()->role->role;
    @endphp

    @if($role === 'admin')
        <x-responsive-nav-link :href="route('admin.dashboard')">
            Dashboard
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.course')">
            Courses
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.department')">
            Department
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.programs')">
            Programs
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.students')">
            Students
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.enrollment')">
            Enrollment
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.professors')">
            Professors
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.registrars')">
            Registrar
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('admin.payments')">
            Payments
        </x-responsive-nav-link>
        
        <x-responsive-nav-link :href="route('admin.rooms')">
            Rooms
        </x-responsive-nav-link>
    @endif


    <!-- PROFILE -->
    <x-responsive-nav-link :href="route('profile.edit')">
        Profile
    </x-responsive-nav-link>

    <!-- LOGOUT -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <x-responsive-nav-link :href="route('logout')"
            onclick="event.preventDefault(); this.closest('form').submit();">
            Log Out
        </x-responsive-nav-link>
    </form>

</div>

</nav>