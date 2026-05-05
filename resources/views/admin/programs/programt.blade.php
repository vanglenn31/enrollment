<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
            @include('layouts.' . auth()->user()->role->role . '_side_bar')
        </aside>

        <!-- MAIN -->
        <div class="flex-1 lg:ml-64 flex flex-col">

            <!-- HEADER -->
            <header class="sticky top-0 z-30 bg-white shadow-sm">
                @include('layouts.navigation')
            </header>

            <!-- CONTENT -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">

                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- PAGE HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Programs</h1>
                            <p class="text-sm text-gray-500 mt-1">
                                Manage academic programs efficiently
                            </p>
                        </div>

                        <a href="{{ route('admin.programs.create') }}"
                           class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700">
                            + Add Program
                        </a>
                    </div>

                    <!-- SUCCESS MESSAGE -->
                    @if(session('success'))
                        <div class="rounded-2xl bg-emerald-100 border border-emerald-200 text-emerald-700 p-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- CARD -->
                    <div class="bg-white rounded-2xl shadow-sm border p-4 sm:p-6 space-y-6">

                        <!-- TOP BAR -->
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Program Catalog</h2>
                                <p class="text-sm text-gray-500">All registered academic programs</p>
                            </div>

                            <!-- SEARCH -->
                            <form method="GET" action="{{ route('admin.programs.programs') }}" class="w-full sm:w-80">
                                <div class="flex bg-gray-100 rounded-xl px-3 py-2 focus-within:ring-2 focus-within:ring-blue-500">
                                    <input name="search" value="{{ $search ?? '' }}"
                                           type="text"
                                           placeholder="Search programs..."
                                           class="bg-transparent w-full outline-none text-sm px-2">

                                    <button class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-sm">
                                        Search
                                    </button>
                                </div>
                            </form>

                        </div>

                        <!-- DESKTOP TABLE -->
                        <div class="hidden md:block overflow-x-auto border rounded-xl">
                            <table class="min-w-full text-sm text-gray-700">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="py-3 px-4 text-left">Program</th>
                                        <th class="py-3 px-4 text-left">Department</th>
                                        <th class="py-3 px-4 text-left">Code</th>
                                        <th class="py-3 px-4 text-left">Status</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y">
                                    @forelse($programs as $program)
                                        <tr class="hover:bg-gray-50">

                                            <td class="py-3 px-4 font-medium">
                                                {{ $program->name }}
                                            </td>

                                            <td class="py-3 px-4">
                                                {{ optional($program->department)->name ?? 'Unassigned' }}
                                            </td>

                                            <td class="py-3 px-4">
                                                {{ $program->code }}
                                            </td>

                                            <!-- STATUS -->
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-1 rounded-full text-xs
                                                    {{ $program->status === 'active'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-red-100 text-red-700' }}">
                                                    {{ ucfirst($program->status) }}
                                                </span>
                                            </td>

                                            <!-- ACTIONS -->
                                            <td class="py-3 px-4 space-x-3">

                                                <a href="{{ route('admin.programs.edit', $program) }}"
                                                   class="text-blue-600 hover:underline">
                                                    Edit
                                                </a>

                                                <form action="{{ strtolower($program->status) === 'active'
                                                    ? route('admin.programs.deactivate', $program)
                                                    : route('admin.programs.activate', $program) }}"
                                                    method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                        class="{{ strtolower($program->status) === 'active'
                                                            ? 'text-red-600 hover:underline'
                                                            : 'text-green-600 hover:underline' }}">
                                                        {{ strtolower($program->status) === 'active' ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>

                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-6 text-gray-400">
                                                No programs found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- MOBILE VIEW -->
                        <div class="md:hidden space-y-4">
                            @forelse($programs as $program)

                                <div class="border rounded-xl p-4 shadow-sm">

                                    <div class="flex justify-between items-start">
                                        <h3 class="font-semibold text-gray-900">
                                            {{ $program->name }}
                                        </h3>

                                        <span class="text-xs px-2 py-1 rounded-full
                                            {{ $program->status === 'active'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-red-100 text-red-700' }}">
                                            {{ ucfirst($program->status) }}
                                        </span>
                                    </div>

                                    <div class="text-sm text-gray-600 mt-2 space-y-1">
                                        <p><strong>Department:</strong> {{ optional($program->department)->name ?? 'Unassigned' }}</p>
                                        <p><strong>Code:</strong> {{ $program->code }}</p>
                                    </div>

                                    <div class="mt-3 flex flex-col space-y-1">

                                        <a href="{{ route('admin.programs.edit', $program) }}"
                                           class="text-blue-600 text-sm">
                                            Edit →
                                        </a>

                                        <form action="{{ strtolower($program->status) === 'active'
                                            ? route('admin.programs.deactivate', $program)
                                            : route('admin.programs.activate', $program) }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="{{ strtolower($program->status) === 'active'
                                                ? 'text-red-600'
                                                : 'text-green-600' }}">
                                            {{ strtolower($program->status) === 'active' ? 'Deactivate' : 'Activate' }}
                                        </button>
                                        
                                    </form>

                                    </div>

                                </div>

                            @empty
                                <p class="text-center text-gray-400">No programs found</p>
                            @endforelse
                        </div>

                        <!-- PAGINATION -->
                        <div>
                            {{ $programs->links() }}
                        </div>

                    </div>

                </div>

            </main>
        </div>
    </div>
</x-app-layout>