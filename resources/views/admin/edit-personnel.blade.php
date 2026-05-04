<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">

        <!-- SIDEBAR -->
        <aside class="w-64 fixed inset-y-0 left-0 z-40 bg-white shadow-md hidden lg:block">
            @include('layouts.' . auth()->user()->role->role . '_side_bar')
        </aside>

        <!-- MAIN WRAPPER -->
        <div class="flex-1 lg:ml-64 flex flex-col min-w-0">

            <!-- NAVBAR -->
            <header class="sticky top-0 z-30 bg-white shadow-sm">
                @include('layouts.navigation')
            </header>

            <!-- CONTENT -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">

                <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-sm p-6">

                    <!-- HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-3xl font-semibold text-gray-900">
                                Edit {{ $displayName }}
                            </h1>
                            <p class="mt-2 text-sm text-gray-500">
                                Update {{ strtolower($displayName) }} contact information and login credentials.
                            </p>
                        </div>

                        <a href="{{ route('admin.' . strtolower($roleName) . 's') }}"
                           class="text-sm text-blue-600 hover:text-blue-800">
                            Back to {{ strtolower($displayName) }} list
                        </a>
                    </div>

                    <!-- ERRORS -->
                    @if ($errors->any())
                        <div class="rounded-2xl bg-red-50 border border-red-200 text-red-700 p-4 mb-4">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- FORM -->
                    <form action="{{ $submitRoute }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        @php
                            if ($roleName === 'professor') {
                                $personnelRecord = optional($personnel->profile)->{$roleName};
                            } else {
                                $personnelRecord = optional($personnel->{$roleName});
                            }

                            $personnelNumber = old('personnel_number', optional($personnelRecord)->{$roleName . '_number'} ?? '');
                            $selectedDepartmentId = old('department_id', optional($personnelRecord)->department_id ?? '');
                            $specializationValue = old('specialization', optional($personnelRecord)->specialization ?? '');
                        @endphp

                        <!-- PERSONAL INFO -->
                        <div class="grid gap-4 md:grid-cols-2">

                            <div>
                                <label class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="first_name" required
                                    value="{{ old('first_name', optional($personnel->profile)->first_name) }}"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                                <input type="text" name="middle_name"
                                    value="{{ old('middle_name', optional($personnel->profile)->middle_name) }}"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="last_name" required
                                    value="{{ old('last_name', optional($personnel->profile)->last_name) }}"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Suffix</label>
                                <input type="text" name="suffix"
                                    value="{{ old('suffix', optional($personnel->profile)->suffix) }}"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sex</label>
                                <select name="sex" required
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">

                                    <option value="">Select sex</option>
                                    <option value="Male" {{ old('sex', optional($personnel->profile)->sex) === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex', optional($personnel->profile)->sex) === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('sex', optional($personnel->profile)->sex) === 'Other' ? 'selected' : '' }}>Other</option>

                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Birthdate</label>
                                <input type="date" name="birthdate" required
                                    value="{{ old('birthdate', optional($personnel->profile)->birthdate?->format('Y-m-d')) }}"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Personnel Number</label>
                                <input type="text" name="personnel_number" required
                                    value="{{ $personnelNumber }}"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                            </div>

                            @if($roleName === 'professor')
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Department</label>
                                    <select name="department_id" required
                                        class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">

                                        <option value="">Select department</option>
                                        @foreach($departments ?? [] as $department)
                                            <option value="{{ $department->id }}"
                                                {{ $selectedDepartmentId == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Specialization</label>
                                    <input type="text" name="specialization"
                                        value="{{ $specializationValue }}"
                                        class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                                </div>
                            @endif

                        </div>

                        <!-- CONTACT -->
                        <div class="grid gap-4 md:grid-cols-2">

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" required
                                    value="{{ old('email', $personnel->email) }}"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" name="phone_number" required
                                    value="{{ old('phone_number', optional($personnel->profile)->phone_number) }}"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                            </div>

                        </div>

                        <!-- PASSWORD -->
                        <div class="grid gap-4 md:grid-cols-2">

                            <div>
                                <label class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" name="password"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                <input type="password" name="password_confirmation"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                            </div>

                        </div>

                        <!-- BUTTON -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                                Update {{ $displayName }}
                            </button>
                        </div>

                    </form>

                </div>
            </main>

        </div>
    </div>
</x-app-layout>