<x-app-layout>
    <div class="grid grid-cols-5 2xl:grid-cols-6 gap-x-0 gap-y-0">
        <div class="col-span-1 fixed h-screen z-30 w-fit lg:w-auto">@include('layouts.admin_side_bar')</div>
        <div class="col-span-5 sticky top-0 z-50 col-start-2 z-20">@include('layouts.navigation')</div>
        <div class="col-span-4 col-start-2 p-6 z-10 md:z-10 w-full">
            <div class="min-h-screen bg-gray-100 p-4 sm:p-6 lg:p-8">
                <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-3xl font-semibold text-gray-900">Add Registrar</h1>
                            <p class="mt-2 text-sm text-gray-500">Add registrar staff who can manage student records.</p>
                        </div>
                        <a href="{{ route('admin.registrars') }}" class="text-sm text-blue-600 hover:text-blue-800">Back to registrar</a>
                    </div>
                    <form action="{{ route('admin.registrars.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Office</label>
                            <input type="text" name="office" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Save Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>