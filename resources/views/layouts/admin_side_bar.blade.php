<div x-data="{ open: false }" class="flex min-h-screen bg-gray-100 overflow-hidden"> 

    <!-- SIDEBAR -->
    <aside 
        :class="open ? 'translate-x-0' : '-translate-x-full'"
    class="fixed lg:static top-0 left-0 z-50 h-[100vh] w-64 flex-shrink-0 bg-first shadow-md border-r border-gray-200
           transform transition-transform duration-300 ease-in-out
           lg:translate-x-0 flex flex-col">

        <!-- CLOSE BUTTON (MOBILE) -->
        <div class="flex items-center justify-end p-3 lg:hidden">
            <button 
                @click="open = false"
                class="text-xl font-bold p-2 rounded-md hover:bg-gray-200 transition">
                ✕
            </button>
        </div>

        <!-- HEADER -->
        <div class="hidden md:block px-4 pb-2">
            <div class="bg-gray-300 rounded-lg py-2 text-center font-semibold">
                Admin Portal
            </div>
        </div>

        <!-- MENU -->
        <nav class="mt-4 space-y-1 px-3 flex-1 overflow-y-auto">

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="{{ request()->routeIs('admin.dashboard') ? 'bg-sky-200' : '' }}
               flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M3 13h8V3H3v10zM13 21h8v-6h-8v6zM13 3v6h8V3h-8zM3 21h8v-4H3v4z"/>
                </svg>
                Dashboard
            </a>

            <!-- Courses -->
            <a href="{{ route('admin.course') }}" 
               class="{{ request()->routeIs('admin.course') ? 'bg-sky-200' : '' }}
               flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M12 20l9-5-9-5-9 5 9 5z"/>
                </svg>
                Courses
            </a>

            <!-- Department -->
            <a href="{{ route('admin.department') }}" 
               class="{{ request()->routeIs('admin.department') ? 'bg-sky-200' : '' }}
               flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium transition">
                <span class="text-lg font-bold">D</span>
                Department
            </a>

            <!-- Programs -->
            <a href="{{ route('admin.programs') }}" 
               class="{{ request()->routeIs('admin.programs') ? 'bg-sky-200' : '' }}
               flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium transition">
                <span class="text-lg font-bold">P</span>
                Programs
            </a>

            <!-- Students -->
            <a href="{{ route('admin.students') }}" 
               class="{{ request()->routeIs('admin.students') ? 'bg-sky-200' : '' }}
               flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium transition">
                <span class="text-lg font-bold">S</span>
                Students
            </a>

            <!-- Enrollment -->
            <a href="{{ route('admin.enrollment') }}" 
               class="{{ request()->routeIs('admin.enrollment*') ? 'bg-sky-200' : '' }}
               flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Enrollment
            </a>

            <!-- Professors -->
            <a href="{{ route('admin.professors') }}" 
               class="{{ request()->routeIs('admin.professors') ? 'bg-sky-200' : '' }}
               flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium transition">
                <span class="text-lg font-bold">F</span>
                Professors
            </a>

            <a href="{{ route('admin.rooms') }}" 
               class="{{ request()->routeIs('admin.rooms') ? 'bg-sky-200' : '' }}
               flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium transition">
                <span class="text-lg font-bold">R</span>
                Rooms
            </a>

            <!-- Registrar -->
            <a href="{{ route('admin.registrars') }}" 
               class="{{ request()->routeIs('admin.registrars') ? 'bg-sky-200' : '' }}
               flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium transition">
                <span class="text-lg font-bold">R</span>
                Registrar
            </a>

            <!-- Payment -->
            <a href="{{ route('admin.payments') }}" 
               class="{{ request()->routeIs('admin.payments') ? 'bg-sky-200' : '' }}
               flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium transition">
                <span class="text-lg font-bold">₱</span>
                Payments
            </a>

            <!-- MOBILE PROFILE -->
            <form method="POST" action="{{ route('logout') }}" class="md:hidden pt-4 border-t">
                @csrf 

                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                    Profile
                </a>

                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); this.closest('form').submit();"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                    Logout
                </a>
            </form>

        </nav>
    </aside>

    <!-- CONTENT -->
    <div class="flex-1 flex flex-col lg:ml-64 min-h-screen">

        <!-- MOBILE TOP BAR -->
        <div class="lg:hidden p-3 bg-first shadow flex justify-between items-center">
            <button @click="open = !open" class="p-2 text-xl">
                ☰
            </button>   
        </div>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto w-full">
                <!-- Your content here -->
            </div>
        </main>

    </div>
</div>