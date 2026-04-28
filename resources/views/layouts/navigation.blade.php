<nav x-data="{ open: false }"
    class="sticky top-0 z-50 w-full ml-auto h-16 lg:h-24 xl:h-28 bg-first shadow-xl px-4 sm:px-6 lg:px-10">

    <div class="w-full mx-auto h-full flex items-center justify-between">

        <!-- LEFT: Search -->
        <div class="flex-1 max-w-xs sm:max-w-sm md:max-w-md">
            <div class="flex items-center bg-gray-100 rounded-lg px-3 py-2">
                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/>
                </svg>
                <input type="text" placeholder="Search here"
                    class="bg-transparent outline-none w-full text-sm">
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class=" flex items-center space-x-3 sm:space-x-4">

            <!-- Bell -->
            <button class="hidden md:block p-2 rounded-full hover:bg-gray-100">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0"/>
                </svg>
            </button>

            <!-- Profile Dropdown -->
            <div class="hidden md:block sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-3 py-2 rounded-md bg-white hover:bg-gray-100 transition">
                            <img src="/profile.jpg" class=" hidden md:block w-12 h-12 rounded-full pr-6" alt="Profile Picture" >

                            <span class="text-lg font-medium">
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
            <div class="hidden">
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
    <div x-show="open" class="sm:hidden px-4 pb-4 space-y-2">

        <x-responsive-nav-link :href="route('profile.edit')">
            Profile
        </x-responsive-nav-link>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-responsive-nav-link :href="route('logout')"
                onclick="event.preventDefault(); this.closest('form').submit();">
                Log Out
            </x-responsive-nav-link>
        </form>
    </div>

</nav>