<x-guest-layout class="">

    @include('layouts.landing_nav')

    <div class=" relative h-full w-full bg-blue-500">
        <img src="{{ Vite::asset('resources/images/index-hero-pic.jpg') }}" alt="student image" class="w-full invisible sm:visible">
        <div class="absolute inset-0 w-full bg-black/60 lg:bg-black/40">
            <div class="h-full w-5/6 lg:w-1/2 flex flex-col justify-center items-center text-center place-self-center lg:ms-auto gap-6">
                <h1 class="lg:w-3/4 xl:w-5/6 text-white text-lg md:text-xl lg:text-2xl xl:text-4xl font-bold tracking-wider leading-tight">Fostering Growth, Encouraging Curiosity, and Celebrating Achievement.</h1>
                <p class=" lg:w-3/4  text-white text-center text-[0.7rem] md:text-lg lg:text-xl leading-relaxed ">Welcome to UIM - University of International Mindandao, Davao</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-[repeat(auto-fit,minmax(22rem,1fr))] gap-6 items-stretch justify-items-center mx-auto max-w-6xl mt-6 text-center"  >
        <h3 class="text-base md:text-lg lg:text-xl xl:text-2xl font-bold col-span-full">Why Choose University of International Mindanao</h3>
            <div class="min-h-8 max-h-[16rem] bg-slate-50 shadow-md shadow-gray-500 mx-5 rounded-md p-6">
                         
                <svg viewBox="0 0 1024 1024" fill="currentColor" class="icon w-10 h-fit bg-blue-500 rounded-full p-2 text-gray-50 m-auto shadow-lg " version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M678.584675 765.172506v157.995691l75.697852 31.505938V723.768586a429.379161 429.379161 0 0 1-75.697852 41.40392zM269.717473 723.768586V953.098138l75.697852-31.505938v-156.419694a429.309162 429.309162 0 0 1-75.697852-41.40392zM511.999 798.78444a428.955162 428.955162 0 0 1-105.993793-13.241974v238.457534L511.999 979.886086 617.992793 1023.998V785.542466A429.025162 429.025162 0 0 1 511.999 798.78444zM511.999 0C308.479398 0 142.903721 165.575677 142.903721 369.097279S308.479398 738.192558 511.999 738.192558s369.097279-165.575677 369.097279-369.097279S715.520602 0 511.999 0z m0 660.198711c-161.345685 0-292.611428-131.265744-292.611428-292.611429 0-161.347685 131.265744-292.613428 292.611428-292.613428s292.611428 131.265744 292.611428 292.613428c0 161.347685-131.263744 292.611428-292.611428 292.611429zM511.999 135.563735c-127.93575 0-232.021547 104.083797-232.021547 232.023547S384.06325 599.606829 511.999 599.606829s232.021547-104.083797 232.021547-232.021547c0-127.93775-104.083797-232.021547-232.021547-232.021547zM607.360814 502.999018L511.999 452.865115 416.639186 502.999018l18.211965-106.183793-77.14785-75.199853 106.617792-15.49397L511.999 209.509591l47.679907 96.611811 106.617792 15.49397-77.14785 75.199853 18.211965 106.183793z"></path></g></svg>
                <h5 class="text-lg font-semibold">Quility Education</h5>
                <p class="text-sm text-justify">UIM School provides quality education that helps students develop the knowledge and skills needed for academic and professional growth.</p>
            </div>
            <div class="min-h-8 max-h-[16rem] bg-slate-50 shadow-md shadow-gray-500 mx-5 rounded-md p-6 space-y-2">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-10 h-fit bg-blue-500 rounded-full p-2 text-gray-50 m-auto shadow-lg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M10 16V14.0003M10 14.0003V12M10 14.0003L12 14.0005M10 14.0003L8 14M21 12V11.2C21 10.0799 21 9.51984 20.782 9.09202C20.5903 8.71569 20.2843 8.40973 19.908 8.21799C19.4802 8 18.9201 8 17.8 8H3M21 12V16M21 12H19C17.8954 12 17 12.8954 17 14C17 15.1046 17.8954 16 19 16H21M21 16V16.8C21 17.9201 21 18.4802 20.782 18.908C20.5903 19.2843 20.2843 19.5903 19.908 19.782C19.4802 20 18.9201 20 17.8 20H6.2C5.0799 20 4.51984 20 4.09202 19.782C3.71569 19.5903 3.40973 19.2843 3.21799 18.908C3 18.4802 3 17.9201 3 16.8V8M18 8V7.2C18 6.0799 18 5.51984 17.782 5.09202C17.5903 4.71569 17.2843 4.40973 16.908 4.21799C16.4802 4 15.9201 4 14.8 4H6.2C5.07989 4 4.51984 4 4.09202 4.21799C3.71569 4.40973 3.40973 4.71569 3.21799 5.09202C3 5.51984 3 6.0799 3 7.2V8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                <h5>Affordable Tuition</h5>
                <p class="text-sm text-justify">UIM School provides quality education that helps students develop the knowledge and skills needed for academic and professional growth.</p>
            </div>
            <div class="min-h-8 max-h-[16rem] bg-slate-50 shadow-md shadow-gray-500 mx-5 rounded-md p-6 space-y-2">
                <svg height="200px" width="200px" version="1.1" id="Icons" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve" fill="currentColor" class="w-10 h-fit bg-blue-500 rounded-full p-2 text-gray-50 m-auto shadow-lg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .st0{fill:#FFFFFF;} </style> <path d="M28.9,9.4C28.9,9.4,28.9,9.4,28.9,9.4C28.9,9.3,29,9.2,29,9.1c0,0,0,0,0-0.1c0,0,0,0,0-0.1c0-0.1,0-0.2,0-0.3c0,0,0,0,0-0.1 c0-0.1-0.1-0.2-0.1-0.3c0,0,0,0,0,0c-0.1-0.1-0.1-0.1-0.2-0.2l-11-7c-0.3-0.2-0.8-0.2-1.1,0l-13,9c0,0-0.1,0.1-0.1,0.1 c0,0,0,0-0.1,0c-0.1,0.1-0.1,0.2-0.2,0.3c0,0,0,0,0,0.1C3,10.8,3,10.9,3,11c0,0,0,0,0,0v6v6c0,0.3,0.2,0.7,0.5,0.8l11,7 c0.2,0.1,0.4,0.2,0.5,0.2c0.2,0,0.4-0.1,0.6-0.2l13-9c0.2-0.2,0.4-0.4,0.4-0.7s-0.1-0.6-0.3-0.8c-0.9-0.9-1.1-2.2-0.5-3.4l0.7-1.5 c0-0.1,0.1-0.2,0.1-0.3c0,0,0-0.1,0-0.1c0,0,0,0,0,0c0-0.1,0-0.3-0.1-0.4c0,0,0-0.1,0-0.1c0-0.1-0.1-0.2-0.2-0.3c0,0,0,0,0,0 c-0.9-0.9-1.1-2.2-0.5-3.4L28.9,9.4z M26.6,14.8l-11.6,8L5,16.5v-3.6l9.5,6c0.2,0.1,0.4,0.2,0.5,0.2c0.2,0,0.4-0.1,0.6-0.2l10.3-7.1 C25.8,12.8,26,13.8,26.6,14.8z M15,28.8L5,22.5v-3.6l9.5,6c0.2,0.1,0.4,0.2,0.5,0.2c0.2,0,0.4-0.1,0.6-0.2l10.3-7.1 c-0.1,1.1,0.1,2.2,0.7,3.1L15,28.8z"></path> </g></svg>
                <h5>Supportive Learning Environment</h5>
                <p class="text-sm text-justify">UI M School provides a student-friendly and supportive environment where learners are guided by instructors and given opportunities to improve their skills.</p>
            </div> 
    </div>
    

</x-guest-layout>