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
                Student Portal
            </div>
        </div>

        <!-- MENU -->
        <nav class="mt-4 space-y-2 px-3">

            <!-- Dashboard -->
            <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'bg-sky-200' : ''  }} flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200 text-gray-800 font-medium">
                <!-- Icon -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M3 13h8V3H3v10zM13 21h8v-6h-8v6zM13 3v6h8V3h-8zM3 21h8v-4H3v4z"/>
                </svg>
                Dashboard
            </a>

            <!-- Course -->
            <a href="{{ route('student.course') }}" class="{{ request()->routeIs('student.course') ? 'bg-sky-200' : ''  }} flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M12 20l9-5-9-5-9 5 9 5z"/>
                </svg>
                Course
            </a>

            <!-- Enrollment -->
            <a href="{{ route('student.enrollment') }}" class="{{ request()->routeIs('student.enrollment') ? 'bg-sky-200' : ''  }} flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M9 12h6M9 16h6M9 8h6M5 4h14v16H5z"/>
                </svg>
                Enrollment
            </a>

            <!-- Payment (₱ FIXED HERE) -->
            <a href="{{ route('student.payment') }}" class="{{ request()->routeIs('student.payment') ? 'bg-sky-200' : ''  }} flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200">
                <span class="text-lg font-bold">₱</span>
                Payment
            </a>

           
            <form method="POST" action="{{ route('logout') }}" class="md:hidden">
                @csrf 
                <a href="{{ route('profile.edit') }}" class=" flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200">
                    <span class="text-lg font-bold"></span>
                    Profile
                </a>

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-200">

                    <span class="text-lg font-bold"></span>
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
