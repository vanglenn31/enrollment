<x-guest-layout>

    @include('layouts.landing_nav')
    <div class="max-w-5xl mx-auto py-6">
        <h1 class="text-2xl font-bold mb-2">Enrollment Application</h1>

        <!-- Progress -->
        <div class="w-full bg-gray-200 h-2 rounded mb-6">
            <div id="progressBar" class="bg-indigo-600 h-2 rounded" style="width:20%"></div>
        </div>

        <!-- Step Circles -->
        <div class="flex justify-between mb-8 text-center text-sm">
            <div class="step-circle active">1<br>Personal</div>
            <div class="step-circle">2<br>Academic</div>
            <div class="step-circle">3<br>Program</div>
            <div class="step-circle">4<br>Address</div>
            <div class="step-circle">5<br>Account</div>
        </div>
        <form method="POST" action="{{ route('register') }}" class="pt-6">
            @csrf
            <div class="step">
            <!-- Personal Information -->
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input id="" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="middle_name" :value="__('Middle Name')" />
                    <x-text-input id="" class="block mt-1 w-full" type="text" name="middle_name" :value="old('middle_name')"  autofocus autocomplete="middle_name" />
                    <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input id="" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')"  autofocus autocomplete="last_name" />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="suffix" :value="__('Suffix')" />
                    <x-text-input id="" class="block mt-1 w-full" type="text" name="suffix" :value="old('suffix')"  autofocus autocomplete="suffix" />
                    <x-input-error :messages="$errors->get('suffix')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="sex" :value="__('Sex')" />
                    <x-text-input id="" class="block mt-1 w-full" type="text" name="sex" :value="old('sex')"  required autofocus autocomplete="sex" />
                    <x-input-error :messages="$errors->get('sex')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="birthdate" :value="__('Date of Birth')" />
                    <x-text-input id="" class="block mt-1 w-full" type="date" name="birthdate" :value="old('birthdate')"  required autofocus autocomplete="birthdate" />
                    <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="phone_number" :value="__('Phone Number')" />
                    <x-text-input id="" class="block mt-1 w-full" type="number" name="phone_number" :value="old('phone_number')" required autocomplete="phone_number" />
                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                </div>

                <button type="button" onclick="nextStep()" class="btn-next">Next</button>
            </div>

            <!-- Address -->
            <div class="step hidden">
                <div class="mt-4">
                    <x-input-label for="house_number" :value="__('House Number')" />
                    <x-text-input id="" class="block mt-1 w-full" type="text" name="house_number" :value="old('house_number')"  autocomplete="house_number" />
                    <x-input-error :messages="$errors->get('house_number')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="street" :value="__('Street')" />
                    <x-text-input id="" class="block mt-1 w-full" type="text" name="street" :value="old('street')"  autocomplete="street" />
                    <x-input-error :messages="$errors->get('street')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="barangay" :value="__('Barangay')" />
                    <x-text-input id="" class="block mt-1 w-full" type="text" name="barangay" :value="old('barangay')" required autocomplete="barangay" />
                    <x-input-error :messages="$errors->get('barangay')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="city" :value="__('City/Municipality')" />
                    <x-text-input id="" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required autocomplete="city" />
                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="province" :value="__('Province')" />
                    <x-text-input id="" class="block mt-1 w-full" type="text" name="province" :value="old('province')" required autocomplete="province" />
                    <x-input-error :messages="$errors->get('province')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="postal_code" :value="__('Postal Code')" />
                    <x-text-input id="" class="block mt-1 w-full" type="number" name="postal_code" :value="old('postal_code')" required autocomplete="postal_code" />
                    <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                </div>
                <div class="flex justify-between mt-4">
                    <button type="button" onclick="prevStep()" class="btn-back">Back</button>
                    <button type="button" onclick="nextStep()" class="btn-next">Next</button>
                </div>
            </div>

            <!-- Education Background -->
            <div class="step hidden">
                <div class="mt-4">
                    <x-input-label for="high_school" :value="__('High School')" />
                    <x-text-input id="" class="block mt-1 w-full" type="text" name="high_school" :value="old('high_school')" required autocomplete="high_school" />
                    <x-input-error :messages="$errors->get('high_school')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="HS_grad_date" :value="__('Year of Graduation')" />
                    <x-text-input id="" class="block mt-1 w-full" type="date" name="HS_grad_date" :value="old('HS_grad_date')" required autocomplete="HS_grad_date" />
                    <x-input-error :messages="$errors->get('HS_grad_date')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="Strand" :value="__('Strand')" />
                    <select id="" name="Strand" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                
                        <option value="">Select course</option>
                        <option value="TVL">TVL</option>
                        <option value="HUMSS">HUMSS</option>
                        <option value="STEM">STEM</option>
                        <option value="ABM">ABM</option>
                        <option value="GAS">GAS</option>
                        <option value="Arts & Design">Arts & Design</option>
                        <option value="Sports">Sports</option>

                    </select>
                    <x-input-error :messages="$errors->get('Strand')" class="mt-2" />
                </div>
            
                <!-- optional -->
                <div class="mt-4">
                    <x-input-label for="college" :value="__('College')" />
                    <x-text-input id="" class="block mt-1 w-full" type="text" name="college" :value="old('college')"  autocomplete="college" />
                    <x-input-error :messages="$errors->get('college')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="col_grad_date" :value="__('College Graduation Date  ')" />
                    <x-text-input id="" class="block mt-1 w-full" type="date" name="col_grad_date" :value="old('col_grad_date')"  autocomplete="col_grad_date" />
                    <x-input-error :messages="$errors->get('col_grad_date')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="prev_field" :value="__('Previos field of Study')" />
                    <x-text-input id="" class="block mt-1 w-full" type="text" name="prev_field" :value="old('prev_field')"  autocomplete="prev_field" />
                    <x-input-error :messages="$errors->get('prev_field')" class="mt-2" />

                </div>
                <div class="flex justify-between mt-4">
                    <button type="button" onclick="prevStep()" class="btn-back">Back</button>
                    <button type="button" onclick="nextStep()" class="btn-next">Next</button>
                </div>
            </div>

            <!-- //selected program and preferrences -->
            <div class="step hidden">
                <div class="mt-4">
                    <x-input-label for="program" :value="__('Program')" />
                    <select id="" name="program" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                
                        <option value="">Select course</option>
                        <option value="BSIT">Bachelor of Science in Information Technology</option>
                        <option value="BSCS">Bachelor of Science in Computer Science</option>
                        <option value="BSIS">Bachelor of Science in Information Systems</option>
                    </select>
                    <x-input-error :messages="$errors->get('program')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="preferred_time" :value="__('Preferred Time')" />
                    <select id="" name="preferred_time" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                
                        <option value="">Select Time</option>
                        <option value="morning">Morning</option>
                        <option value="afternoon">Afternoon</option>
                        <option value="evening">Evening</option>
                    </select>
                    <x-input-error :messages="$errors->get('preferred_time')" class="mt-2" />
                </div>
                <div class="flex justify-between mt-4">
                    <button type="button" onclick="prevStep()" class="btn-back">Back</button>
                    <button type="button" onclick="nextStep()" class="btn-next">Next</button>
                </div>
            </div>
            
            <!-- //password -->
                <div class="step hidden">
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
                <div class="flex justify-between mt-4">
                    <button type="button" onclick="prevStep()" class="btn-back">Back</button>
                    <x-primary-button class="ms-4">
                    {{ __('Register') }}
                </x-primary-button>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                
            </div>
        </form>
    </div>
    <style>
        .input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .btn-next {
            background: #4f46e5;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            margin-top: 12px;
        }

        .btn-back {
            background: gray;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
        }

        .step-indicator {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid gray;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .step-indicator.active {
            background: #4f46e5;
            color: white;
            border: none;
        }

        .step-indicator.done {
            background: #10b981;
            color: white;
            border: none;
        }
    </style>
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
                    ind.innerHTML = "✔";
                } else if (i === index) {
                    ind.classList.add('active');
                    ind.innerHTML = i + 1;
                } else {
                    ind.innerHTML = i + 1;
                }
            });

            progressBar.style.width = ((index + 1) / steps.length) * 100 + "%";
        }

        function validateStep() {
            let inputs = steps[currentStep].querySelectorAll("input, select, textarea");

            for (let input of inputs) {
                if (input.hasAttribute("required") && !input.value) {
                    input.classList.add("border-red-500");
                    alert("Please fill all required fields.");
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
</x-guest-layout>
