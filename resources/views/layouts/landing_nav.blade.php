<nav class="sticky top-0 lg:h-24 xl:h-28 bg-first flex justify-between lg:justify-around items-center font-semibold shadow-xl px-10 lg:px-5 z-50 ">
    <div class="flex justify-center items-center ">
        <div >
            <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="UIM logo" class="w-[4rem] h-full]">
        </div>
        <div class="hidden md:block">
            <h3 class="sm:text-md lg:text-xl md:text:xlg">UIM OES</h3>
            <p class="sm:text-[1rem] lg:text-md">University of International Mindandao</p>
        </div>

    </div>
    <button id="menu-btn" class="lg:hidden text-black flex justify-center items-center " >
            <img src="{{ Vite::asset('resources/images/hamburger.svg') }}" alt="humburger icon" class="w-[2rem] h-full hover:opacity-60 outline-2">
        </button>
    <div class="hidden lg:block flex flex-row items-center md:text-lg lg:text-xl xl:text-xl 2xl:4xl "> 
        <ul class="w-full grid grid-cols-4 justify-items-center gap-2">
            <li class="grid place-items-center"><a href="{{ route('index') }}" class="{{ request()->routeIs('index') ? 'bg-sky-200' : ''  }} hover:bg-gray-300  w-full py-2 px-4 rounded-xl active:bg-sky-400 transition-colors duration-200 ease-in">Home</a></li>
            <li class="grid place-items-center"><a href="{{ route('programs') }}" class="{{ request()->routeIs('programs') ? 'bg-sky-200' : 'text-black'  }} hover:bg-gray-300 w-full py-2 px-4 rounded-xl active:bg-sky-100 transition-colors duration-200 ease-in">Programs</a></li>
            <li class="grid place-items-center"><a href="{{ route('admission') }}" class="{{ request()->routeIs('admission') ? 'bg-sky-100' : 'text-black '  }} hover:bg-gray-300 w-full py-2 px-4 rounded-xl active:bg-sky-100 transition-colors duration-200 ease-in">Admissions</a></li>
            <li class="grid place-items-center"><a href="{{ route('FAQ') }}" class="{{ request()->routeIs('FAQ') ? 'bg-sky-100' : 'text-black'  }} hover:hover:bg-gray-300 w-full py-2 px-4 rounded-xl active:bg-sky-100 duration-200 ease-in">FAQ</a></li>
        </ul>
    </div>
    <div class="hidden lg:block flex flex-row items-end md:text-lg lg:text:xl text-nowrap">
        <ul class="w-full flex flex-row gap-10">
            <li><a href="{{ route('login') }}" class="hover:bg-sky-400 hover:text-first w-full  py-2 px-4 rounded-xl">Login</a></li>
            <li><a href="{{ route('register') }}" class="bg-sky-800 hover:bg-sky-1000 text-first w-full  py-2 px-4 rounded-xl">Enroll Now</a></li>
        </ul>
    </div>    
</nav>
<div id="overlay" class="hidden fixed inset-0 bg-black-100 z-40"></div>

<div id="sidebar"
     class="fixed top-0 right-0 h-full w-64 bg-first text-white shadow-xl z-[999]
            translate-x-full transition-transform duration-400 ease-in">

    <!-- Close button -->
    <div class="flex justify-end p-4">
        <button id="close-btn" class="text-3xl text-black mr-auto">x</button>
    </div>

    <!-- Navigation -->
    <div class="flex flex-col gap-4 mt-4 text-black text-center">

        <a href="{{ route('index') }}" class="py-2 hover:bg-blue-100 rounded">
            Home
        </a>

        <a href="{{ route('programs') }}" class="py-2 hover:bg-blue-100 rounded">
            Programs
        </a>

        <a href="{{ route('admission') }}" class="py-2 hover:bg-blue-100 rounded">
            Admissions
        </a>

        <a href="{{ route('FAQ') }}" class="py-2 hover:bg-blue-100 rounded">
            FAQ
        </a>

        <hr class="opacity-30">

        <a href="{{ route('login') }}" class="py-2 hover:bg-blue-100 rounded">
            Login
        </a>

        <a href="{{ route('register') }}" class="py-2 hover:bg-blue-100 rounded">
            Enroll Now
        </a>

    </div>
</div>

 

<script>
    const btn = document.getElementById('menu-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const closeBtn = document.getElementById('close-btn');

    function openMenu() {
        sidebar.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
    }

    function closeMenu() {
        sidebar.classList.add('translate-x-full');
        overlay.classList.add('hidden');
    }

        btn.addEventListener('click', openMenu);
        closeBtn.addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu);
</script>