<div x-data="{ open: false }" class="flex overflow-x-hidden "> 

    <!-- SIDEBAR -->
    <aside 
        :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed bg-first lg:static z-50 top-0 left-0 h-screen w-64 shadow-md transform lg:translate-x-0 transition-transform duration-200 border-r border-gray-200">
        <div class=" top-3 flex items-center justify-end px-4 lg:hidden">
            <button 
                @click="open = false"
                class="text-xl font-bold p-2 rounded-md hover:bg-gray-200">
                ✕
            </button>
         </div>
        <!-- HEADER -->
        <div class=" hidden md:block p-4">
            <div class=" bg-gray-300 rounded-lg py-2 text-center font-semibold">
                Professor Portal
            </div>
        </div>

        <!-- MENU -->
        <nav class="mt-4 space-y-2 px-3">

            <!-- Dashboard -->
            <a href="{{ route('professor.dashboard') }}" class="{{ request()->routeIs('professor.dashboard') ? 'bg-sky-200' : ''  }} flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium">
                <span class="text-lg font-bold">D</span>
                Dashboard
            </a>

            <!-- My Classes -->
            <a href="{{ route('professor.course') }}" class="{{ request()->routeIs('professor.course') ? 'bg-sky-200' : ''  }} flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium">
                <span class="text-lg font-bold">C</span>
                My Classes
            </a>

            <!-- Profile -->
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium">
                <span class="text-lg font-bold">P</span>
                Profile
            </a>

            <form method="POST" action="{{ route('logout') }}" class="md:hidden">
                @csrf 
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium">
                    <span class="text-lg font-bold">L</span>
                    Logout
                </a>
            </form>
        </nav>
    </aside>

    <!-- CONTENT -->
    <div class="flex-1 lg:ml-64">

        <!-- MOBILE TOP BAR -->
        <div class="lg:hidden p-3 bg-first w-full shadow flex justify-between items-center">
            <button @click="open = !open" class="p-2">
                ☰
            </button>   
        </div>

        <!-- MAIN CONTENT -->
        
    </div>
</div>
