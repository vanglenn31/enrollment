<x-guest-layout>
    @include('layouts.landing_nav')

    @if(session('success'))
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
        </script>
    @endif

    <div class="relative h-full w-full">
       <h1>FAQ</h1>
    </div>
    
    
    
</x-guest-layout>