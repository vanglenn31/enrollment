<x-app-layout>
    <div class="grid grid-cols-5 2xl:grid-cols-6 gap-x-0 gap-y-0">
        <div class="hidden md:block col-span-1 fixed h-screen z-30">
            @include('layouts.student_side_bar')
        </div>
        <div class="col-span-5 col-start-2 z-20">
            @include('layouts.navigation')
        </div>

        <div class="col-span-4 col-start-2 p-6 z-30 w-full">
            <h1 class="text-2xl font-bold mb-4">Subject Enlistment</h1>
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-6">

                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <div class="mb-4">
                        <h2 class="text-xl font-semibold">Browse available subjects</h2>
                        <p class="text-gray-500 text-sm">Select courses from your program or general education offerings.</p>
                    </div>

                    <div class="rounded-3xl border border-gray-200 bg-slate-50 p-4 mb-6 text-sm text-gray-700">
                        {{ $message }}
                    </div>

                    @if(session('success'))
                        <div class="rounded-3xl border border-green-200 bg-green-50 p-4 text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="rounded-3xl border border-red-200 bg-red-50 p-4 text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid gap-6 lg:grid-cols-2">
                        <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">Current subjects</h3>
                            @if($currentEnrollments->isEmpty())
                                <div class="rounded-2xl border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">
                                    No subjects have been enlisted yet.
                                </div>
                            @else
                                <ul class="space-y-3">
                                    @foreach($currentEnrollments as $enrollment)
                                        <li class="rounded-2xl border border-gray-200 p-4 bg-slate-50">
                                            <div class="flex items-center justify-between gap-4">
                                                <div>
                                                    <p class="font-semibold">{{ optional($enrollment->course)->course_code ?? 'Unknown code' }} - {{ optional($enrollment->course)->course_name ?? 'Unknown course' }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">{{ optional(optional($enrollment->course)->program)->name ?? 'General Education' }} • {{ optional($enrollment->course)->units ?? 0 }} units</p>
                                                </div>
                                                <span class="text-xs rounded-full bg-slate-100 px-3 py-1 text-slate-700">Enlisted</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">Available subjects</h3>
                            @if($availableCourses->isEmpty())
                                <div class="rounded-2xl border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">
                                    No available subjects found for your program or general education.
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach($availableCourses as $course)
                                        <div class="rounded-3xl border border-gray-200 p-4 hover:border-blue-300 transition">
                                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-slate-900">{{ $course->course_code }} - {{ $course->course_name }}</p>
                                                    <p class="mt-1 text-xs text-gray-500">{{ $course->program->name ?? 'General Education' }} • {{ $course->units }} units • ₱{{ number_format($course->course_price ?? 0, 2) }}</p>
                                                    <p class="mt-2 text-xs text-gray-500">{{ Str::limit($course->description ?? 'No description available.', 110) }}</p>
                                                </div>

                                                <div class="flex items-center gap-3">
                                                    <span class="text-xs bg-slate-100 px-3 py-1 rounded-full text-slate-700">{{ $course->time ?? 'Schedule pending' }}</span>
                                                    @if($canSelfEnroll)
                                                        <form action="{{ route('student.course.enlist') }}" method="POST" class="inline-block">
                                                            @csrf
                                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                                            <button type="submit" class="rounded-2xl bg-black px-4 py-2 text-xs font-semibold text-white hover:bg-slate-900">Enlist</button>
                                                        </form>
                                                    @else
                                                        <span class="rounded-2xl bg-gray-200 px-3 py-2 text-xs font-semibold text-gray-600">Verification required</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
