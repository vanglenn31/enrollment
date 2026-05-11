<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap');

    .nav-root {
        font-family: 'DM Sans', sans-serif;
    }
    .nav-brand-title {
        font-family: 'Playfair Display', serif;
    }

    /* Scroll-aware nav */
    .nav-root {
        transition: background 0.3s ease, box-shadow 0.3s ease, height 0.3s ease;
    }
    .nav-root.scrolled {
        background: rgba(255,255,255,0.96) !important;
        backdrop-filter: blur(12px);
        box-shadow: 0 2px 24px 0 rgba(15,40,90,0.10);
        height: 4.5rem !important;
    }

    /* Nav link underline animation */
    .nav-link {
        position: relative;
        color: #1e3a5f;
        font-weight: 500;
        font-size: 0.95rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        transition: color 0.2s, background 0.2s;
        letter-spacing: 0.01em;
    }
    .nav-link::after {
        content: '';
        position: absolute;
        left: 50%;
        bottom: -2px;
        transform: translateX(-50%) scaleX(0);
        width: 60%;
        height: 2px;
        background: #1d6fba;
        border-radius: 2px;
        transition: transform 0.25s cubic-bezier(.4,0,.2,1);
    }
    .nav-link:hover::after,
    .nav-link.active::after {
        transform: translateX(-50%) scaleX(1);
    }
    .nav-link.active {
        color: #1d6fba;
        background: #e8f1fb;
    }
    .nav-link:hover {
        color: #1d6fba;
        background: #f0f6ff;
    }

    /* Sidebar backdrop */
    #nav-overlay {
        backdrop-filter: blur(3px);
        background: rgba(10, 25, 55, 0.45);
    }

    /* Sidebar link */
    .sidebar-link {
        font-family: 'DM Sans', sans-serif;
        font-size: 1rem;
        font-weight: 500;
        color: #1e3a5f;
        padding: 0.65rem 1.5rem;
        border-radius: 0.625rem;
        transition: background 0.2s, color 0.2s, transform 0.15s;
        display: block;
    }
    .sidebar-link:hover {
        background: #e8f1fb;
        color: #1d6fba;
        transform: translateX(4px);
    }
    .sidebar-link.active {
        background: #dbeafe;
        color: #1d4ed8;
        font-weight: 600;
    }

    /* Button styles */
    .btn-login {
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.5rem 1.25rem;
        border-radius: 0.5rem;
        border: 1.5px solid #1d6fba;
        color: #1d6fba;
        transition: background 0.2s, color 0.2s;
        white-space: nowrap;
    }
    .btn-login:hover {
        background: #e8f1fb;
    }
    .btn-enroll {
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.5rem 1.25rem;
        border-radius: 0.5rem;
        background: linear-gradient(135deg, #1d6fba 0%, #1a4f8a 100%);
        color: #fff;
        box-shadow: 0 2px 12px 0 rgba(29,111,186,0.25);
        transition: box-shadow 0.2s, transform 0.15s, background 0.2s;
        white-space: nowrap;
    }
    .btn-enroll:hover {
        background: linear-gradient(135deg, #1a5fa8 0%, #163e72 100%);
        box-shadow: 0 4px 18px 0 rgba(29,111,186,0.35);
        transform: translateY(-1px);
    }
</style>

<nav class="nav-root sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm"
     style="height: 5rem; display: flex; align-items: center;">
    <div class="w-full max-w-7xl mx-auto px-5 lg:px-8 flex items-center justify-between">

        <!-- Brand -->
        <a href="{{ route('index') }}" class="flex items-center gap-3 shrink-0">
            <div class="relative">
                <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="UIM logo"
                     class="w-12 h-12 object-contain drop-shadow-sm">
            </div>
            <div class="hidden sm:block leading-tight">
                <p class="nav-brand-title text-lg font-bold text-[#1a3d6e] tracking-tight leading-none">UIM OES</p>
                <p class="text-[0.7rem] text-gray-500 font-medium tracking-wide leading-none mt-0.5">University of International Mindanao</p>
            </div>
        </a>

        <!-- Desktop Nav Links -->
        <ul class="hidden lg:flex items-center gap-1">
            <li>
                <a href="{{ route('index') }}"
                   class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}">
                    Home
                </a>
            </li>
            <li>
                <a href="{{ route('programs') }}"
                   class="nav-link {{ request()->routeIs('programs') ? 'active' : '' }}">
                    Programs
                </a>
            </li>
            <li>
                <a href="{{ route('admission') }}"
                   class="nav-link {{ request()->routeIs('admission') ? 'active' : '' }}">
                    Admissions
                </a>
            </li>
        </ul>

        <!-- Desktop CTA Buttons -->
        <div class="hidden lg:flex items-center gap-3">
            <a href="{{ route('login') }}" class="btn-login">Login</a>
            <a href="{{ route('register') }}" class="btn-enroll">Enroll Now</a>
        </div>

        <!-- Hamburger (mobile) -->
        <button id="menu-btn" aria-label="Open menu"
                class="lg:hidden flex flex-col justify-center items-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors gap-1.5 group">
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
</nav>

<!-- Overlay -->
<div id="nav-overlay" class="hidden fixed inset-0 z-[998] transition-opacity duration-300"></div>

<!-- Mobile Sidebar -->
<div id="sidebar"
     class="fixed top-0 right-0 h-full w-72 bg-white z-[999] shadow-2xl
            translate-x-full transition-transform duration-300 ease-in-out
            flex flex-col">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-5 py-5 border-b border-gray-100">
        <div class="flex items-center gap-2.5">
            <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="UIM logo" class="w-9 h-9 object-contain">
            <div>
                <p class="nav-brand-title text-base font-bold text-[#1a3d6e] leading-none">UIM OES</p>
                <p class="text-[0.65rem] text-gray-400 leading-none mt-0.5">Univ. of International Mindanao</p>
            </div>
        </div>
        <button id="close-btn" aria-label="Close menu"
                class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 text-gray-500 hover:text-gray-800 transition-colors text-xl font-light">
            ✕
        </button>
    </div>

    <!-- Sidebar Links -->
    <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
        <a href="{{ route('index') }}" class="sidebar-link {{ request()->routeIs('index') ? 'active' : '' }}">
            <span class="flex items-center gap-3">
                <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Home
            </span>
        </a>
        <a href="{{ route('programs') }}" class="sidebar-link {{ request()->routeIs('programs') ? 'active' : '' }}">
            <span class="flex items-center gap-3">
                <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Programs
            </span>
        </a>
        <a href="{{ route('admission') }}" class="sidebar-link {{ request()->routeIs('admission') ? 'active' : '' }}">
            <span class="flex items-center gap-3">
                <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Admissions
            </span>
        </a>
    </nav>

    <!-- Sidebar Footer CTAs -->
    <div class="px-4 py-5 border-t border-gray-100 space-y-2.5">
        <a href="{{ route('login') }}"
           class="block text-center btn-login w-full">Login</a>
        <a href="{{ route('register') }}"
           class="block text-center btn-enroll w-full">Enroll Now</a>
    </div>
</div>

<script>
    const menuBtn = document.getElementById('menu-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('nav-overlay');
    const closeBtn = document.getElementById('close-btn');
    const navRoot = document.querySelector('.nav-root');

    function openMenu() {
        sidebar.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeMenu() {
        sidebar.classList.add('translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    menuBtn.addEventListener('click', openMenu);
    closeBtn.addEventListener('click', closeMenu);
    overlay.addEventListener('click', closeMenu);

    // Scroll-aware navbar
    window.addEventListener('scroll', () => {
        navRoot.classList.toggle('scrolled', window.scrollY > 20);
    });
</script>