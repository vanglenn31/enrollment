<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
            @include('layouts.' . auth()->user()->role->role . '_side_bar')
        </aside>

        <!-- MAIN AREA -->
        <div class="flex-1 lg:ml-64 flex flex-col">

            <!-- HEADER -->
            <header class="sticky top-0 z-30 bg-white shadow-sm">
                @include('layouts.navigation')
            </header>

            <!-- CONTENT -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">

                <div class="max-w-7xl mx-auto space-y-6">

                    <!-- HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Departments</h1>
                            <p class="text-sm text-gray-500 mt-1">
                                Manage your academic departments
                            </p>
                        </div>

                        <a href="{{ route('admin.department.create') }}"
                           class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700">
                            + Add Department
                        </a>
                    </div>

                    <!-- SUCCESS -->
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
                                <h2 class="text-lg font-semibold text-gray-800">Department List</h2>
                                <p class="text-sm text-gray-500">All registered departments</p>
                            </div>

                            <!-- SEARCH -->
                            <form method="GET" action="{{ route('admin.department') }}" class="w-full sm:w-80">
                                <div class="flex bg-gray-100 rounded-xl px-3 py-2">
                                    <input name="search" value="{{ $search ?? '' }}"
                                           placeholder="Search departments..."
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
                                        <th class="py-3 px-4 text-left">Department</th>
                                        <th class="py-3 px-4 text-left">Programs</th>
                                        <th class="py-3 px-4 text-left">Created</th>
                                        <th class="py-3 px-4 text-left">Status</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y">
                                    @forelse($departments as $department)
                                        <tr class="hover:bg-gray-50">

                                            <td class="py-3 px-4 font-medium text-gray-900">
                                                {{ $department->name }}
                                            </td>

                                            <td class="py-3 px-4">
                                                {{ $department->programs_count }}
                                            </td>

                                            <td class="py-3 px-4">
                                                {{ $department->created_at->format('M d, Y') }}
                                            </td>

                                            <!-- ✅ STATUS -->
                                            <td class="py-3 px-4">
                                                <span class="{{ $department->status === 'active'
                                                    ? 'bg-green-100 text-green-700'
                                                    : 'bg-red-100 text-red-700' }}
                                                    px-2 py-1 rounded-full text-xs">
                                                    {{ ucfirst($department->status) }}
                                                </span>
                                            </td>

                                            <!-- ✅ ACTIONS -->
                                            <td class="py-3 px-4 flex gap-2">

                                                <a href="{{ route('admin.department.edit', $department) }}"
                                                   class="text-blue-600 hover:underline">
                                                    Edit
                                                </a>

                                                @if($department->status === 'active')
                                                    <form method="POST"
                                                          action="{{ route('admin.department.deactivate', $department) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                                onclick="return confirm('Deactivate this department?')"
                                                                class="text-red-600">
                                                            Deactivate
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST"
                                                          action="{{ route('admin.department.activate', $department) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                                onclick="return confirm('Activate this department?')"
                                                                class="text-green-600">
                                                            Activate
                                                        </button>
                                                    </form>
                                                @endif

                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-6 text-gray-400">
                                                No departments found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- MOBILE -->
                        <div class="md:hidden space-y-4">
                            @forelse($departments as $department)

                                <div class="border rounded-xl p-4 shadow-sm">

                                    <div class="flex justify-between items-center">
                                        <h3 class="font-semibold text-gray-900">
                                            {{ $department->name }}
                                        </h3>

                                        <span class="text-xs px-2 py-1 rounded-full
                                            {{ $department->status === 'active'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-red-100 text-red-700' }}">
                                            {{ ucfirst($department->status) }}
                                        </span>
                                    </div>

                                    <div class="mt-2 text-sm text-gray-600 space-y-1">
                                        <p><strong>Programs:</strong> {{ $department->programs_count }}</p>
                                        <p><strong>Created:</strong> {{ $department->created_at->format('M d, Y') }}</p>
                                    </div>

                                    <div class="flex gap-3 mt-3">

                                        <a href="{{ route('admin.department.edit', $department) }}"
                                           class="text-blue-600 text-sm">
                                            Edit
                                        </a>

                                        @if($department->status === 'active')
                                            <form method="POST"
                                                  action="{{ route('admin.department.deactivate', $department) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="text-red-600 text-sm">
                                                    Deactivate
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST"
                                                  action="{{ route('admin.department.activate', $department) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="text-green-600 text-sm">
                                                    Activate
                                                </button>
                                            </form>
                                        @endif

                                    </div>

                                </div>

                            @empty
                                <p class="text-center text-gray-400">No departments found</p>
                            @endforelse
                        </div>

                        <!-- PAGINATION -->
                        <div>
                            {{ $departments->links() }}
                        </div>

                    </div>

                </div>

            </main>
        </div>
    </div>
</x-app-layout>