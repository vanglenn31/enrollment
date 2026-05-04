<x-app-layout>
<div class="flex min-h-screen bg-gray-100">

    <!-- SIDEBAR -->
    <aside class="hidden lg:block w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40">
        @include('layouts.' . auth()->user()->role->role . '_side_bar')
    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col lg:ml-64">

        <!-- NAV -->
        <header class="sticky top-0 z-30 bg-white shadow-sm">
            @include('layouts.navigation')
        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">

            <div class="max-w-7xl mx-auto space-y-6">

                <!-- HEADER -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                            Registrar
                        </h1>
                        <p class="text-sm text-gray-500">
                            Manage registrar staff accounts
                        </p>
                    </div>

                    <a href="{{ route('admin.registrars.create') }}"
                        class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700">
                        + Add Registrar
                    </a>

                </div>

                <!-- CARD -->
                <div class="hidden md:block bg-white rounded-2xl border shadow-sm p-4 sm:p-6 space-y-6">

                    <!-- TOP BAR -->
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                        <div>
                            <h2 class="text-lg font-semibold">Registrar List</h2>
                            <p class="text-sm text-gray-500">All registrar staff records</p>
                        </div>

                        <!-- SEARCH -->
                        <form method="GET" action="{{ route('admin.registrars') }}" class="w-full sm:w-80">

                            <div class="flex bg-gray-100 rounded-xl px-3 py-2">

                                <input name="search"
                                    value="{{ $search ?? '' }}"
                                    placeholder="Search registrar..."
                                    class="bg-transparent w-full outline-none text-sm min-w-0">

                                <button class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-sm shrink-0">
                                    Search
                                </button>

                            </div>

                        </form>

                    </div>

                    <!-- TABLE -->
                    <div class="w-full overflow-x-auto border rounded-xl">

                        <table class="w-full min-w-[900px] text-sm">

                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="p-2 sm:p-3 text-left whitespace-nowrap">Name</th>
                                    <th class="p-2 sm:p-3 text-left whitespace-nowrap">Registrar #</th>
                                    <th class="p-2 sm:p-3 text-left whitespace-nowrap">Email</th>
                                    <th class="p-2 sm:p-3 text-left whitespace-nowrap">Status</th>
                                    <th class="p-2 sm:p-3 text-left whitespace-nowrap">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y">

                                @forelse($registrars as $registrar)

                                    @php
                                        $profile = $registrar->profile;
                                        $reg = $registrar->registrar;
                                    @endphp

                                    <tr class="hover:bg-gray-50">

                                        <td class="p-2 sm:p-3 whitespace-nowrap">
                                            {{ $profile?->first_name }} {{ $profile?->last_name }}
                                        </td>

                                        <td class="p-2 sm:p-3 whitespace-nowrap">
                                            {{ $reg?->registrar_number ?? 'N/A' }}
                                        </td>

                                        <td class="p-2 sm:p-3 whitespace-nowrap">
                                            {{ optional($registrar->profile->user)->email ?? 'N/A' }}
                                        </td>

                                        <td class="p-2 sm:p-3 whitespace-nowrap">
                                            <span class="{{ $registrar->is_active ? 'text-green-600' : 'text-red-600' }} font-medium">
                                                {{ $registrar->is_active ? 'Active' : 'Deactivated' }}
                                            </span>
                                        </td>

                                        <!-- ACTIONS -->
                                        <td class="p-2 sm:p-3">
                                            <div class="flex flex-wrap sm:flex-nowrap gap-2 sm:gap-3">

                                                <a href="{{ route('admin.registrars.edit', $registrar) }}"
                                                    class="text-blue-600 text-sm font-medium hover:underline">
                                                    Edit
                                                </a>

                                                @if($registrar->is_active)

                                                    <form method="POST" action="{{ route('admin.registrars.deactivate', $registrar) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button class="text-red-600 text-sm font-medium hover:underline">
                                                            Deactivate
                                                        </button>
                                                    </form>

                                                @else

                                                    <form method="POST" action="{{ route('admin.registrars.activate', $registrar) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button class="text-green-600 text-sm font-medium hover:underline">
                                                            Activate
                                                        </button>
                                                    </form>

                                                @endif

                                            </div>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5" class="text-center p-10 text-gray-400">
                                            <div class="flex flex-col items-center gap-2">
                                                <span class="text-lg">No registrars found</span>
                                                <span class="text-sm">Try adding a new registrar or changing your search</span>
                                            </div>
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    <!-- PAGINATION -->
                    <div class="pt-4">
                        {{ $registrars->links() }}
                    </div>

                </div>
                
                <!-- MOBILE CARDS -->
<div class="md:hidden space-y-4">

    @foreach($registrars as $registrar)

        <div class="bg-white border rounded-xl p-4 shadow-sm space-y-2">

            <div class="flex justify-between items-start">
                <div>
                    <p class="font-semibold">
                        {{ $registrar->profile?->first_name }} {{ $registrar->profile?->last_name }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ $registrar->registrar?->registrar_number ?? 'N/A' }}
                    </p>
                </div>

                <span class="text-sm font-medium {{ $registrar->is_active ? 'text-green-600' : 'text-red-600' }}">
                    {{ $registrar->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <p class="text-sm text-gray-600">
                {{ optional($registrar->profile->user)->email ?? 'N/A' }}
            </p>

            <div class="flex gap-3 pt-2">

                <a href="{{ route('admin.registrars.edit', $registrar) }}"
                   class="text-blue-600 text-sm">
                    Edit
                </a>

                @if($registrar->is_active)

                    <form method="POST" action="{{ route('admin.registrars.deactivate', $registrar) }}">
                        @csrf @method('PATCH')
                        <button class="text-red-600 text-sm">Deactivate</button>
                    </form>

                @else

                    <form method="POST" action="{{ route('admin.registrars.activate', $registrar) }}">
                        @csrf @method('PATCH')
                        <button class="text-green-600 text-sm">Activate</button>
                    </form>

                @endif

            </div>

        </div>

    @endforeach

</div>

            </div>

        </main>
    </div>
</div>
</x-app-layout>