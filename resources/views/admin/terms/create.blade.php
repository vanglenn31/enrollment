<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

        <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
            @include('layouts.' . auth()->user()->role->role . '_side_bar')
        </aside>

        <div class="flex-1 lg:ml-64 flex flex-col">
            <header class="sticky top-0 z-30 bg-white shadow-sm">
                @include('layouts.navigation')
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <div class="max-w-2xl mx-auto w-full space-y-6">

                    {{-- Page Header --}}
                    <div>
                        <a href="{{ route('admin.terms.index') }}"
                           class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1 mb-2">
                            ← Back to Terms
                        </a>
                        <h1 class="text-2xl font-bold text-gray-900">Create New Term</h1>
                        <p class="text-sm text-gray-500 mt-1">
                            Define a new school semester or academic period.
                        </p>
                    </div>

                    {{-- Form Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border p-6 space-y-5">

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                                <ul class="space-y-1 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.terms.store') }}" class="space-y-5">
                            @csrf

                            {{-- School Year --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    School Year <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="school_year"
                                       value="{{ old('school_year') }}"
                                       placeholder="e.g. 2025-2026"
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm
                                              focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none
                                              @error('school_year') border-red-400 @enderror">
                                @error('school_year')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-400 mt-1">Format: YYYY-YYYY (e.g. 2025-2026)</p>
                            </div>

                            {{-- Semester --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Semester <span class="text-red-500">*</span>
                                </label>
                                <select name="semester"
                                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm
                                               focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none
                                               @error('semester') border-red-400 @enderror">
                                    <option value="">-- Select semester --</option>
                                    <option value="1st"    {{ old('semester') === '1st'    ? 'selected' : '' }}>1st Semester</option>
                                    <option value="2nd"    {{ old('semester') === '2nd'    ? 'selected' : '' }}>2nd Semester</option>
                                    <option value="summer" {{ old('semester') === 'summer' ? 'selected' : '' }}>Summer Term</option>
                                </select>
                                @error('semester')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Dates --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                    <input type="date"
                                           name="start_date"
                                           value="{{ old('start_date') }}"
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm
                                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none
                                                  @error('start_date') border-red-400 @enderror">
                                    @error('start_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                    <input type="date"
                                           name="end_date"
                                           value="{{ old('end_date') }}"
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm
                                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none
                                                  @error('end_date') border-red-400 @enderror">
                                    @error('end_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <p class="text-xs text-gray-400">
                                The term will be created with <strong>Upcoming</strong> status.
                                You can activate it later from the terms list.
                            </p>

                            {{-- Submit --}}
                            <div class="flex items-center gap-3 pt-2">
                                <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold
                                               px-6 py-2.5 rounded-xl transition">
                                    Create Term
                                </button>
                                <a href="{{ route('admin.terms.index') }}"
                                   class="text-sm text-gray-500 hover:underline">
                                    Cancel
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
