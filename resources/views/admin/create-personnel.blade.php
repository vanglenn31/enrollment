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


        <!-- MAIN CONTENT -->
        <div class="flex-1 p-4 sm:p-6 lg:p-8">
            
            <div class="min-h-screen bg-gray-100 p-4 sm:p-6 lg:p-8">
                
                <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-sm p-6">

                    <!-- HEADER -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-3xl font-semibold text-gray-900">
                                Add {{ $displayName }}
                            </h1>
                            <p class="mt-2 text-sm text-gray-500">
                                Use the student-style registration flow to add {{ strtolower($displayName) }} accounts.
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
                    <form action="{{ $submitRoute }}" method="POST" class="space-y-6" id="personnelForm">
                        @csrf

                        @php
                            $personnelNumber = old('personnel_number', $personnelNumber ?? '');
                            $selectedDepartmentId = old('department_id', $selectedDepartmentId ?? '');
                            $specializationValue = old('specialization', $specializationValue ?? '');
                        @endphp

                        <input type="hidden" name="role" value="{{ $roleName }}" />

                        <!-- PROGRESS -->
                        <div class="w-full bg-gray-200 h-2 rounded mb-6">
                            <div id="progressBar" class="bg-indigo-600 h-2 rounded" style="width:33%"></div>
                        </div>

                        <!-- STEP INDICATOR -->
                        <div class="flex justify-between mb-8 text-center text-sm">
                            <div class="step-indicator active">1<br>Personal</div>
                            <div class="step-indicator">2<br>Contact</div>
                            <div class="step-indicator">3<br>Access</div>
                        </div>

                        <!-- STEP 1 -->
                        <div class="step">
                            <div class="grid gap-4 md:grid-cols-2">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" name="first_name" required class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                                    <input type="text" name="middle_name" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" name="last_name" required class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Suffix</label>
                                    <input type="text" name="suffix" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sex</label>
                                    <select name="sex" required class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select sex</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Birthdate</label>
                                    <input type="date" name="birthdate" required class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Personnel Number</label>
                                    <input type="text" name="personnel_number" required value="{{ $personnelNumber }}" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                                </div>

                                @if($roleName === 'professor')
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Department</label>
                                        <select name="department_id" required class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Select department</option>
                                            @foreach($departments ?? [] as $department)
                                                <option value="{{ $department->id }}" {{ $selectedDepartmentId == $department->id ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Specialization</label>
                                        <input type="text" name="specialization" value="{{ $specializationValue }}" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500" />
                                    </div>
                                @endif

                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="button" onclick="nextStep()" class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                                    Next
                                </button>
                            </div>
                        </div>

                        <!-- STEP 2 -->
                        <div class="step hidden">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" required class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3" />
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="text" name="phone_number" required class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3" />
                                </div>
                            </div>

                            <div class="mt-6 flex justify-between">
                                <button type="button" onclick="prevStep()" class="bg-gray-500 text-white px-5 py-2.5 rounded-lg">
                                    Back
                                </button>
                                <button type="button" onclick="nextStep()" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg">
                                    Next
                                </button>
                            </div>
                        </div>

                        <!-- STEP 3 -->
                        <div class="step hidden">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Password</label>
                                    <input type="password" name="password" required class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <input type="password" name="password_confirmation" required class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3" />
                                </div>
                            </div>

                            <div class="mt-6 flex justify-between">
                                <button type="button" onclick="prevStep()" class="bg-gray-500 text-white px-5 py-2.5 rounded-lg">
                                    Back
                                </button>
                                <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg">
                                    Create {{ $displayName }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT (unchanged) -->
    <script>
        let currentStep = 0;
        const steps = document.querySelectorAll('.step');
        const indicators = document.querySelectorAll('.step-indicator');
        const progressBar = document.getElementById('progressBar');

        function showStep(index) {
            steps.forEach((step, i) => {
                step.classList.toggle('hidden', i !== index);
            });

            indicators.forEach((ind, i) => {
                ind.classList.remove('active', 'done');

                if (i < index) {
                    ind.classList.add('done');
                    ind.innerHTML = '✔';
                } else if (i === index) {
                    ind.classList.add('active');
                    ind.innerHTML = i + 1;
                } else {
                    ind.innerHTML = i + 1;
                }
            });

            progressBar.style.width = ((index + 1) / steps.length) * 100 + '%';
        }

        function validateStep() {
            const inputs = steps[currentStep].querySelectorAll('input, select, textarea');
            for (const input of inputs) {
                if (input.hasAttribute('required') && !input.value) {
                    input.classList.add('border-red-500');
                    return false;
                }
            }
            return true;
        }

        function nextStep() {
            if (!validateStep()) return;
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        }

        function prevStep() {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        }

        showStep(currentStep);
    </script>
</x-app-layout>