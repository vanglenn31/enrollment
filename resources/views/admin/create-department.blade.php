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
                <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm p-4 sm:p-6">

                    <!-- HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">
                                Add Department
                            </h1>
                            <p class="text-sm text-gray-500 mt-1">
                                Create a new academic department
                            </p>
                        </div>

                        <a href="{{ route('admin.department.department') }}"
                           class="text-sm text-blue-600 hover:text-blue-800">
                            Back
                        </a>
                    </div>

                    <!-- ERRORS -->
                    @if ($errors->any())
                        <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 p-4 mb-4">
                            <ul class="list-disc pl-5 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- FORM -->
                    <form action="{{ route('admin.department.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- INPUT -->
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Department Name
                            </label>
                            <input type="text" name="name" required
                                   class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- BUTTON -->
                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm">
                                Save Department
                            </button>
                        </div>

                    </form>

                </div>
            </main>
        </div>
    </div>
</x-app-layout>