<x-guest-layout>

    @include('layouts.landing_nav')

    <div class="relative h-full w-full">
        <img src="{{ Vite::asset('resources/images/index-hero-pic.jpg') }}" alt="student image" class="w-full">
        <div class="absolute inset-0 w-full bg-black/40">
            <div class="h-full w-1/2 flex flex-col justify-center items-center ml-auto">
                <h1 class="w-3/4 text-white text-[2.5rem] font-bold ">Fostering Growth, Encouraging Curiosity, and Celebrating Achievement.</h1>
                <p class=" w-3/4 mt-4 text-white text-justify text-[1.5rem] text-white-300">Welcome to UIM - University of International Mindandao, Davao</p>
            </div>
            
        </div>
    </div>
    

    </x-guest-layout>