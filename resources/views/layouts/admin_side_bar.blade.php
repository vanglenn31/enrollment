<div x-data="{ open: false }" class="flex min-h-screen bg-gray-100 overflow-hidden">

    <!-- SIDEBAR -->
    <aside
        :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed lg:static top-0 left-0 z-50 h-[100vh] w-64 flex-shrink-0 bg-first shadow-lg border-r border-white/10
               transform transition-transform duration-300 ease-in-out
               lg:translate-x-0 flex flex-col">

        <!-- CLOSE BUTTON (MOBILE) -->
        <div class="flex items-center justify-end p-3 lg:hidden">
            <button @click="open = false"
                class="text-gray-600 p-2 rounded-md hover:bg-white/20 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- HEADER -->
        <div class="hidden md:block px-4 pt-5 pb-3">
            <div class="bg-white/20 backdrop-blur-sm rounded-xl py-2.5 text-center font-semibold text-sm tracking-wide text-gray-800 shadow-inner">
                🛡️ Admin Portal
            </div>
        </div>

        <!-- DIVIDER -->
        <div class="mx-4 border-t border-white/20 my-1"></div>

        <!-- MENU -->
        <nav class="mt-2 space-y-0.5 px-3 flex-1 overflow-y-auto pb-4">

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'bg-blue-400 text-white font-semibold shadow-sm' : 'text-gray-700 hover:bg-blue-200' }}
               flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-3a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/>
                </svg>
                Dashboard
            </a>

            <!-- Courses -->
            <a href="{{ route('admin.course') }}"
               class="{{ request()->routeIs('admin.course') ? 'bg-blue-400 text-white font-semibold shadow-sm' : 'text-gray-700 hover:bg-blue-200' }}
               flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Courses
            </a>

            <!-- Department -->
            <a href="{{ route('admin.department') }}"
               class="{{ request()->routeIs('admin.department') ? 'bg-blue-400 text-white font-semibold shadow-sm' : 'text-gray-700 hover:bg-blue-200' }}
               flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Department
            </a>

            <!-- Programs -->
            <a href="{{ route('admin.programs') }}"
               class="{{ request()->routeIs('admin.programs') ? 'bg-blue-400 text-white font-semibold shadow-sm' : 'text-gray-700 hover:bg-blue-200' }}
               flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Programs
            </a>

            <!-- Students -->
            <a href="{{ route('admin.students') }}"
               class="{{ request()->routeIs('admin.students') ? 'bg-blue-400 text-white font-semibold shadow-sm' : 'text-gray-700 hover:bg-blue-200' }}
               flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0-6l-3.5-2m3.5 2l3.5-2M3 19h18"/>
                </svg>
                Students
            </a>

            <!-- Enrollment -->
            <a href="{{ route('admin.enrollment') }}"
               class="{{ request()->routeIs('admin.enrollment*') ? 'bg-blue-400 text-white font-semibold shadow-sm' : 'text-gray-700 hover:bg-blue-200' }}
               flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Enrollment
            </a>

            <!-- Professors -->
            <a href="{{ route('admin.professors') }}"
               class="{{ request()->routeIs('admin.professors') ? 'bg-blue-400 text-white font-semibold shadow-sm' : 'text-gray-700 hover:bg-blue-200' }}
               flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Professors
            </a>

            <!-- Rooms -->
            <a href="{{ route('admin.rooms') }}"
               class="{{ request()->routeIs('admin.rooms') ? 'bg-blue-400 text-white font-semibold shadow-sm' : 'text-gray-700 hover:bg-blue-200' }}
               flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
                Rooms
            </a>

            <!-- Terms -->
            <a href="{{ route('admin.terms.index') }}"
               class="{{ request()->routeIs('admin.terms.*') ? 'bg-blue-400 text-white font-semibold shadow-sm' : 'text-gray-700 hover:bg-blue-200' }}
               flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Terms
            </a>

            <!-- Registrar -->
            <a href="{{ route('admin.registrars') }}"
               class="{{ request()->routeIs('admin.registrars') ? 'bg-blue-400 text-white font-semibold shadow-sm' : 'text-gray-700 hover:bg-blue-200' }}
               flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/>
                </svg>
                Registrar
            </a>

            <!-- Payments -->
            <a href="{{ route('admin.payments') }}"
               class="{{ request()->routeIs('admin.payments') ? 'bg-blue-400 text-white font-semibold shadow-sm' : 'text-gray-700 hover:bg-blue-200' }}
               flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Payments
            </a>

            <!-- MOBILE PROFILE & LOGOUT -->
            <form method="POST" action="{{ route('logout') }}" class="md:hidden pt-3 mt-2 border-t border-white/20 space-y-0.5">
                @csrf
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-white/20 transition">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Profile
                </a>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); this.closest('form').submit();"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </a>
            </form>

        </nav>
    </aside>

    <!-- CONTENT -->
    <div class="flex-1 flex flex-col min-h-screen">

        <!-- MOBILE TOP BAR -->
        <div class="lg:hidden p-3 bg-first shadow flex justify-between items-center">
            <button @click="open = !open" class="p-2 rounded-md hover:bg-white/20 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto w-full">
                @yield('content')
            </div>
        </main>

    </div>
</div>