<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
        @include('layouts.' . auth()->user()->role->role . '_side_bar')
    </aside>

    <!-- MAIN -->
    <div class="flex-1 lg:ml-64 flex flex-col">

        <!-- NAV -->
        <header class="sticky top-0 z-30 bg-white shadow-sm">
            @include('layouts.navigation')
        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">

            <div class="max-w-7xl mx-auto space-y-6">

                <!-- HEADER -->
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        Students
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Manage student records and statuses
                    </p>
                </div>

                <!-- CARD -->
                <div class="bg-white rounded-2xl shadow-sm border p-4 sm:p-6 space-y-6">

                    <!-- TOP BAR -->
                    <div class="flex flex-col lg:flex-row lg:justify-between gap-4">

                        <div>
                            <h2 class="text-lg font-semibold">Student Directory</h2>
                            <p class="text-sm text-gray-500">
                                Current enrollments
                            </p>
                        </div>

                        <!-- SEARCH -->
                        <form method="GET" action="{{ route('admin.students') }}" class="w-full sm:w-80">
                            <div class="flex bg-gray-100 rounded-xl px-3 py-2">
                                <input name="search" value="{{ $search ?? '' }}"
                                    class="bg-transparent w-full outline-none text-sm"
                                    placeholder="Search students...">

                                <button class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-sm">
                                    Search
                                </button>
                            </div>
                        </form>

                    </div>

                    <!-- TABLE (DESKTOP) -->
                    <div class="hidden md:block overflow-x-auto border rounded-xl">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="p-3 text-left">ID</th>
                                    <th class="p-3 text-left">Student</th>
                                    <th class="p-3 text-left">Program</th>
                                    <th class="p-3 text-left">Status</th>
                                    <th class="p-3 text-left">Created</th>
                                    <th class="p-3 text-left">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y">
                                @forelse($students as $student)
                                    <tr class="hover:bg-gray-50">

                                        <td class="p-3">
                                            {{ $student->student_number ?? '—' }}
                                        </td>

                                        <td class="p-3">
                                            {{ optional($student->profile)->first_name }}
                                            {{ optional($student->profile)->last_name }}
                                        </td>

                                        <td class="p-3">
                                            {{ optional($student->programRelation)->code ?? 'Unknown' }}
                                        </td>

                                        <td class="p-3">
                                            @if($student->is_verified)
                                                <span class="text-green-600 font-medium">Verified</span>
                                            @else
                                                <span class="text-red-600 font-medium">Unverified</span>
                                            @endif
                                        </td>

                                        <td class="p-3">
                                            {{ $student->created_at?->format('M d, Y') }}
                                        </td>

                                        <td class="p-3">
                                            <a href="{{ route('admin.students.edit', $student) }}"
                                                class="text-blue-600">
                                                Edit
                                            </a>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center p-6 text-gray-400">
                                            No students found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- MOBILE CARDS -->
                    <div class="md:hidden space-y-4">
                        @forelse($students as $student)
                            <div class="border rounded-xl p-4">

                                <div class="flex justify-between">
                                    <h3 class="font-semibold">
                                        {{ optional($student->profile)->first_name }}
                                    </h3>

                                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100">
                                        {{ $student->student_number ?? '—' }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 mt-2">
                                    {{ optional($student->programRelation)->name }}
                                </p>

                                <a href="{{ route('admin.students.edit', $student) }}"
                                    class="text-blue-600 text-sm mt-2 inline-block">
                                    Edit →
                                </a>

                            </div>
                        @empty
                            <p class="text-center text-gray-400">No students found</p>
                        @endforelse
                    </div>

                    <!-- PAGINATION -->
                    <div>
                        {{ $students->links() }}
                    </div>

                </div>
            </div>

        </main>
    </div>
</div>
</x-app-layout>