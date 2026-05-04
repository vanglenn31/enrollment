<x-app-layout>
<div class="flex min-h-screen bg-gray-100">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
        @include('layouts.' . auth()->user()->role->role . '_side_bar')
    </aside>

    <!-- MAIN -->
    <div class="flex-1 lg:ml-64 flex flex-col">

        <!-- NAVBAR -->
        <header class="sticky top-0 z-30 bg-white shadow-sm">
            @include('layouts.navigation')
        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-3xl mx-auto w-full">

                <!-- CARD -->
                <div class="bg-white rounded-2xl shadow-sm p-6 space-y-6">

                    <!-- HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">
                                Edit Program
                            </h1>
                            <p class="text-sm text-gray-500 mt-1">
                                Update program details and assign it to the right department.
                            </p>
                        </div>

                        <a href="{{ route('admin.programs') }}"
                           class="text-sm text-blue-600 hover:text-blue-800">
                            Back
                        </a>
                    </div>

                    <!-- ERRORS -->
                    @if ($errors->any())
                        <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 p-4">
                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- FORM -->
                    <form action="{{ route('admin.programs.update', $program) }}"
                          method="POST"
                          class="space-y-5">

                        @csrf
                        @method('PUT')

                        <!-- CODE -->
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Program Code
                            </label>
                            <input type="text"
                                   name="code"
                                   value="{{ old('code', $program->code) }}"
                                   required
                                   class="mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- NAME -->
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Program Name
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $program->name) }}"
                                   required
                                   class="mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- DEPARTMENT -->
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Department
                            </label>
                            <select name="department_id"
                                    required
                                    class="mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500">

                                <option value="">Select a department</option>

                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id', $program->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ACTION -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">

                            <a href="{{ route('admin.programs') }}"
                               class="text-center px-5 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                                Cancel
                            </a>

                            <button type="submit"
                                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
                                Update Program
                            </button>

                        </div>

                    </form>

                </div>
            </div>
        </main>

    </div>
</div>
</x-app-layout>