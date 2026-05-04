<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
            @include('layouts.admin_side_bar')
        </aside>

        <!-- MAIN -->
        <div class="flex-1 lg:ml-64 flex flex-col">

            <!-- HEADER -->
            <header class="sticky top-0 z-30 bg-white shadow-sm">
                @include('layouts.navigation')
            </header>

            <!-- CONTENT -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">

                <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-sm p-6">

                    <!-- HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <div>
                            <h1 class="text-3xl font-semibold text-gray-900">
                                Add Professor
                            </h1>
                            <p class="mt-2 text-sm text-gray-500">
                                Enter professor details to add them to the system.
                            </p>
                        </div>

                        <a href="{{ route('admin.professors') }}"
                           class="text-sm text-blue-600 hover:text-blue-800">
                            Back to professors
                        </a>
                    </div>

                    <!-- ERRORS -->
                    @if ($errors->any())
                        <div class="rounded-2xl bg-red-50 border border-red-200 text-red-700 p-4 mb-4">
                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- FORM -->
                    <form action="{{ route('admin.professors.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- NAME -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name"
                                   value="{{ old('name') }}"
                                   class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                        </div>

                        <!-- EMAIL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email"
                                   value="{{ old('email') }}"
                                   class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                        </div>

                        <!-- DEPARTMENT -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Department</label>
                            <input type="text" name="department"
                                   value="{{ old('department') }}"
                                   class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                        </div>

                        <!-- BUTTON -->
                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                                Save Professor
                            </button>
                        </div>

                    </form>

                </div>
            </main>

        </div>
    </div>
</x-app-layout>