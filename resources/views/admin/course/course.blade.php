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
                <div class="max-w-7xl mx-auto w-full space-y-6">

                    <!-- HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Courses</h1>
                            <p class="mt-1 text-sm text-gray-500">
                                Manage course offerings and assign them efficiently
                            </p>
                        </div>

                        <a href="{{ route('admin.course.create') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-blue-700 transition">
                            + Add Course
                        </a>
                    </div>

                    <!-- SUCCESS -->
                    @if(session('success'))
                        <div class="rounded-xl bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- CARD -->
                    <div class="bg-white rounded-2xl shadow-sm border p-4 sm:p-6 space-y-6">

                        <!-- SEARCH -->
                        <form method="GET" action="{{ route('admin.course.course') }}" class="w-full sm:w-80">
                            <div class="flex items-center bg-gray-100 rounded-xl px-3 py-2">
                                <input name="search" value="{{ $search ?? '' }}"
                                       placeholder="Search courses..."
                                       class="bg-transparent w-full outline-none text-sm px-2">
                                <button class="bg-slate-900 text-white text-sm px-4 py-1.5 rounded-lg">
                                    Search
                                </button>
                            </div>
                        </form>

                        <!-- TABLE -->
                        <div class="hidden md:block overflow-x-auto rounded-xl border">
                            <table class="min-w-full text-sm text-gray-700">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="py-3 px-4 text-left">Course</th>
                                        <th class="py-3 px-4 text-left">Code</th>
                                        <th class="py-3 px-4 text-left">Room</th>
                                        <th class="py-3 px-4 text-left">Program</th>
                                        <th class="py-3 px-4 text-left">Professor</th>
                                        <th class="py-3 px-4 text-left">Units</th>
                                        <th class="py-3 px-4 text-left">Slots</th>
                                        <th class="py-3 px-4 text-left">Status</th>
                                        <th class="py-3 px-4 text-left">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y">
                                    @forelse($courses as $course)
                                        <tr class="hover:bg-gray-50">

                                            <!-- CLICKABLE COURSE NAME -->
                                            <td class="py-3 px-4 font-medium">
                                                <a href="{{ route('admin.course.show', $course) }}"
                                                   class="text-blue-600 hover:underline hover:text-blue-800">
                                                    {{ $course->course_name }}
                                                </a>
                                            </td>

                                            <td class="py-3 px-4">
                                                {{ $course->course_code }}
                                            </td>

                                            <td class="py-3 px-4">
                                                {{ optional($course->room)->room_name ?? 'Not Assigned' }}
                                            </td>

                                            <td class="py-3 px-4">
                                                {{ optional($course->program)->code ?? optional($course->program)->name ?? 'Gen Ed' }}
                                            </td>

                                            <td class="py-3 px-4">
                                                @if($course->professor && $course->professor->profile)
                                                    {{ $course->professor->profile->first_name }}
                                                @else
                                                    <span class="text-gray-400">Unassigned</span>
                                                @endif
                                            </td>

                                            <td class="py-3 px-4">
                                                {{ $course->units }}
                                            </td>

                                            <!-- SLOTS WITH FILL INDICATOR -->
                                            <td class="py-3 px-4">
                                                @php
                                                    $enrolled = $course->enrolledCourses_count ?? $course->enrolledCourses->count();
                                                    $slots    = $course->slots ?? 30;
                                                    $pct      = $slots > 0 ? min(100, round(($enrolled / $slots) * 100)) : 0;
                                                @endphp
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs text-gray-600 whitespace-nowrap">
                                                        {{ $enrolled }}/{{ $slots }}
                                                    </span>
                                                    <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                                        <div class="h-1.5 rounded-full
                                                            {{ $pct >= 100 ? 'bg-red-500' : ($pct >= 75 ? 'bg-yellow-400' : 'bg-green-500') }}"
                                                             style="width: {{ $pct }}%">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="py-3 px-4">
                                                <span class="{{ $course->status === 'active'
                                                    ? 'bg-green-100 text-green-700'
                                                    : 'bg-red-100 text-red-700' }}
                                                    px-2 py-1 rounded-full text-xs">
                                                    {{ ucfirst($course->status) }}
                                                </span>
                                            </td>

                                            <td class="py-3 px-4 flex gap-2">

                                                <a href="{{ route('admin.course.edit', $course) }}"
                                                   class="text-blue-600 hover:underline">
                                                    Edit
                                                </a>

                                                @if($course->status === 'active')
                                                    <form method="POST"
                                                          action="{{ route('admin.course.deactivate', $course) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                onclick="return confirm('Deactivate this course?')"
                                                                class="text-red-600">
                                                            Deactivate
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST"
                                                          action="{{ route('admin.courses.activate', $course) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                onclick="return confirm('Activate this course?')"
                                                                class="text-green-600">
                                                            Activate
                                                        </button>
                                                    </form>
                                                @endif

                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-6 text-gray-400">
                                                No courses found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- MOBILE -->
                        <div class="md:hidden space-y-4">
                            @foreach($courses as $course)
                                <div class="border rounded-xl p-4 shadow-sm">

                                    <div class="flex justify-between">
                                        <div>
                                            <a href="{{ route('admin.course.show', $course) }}"
                                               class="font-semibold text-blue-600 hover:underline">
                                                {{ $course->course_name }}
                                            </a>
                                            <p class="text-sm text-gray-500 mt-0.5">{{ $course->course_code }}</p>
                                        </div>
                                        <span class="text-xs px-2 py-1 rounded-full self-start
                                            {{ $course->status === 'active'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-red-100 text-red-700' }}">
                                            {{ ucfirst($course->status) }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-black semi-bold mt-1">
                                        Room: {{ optional($course->room)->room_name ?? 'Not Assigned' }}
                                    </p>

                                    <div class="mt-2 text-sm space-y-0.5">
                                        <p><strong>Program:</strong> {{ optional($course->program)->code ?? optional($course->program)->name ?? 'Gen Ed' }}</p>
                                        <p><strong>Units:</strong> {{ $course->units }}</p>
                                        <p><strong>Slots:</strong> {{ $course->slots ?? 30 }}</p>
                                    </div>

                                    <div class="flex gap-3 mt-3">
                                        <a href="{{ route('admin.course.edit', $course) }}"
                                           class="text-blue-600 text-sm">Edit</a>

                                        @if($course->status === 'active')
                                            <form method="POST"
                                                  action="{{ route('admin.course.deactivate', $course) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="text-red-600 text-sm">Deactivate</button>
                                            </form>
                                        @else
                                            <form method="POST"
                                                  action="{{ route('admin.course.activate', $course) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="text-green-600 text-sm">Activate</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- PAGINATION -->
                        <div>
                            {{ $courses->links() }}
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>