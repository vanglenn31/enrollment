<div x-data="{ open: false }" class="flex overflow-x-hidden">

    <!-- SIDEBAR -->
    <aside
        :class="open ? 'translate-x-0' : '-translate-x-full'"
        class=" fixed bg-white lg:static z-50 top-0 left-0 h-screen w-64 shadow-md transform lg:translate-x-0 transition-transform duration-200 border-r border-gray-200 ">

        <!-- Mobile close button -->
        <div class="top-3 flex items-center justify-end px-4 lg:hidden pt-3">
            <button @click="open = false" class="text-xl font-bold p-2 rounded-md hover:bg-gray-100">✕</button>
        </div>

        <!-- HEADER -->
        <div class="hidden md:block p-4">
            <div class="bg-gray-100 rounded-xl py-2.5 text-center font-semibold text-gray-700 text-sm tracking-wide">
                Student Portal
            </div>
        </div>

        <!-- MENU -->
        <nav class="mt-2 space-y-1 px-3">

            @php
                $navItems = [
                    [
                        'route' => 'student.dashboard',
                        'label' => 'Dashboard',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 13h8V3H3v10zM13 21h8v-6h-8v6zM13 3v6h8V3h-8zM3 21h8v-4H3v4z"/>',
                    ],
                    [
                        'route' => 'student.enrollment',
                        'label' => 'Enrollment',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6M9 16h6M9 8h6M5 4h14v16H5z"/>',
                    ],
                    [
                        'route' => 'student.course',
                        'label' => 'Enlistment',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 20l9-5-9-5-9 5 9 5z"/>',
                    ],
                    [
                        'route' => 'student.my-courses',
                        'label' => 'My Courses',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.63 48.63 0 0 1 12 20.904a48.63 48.63 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>',
                    ],
                    
                    [
                        'route' => 'student.payment',
                        'label' => 'Payments',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>',
                    ],
                ];
            @endphp

            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs($item['route'])
                               ? 'bg-sky-100 text-sky-800'
                               : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $item['icon'] !!}
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach

            <!-- Divider -->
            <div class="pt-2 border-t border-gray-100 mt-2"></div>

            <!-- Mobile-only: Profile & Logout -->
            <form method="POST" action="{{ route('logout') }}" class="md:hidden space-y-1">
                @csrf
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                    Profile
                </a>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); this.closest('form').submit();"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                    </svg>
                    Logout
                </a>
            </form>

        </nav>
    </aside>

    <!-- CONTENT -->
    <div class="flex-1 lg:ml-64">
        <!-- MOBILE TOP BAR -->
        <div class="lg:hidden p-3 bg-white w-full shadow flex justify-between items-center border-b border-gray-200">
            <button @click="open = !open" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        <!-- MAIN CONTENT -->
    </div>
</div>