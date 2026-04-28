<x-app-layout>
    <div class="grid grid-cols-5 2xl:grid-cols-6 gap-x-0 gap-y-0">
        <div class="col-span-1 fixed h-screen z-0">
            @include('layouts.student_side_bar')
        </div>
        <div class="col-span-5 col-start-2">
            @include('layouts.navigation')
        </div>
        
        <div class="col-span-4 col-start-2 p-6 z-30 w-full">
            <h1 class="text-2xl font-bold mb-4">Enrollment</h1>
        

    
    </div>
    

</x-app-layout>