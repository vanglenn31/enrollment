<nav class="sticky top-0 h-24 bg-first grid grid-cols-3 items-center font-semibold z-50 shadow-xl">
    <div class="flex justify-center items-center ">
        <div>
            <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="UIM logo" class="w-[6rem] h-[6rem]">
        </div>
        <div>
            <h3 class="text-lg">UIM OES</h3>
            <p class="text-sm">University of International Mindandao</p>
        </div>

    </div>
    <div class="flex flex-row items-center text-lg"> 
        <ul class="w-full grid grid-cols-4 justify-items-center gap-2">
            <li class="hover:bg-sky-200 w-full grid place-items-center py-2 rounded-xl"><a href="{{ route('index') }}">Home</a></li>
            <li class="hover:bg-sky-200 w-full grid place-items-center py-2 rounded-xl"><a href="{{ route('programs') }}">Programs</a></li>
            <li class="hover:bg-sky-200 w-full grid place-items-center py-2 rounded-xl"><a href="{{ route('admission') }}">Admissions</a></li>
            <li class="hover:bg-sky-200 w-full grid place-items-center py-2 rounded-xl"><a href="{{ route('FAQ') }}">FAQ</a></li>
        </ul>
    </div>
    <div class="flex flex-row items-center text-lg">
        <ul class="w-full flex flex-row justify-center gap-20 pe-5">
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Enroll Now</a></li>
        </ul>
    </div>
    
 </nav>