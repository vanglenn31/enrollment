<x-guest-layout>

    @include('layouts.landing_nav')

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'DM Sans', sans-serif; }
        h1, h2 { font-family: 'Playfair Display', serif; }
        .step { animation: stepFade .25s ease both; }
        @keyframes stepFade {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%236b7280' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.85rem center;
            -webkit-appearance: none;
            appearance: none;
        }
        #progressBar { transition: width .4s cubic-bezier(.4,0,.2,1); }
        #toast { transition: opacity .25s, transform .25s; }
        #toast.hide { opacity: 0; transform: translateX(-50%) translateY(8px); pointer-events: none; }
    </style>

    <div class="min-h-screen bg-slate-50 py-10 px-4">
        <div class="max-w-2xl mx-auto">

            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg,#0d2b56,#1d6fba);">
                        <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="UIM" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-blue-900 leading-tight">University of International Mindanao</p>
                        <p class="text-[11px] text-slate-400">Enrollment Management System</p>
                    </div>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight leading-tight">
                    Enrollment Application
                </h1>
                <p class="mt-1 text-sm text-slate-500">Fill out all steps to complete your application.</p>
            </div>

            {{-- Progress bar --}}
            <div class="w-full h-1.5 rounded-full mb-8 overflow-hidden bg-slate-200">
                <div id="progressBar" class="h-full rounded-full" style="width:20%; background: linear-gradient(90deg,#0d2b56,#1d6fba);"></div>
            </div>

            {{-- Step indicators --}}
            <div class="relative flex justify-between items-start mb-10">
                <div class="absolute top-4 left-[5%] right-[5%] h-px bg-slate-200 z-0"></div>
                @foreach([['1','Personal'],['2','Academic'],['3','Program'],['4','Address'],['5','Account']] as [$num,$label])
                <div class="step-item flex flex-col items-center gap-1.5 z-10 {{ $loop->first ? 'active':'' }}">
                    <div class="step-bubble w-9 h-9 rounded-full border-2 border-slate-200 bg-white flex items-center justify-center text-xs font-semibold text-slate-400 shadow-sm transition-all duration-300">
                        {{ $num }}
                    </div>
                    <span class="step-label text-[10px] font-medium text-slate-400 tracking-wide hidden sm:block transition-colors duration-300">
                        {{ $label }}
                    </span>
                </div>
                @endforeach
            </div>

            {{-- Form card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-10">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- ───── STEP 1 · Personal ───── --}}
                    <div class="step">
                        <h2 class="text-xl font-semibold text-slate-800 pb-4 mb-6 border-b border-slate-100">Personal Information</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">First Name <span class="text-red-400">*</span></label>
                                <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-1 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Middle Name <span class="normal-case font-normal text-slate-400">(opt.)</span></label>
                                <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="text" name="middle_name" :value="old('middle_name')" autocomplete="additional-name" />
                                <x-input-error :messages="$errors->get('middle_name')" class="mt-1 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Suffix <span class="normal-case font-normal text-slate-400">(opt.)</span></label>
                                <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="text" name="suffix" :value="old('suffix')" placeholder="Jr., Sr…" autocomplete="off" />
                                <x-input-error :messages="$errors->get('suffix')" class="mt-1 text-xs" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Last Name <span class="text-red-400">*</span></label>
                                <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-1 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Sex <span class="text-red-400">*</span></label>
                                <select name="sex" required
                                    class="block w-full rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 pr-8 focus:border-blue-700 focus:ring-2 focus:ring-blue-700 focus:bg-white transition">
                                    <option value="" disabled {{ old('sex')?'':'selected' }}>Select…</option>
                                    <option value="Male"   {{ old('sex')=='Male'   ?'selected':'' }}>Male</option>
                                    <option value="Female" {{ old('sex')=='Female' ?'selected':'' }}>Female</option>
                                </select>
                                <x-input-error :messages="$errors->get('sex')" class="mt-1 text-xs" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Date of Birth <span class="text-red-400">*</span></label>
                            <x-text-input class="block w-full sm:w-52 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                type="date" name="birthdate" :value="old('birthdate')" required autocomplete="bday" />
                            <x-input-error :messages="$errors->get('birthdate')" class="mt-1 text-xs" />
                        </div>

                        <div class="flex items-center gap-3 my-6">
                            <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Contact</span>
                            <div class="flex-1 h-px bg-slate-100"></div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Email <span class="text-red-400">*</span></label>
                                <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="email" name="email" :value="old('email')" required autocomplete="email" />
                                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Phone Number <span class="text-red-400">*</span></label>
                                <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="tel" name="phone_number" :value="old('phone_number')" required autocomplete="tel" placeholder="09XXXXXXXXX" />
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-1 text-xs" />
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" onclick="nextStep()"
                                class="inline-flex items-center gap-2 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition-all duration-150 active:scale-95 hover:opacity-90"
                                style="background: linear-gradient(135deg,#1d6fba,#0d2b56);">
                                Next
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- ───── STEP 2 · Academic ───── --}}
                    <div class="step hidden">
                        <h2 class="text-xl font-semibold text-slate-800 pb-4 mb-6 border-b border-slate-100">Academic Background</h2>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">High School <span class="text-red-400">*</span></label>
                            <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                type="text" name="high_school" :value="old('high_school')" required autocomplete="organization" />
                            <x-input-error :messages="$errors->get('high_school')" class="mt-1 text-xs" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Year of Graduation <span class="text-red-400">*</span></label>
                                <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="date" name="HS_grad_date" :value="old('HS_grad_date')" required autocomplete="off" />
                                <x-input-error :messages="$errors->get('HS_grad_date')" class="mt-1 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Strand <span class="text-red-400">*</span></label>
                                <select name="Strand"
                                    class="block w-full rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 pr-8 focus:border-blue-700 focus:ring-2 focus:ring-blue-700 focus:bg-white transition">
                                    <option value="" disabled {{ old('Strand')?'':'selected' }}>Select strand…</option>
                                    @foreach(['TVL','HUMSS','STEM','ABM','GAS','Arts & Design','Sports'] as $s)
                                        <option value="{{ $s }}" {{ old('Strand')==$s?'selected':'' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('Strand')" class="mt-1 text-xs" />
                            </div>
                        </div>

                        <div class="flex items-center gap-3 my-6">
                            <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">College</span>
                            <span class="text-[10px] text-slate-400">(if applicable)</span>
                            <div class="flex-1 h-px bg-slate-100"></div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">College <span class="font-normal normal-case text-slate-400">(optional)</span></label>
                            <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                type="text" name="college" :value="old('college')" autocomplete="organization" />
                            <x-input-error :messages="$errors->get('college')" class="mt-1 text-xs" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Graduation Date <span class="font-normal normal-case text-slate-400">(optional)</span></label>
                                <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="date" name="col_grad_date" :value="old('col_grad_date')" autocomplete="off" />
                                <x-input-error :messages="$errors->get('col_grad_date')" class="mt-1 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Previous Field of Study <span class="font-normal normal-case text-slate-400">(optional)</span></label>
                                <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="text" name="prev_field" :value="old('prev_field')" autocomplete="off" />
                                <x-input-error :messages="$errors->get('prev_field')" class="mt-1 text-xs" />
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevStep()" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold px-5 py-2.5 rounded-lg transition-all duration-150 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back
                            </button>
                            <button type="button" onclick="nextStep()"
                                class="inline-flex items-center gap-2 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition-all duration-150 active:scale-95 hover:opacity-90"
                                style="background: linear-gradient(135deg,#1d6fba,#0d2b56);">
                                Next <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- ───── STEP 3 · Program ───── --}}
                    <div class="step hidden">
                        <h2 class="text-xl font-semibold text-slate-800 pb-4 mb-6 border-b border-slate-100">Program &amp; Preferences</h2>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Program <span class="text-red-400">*</span></label>
                            <select name="program" required
                                class="block w-full rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 pr-8 focus:border-blue-700 focus:ring-2 focus:ring-blue-700 focus:bg-white transition">
                                <option value="" disabled {{ old('program')?'':'selected' }}>Select program…</option>
                                <option value="BSIT" {{ old('program')=='BSIT'?'selected':'' }}>Bachelor of Science in Information Technology</option>
                                <option value="BSCS" {{ old('program')=='BSCS'?'selected':'' }}>Bachelor of Science in Computer Science</option>
                                <option value="BSIS" {{ old('program')=='BSIS'?'selected':'' }}>Bachelor of Science in Information Systems</option>
                            </select>
                            <x-input-error :messages="$errors->get('program')" class="mt-1 text-xs" />
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Preferred Class Time <span class="text-red-400">*</span></label>
                            <select name="preferred_time" required
                                class="block w-full sm:w-52 rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 pr-8 focus:border-blue-700 focus:ring-2 focus:ring-blue-700 focus:bg-white transition">
                                <option value="" disabled {{ old('preferred_time')?'':'selected' }}>Select time…</option>
                                <option value="morning"   {{ old('preferred_time')=='morning'  ?'selected':'' }}>Morning</option>
                                <option value="afternoon" {{ old('preferred_time')=='afternoon'?'selected':'' }}>Afternoon</option>
                                <option value="evening"   {{ old('preferred_time')=='evening'  ?'selected':'' }}>Evening</option>
                            </select>
                            <x-input-error :messages="$errors->get('preferred_time')" class="mt-1 text-xs" />
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevStep()" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold px-5 py-2.5 rounded-lg transition-all duration-150 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back
                            </button>
                            <button type="button" onclick="nextStep()"
                                class="inline-flex items-center gap-2 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition-all duration-150 active:scale-95 hover:opacity-90"
                                style="background: linear-gradient(135deg,#1d6fba,#0d2b56);">
                                Next <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- ───── STEP 4 · Address ───── --}}
                    <div class="step hidden">
                        <h2 class="text-xl font-semibold text-slate-800 pb-4 mb-6 border-b border-slate-100">Home Address</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">House / Unit No. <span class="font-normal normal-case text-slate-400">(optional)</span></label>
                                <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="text" name="house_number" :value="old('house_number')" autocomplete="address-line1" />
                                <x-input-error :messages="$errors->get('house_number')" class="mt-1 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Street <span class="font-normal normal-case text-slate-400">(optional)</span></label>
                                <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="text" name="street" :value="old('street')" autocomplete="address-line2" />
                                <x-input-error :messages="$errors->get('street')" class="mt-1 text-xs" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Barangay <span class="text-red-400">*</span></label>
                            <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                type="text" name="barangay" :value="old('barangay')" required autocomplete="address-level3" />
                            <x-input-error :messages="$errors->get('barangay')" class="mt-1 text-xs" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">City / Municipality <span class="text-red-400">*</span></label>
                                <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="text" name="city" :value="old('city')" required autocomplete="address-level2" />
                                <x-input-error :messages="$errors->get('city')" class="mt-1 text-xs" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Province <span class="text-red-400">*</span></label>
                                <x-text-input class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="text" name="province" :value="old('province')" required autocomplete="address-level1" />
                                <x-input-error :messages="$errors->get('province')" class="mt-1 text-xs" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Postal Code <span class="text-red-400">*</span></label>
                            <x-text-input class="block w-28 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                type="text" inputmode="numeric" name="postal_code" :value="old('postal_code')" required autocomplete="postal-code" maxlength="4" />
                            <x-input-error :messages="$errors->get('postal_code')" class="mt-1 text-xs" />
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevStep()" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold px-5 py-2.5 rounded-lg transition-all duration-150 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back
                            </button>
                            <button type="button" onclick="nextStep()"
                                class="inline-flex items-center gap-2 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition-all duration-150 active:scale-95 hover:opacity-90"
                                style="background: linear-gradient(135deg,#1d6fba,#0d2b56);">
                                Next <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- ───── STEP 5 · Account ───── --}}
                    <div class="step hidden">
                        <h2 class="text-xl font-semibold text-slate-800 pb-4 mb-6 border-b border-slate-100">Create Your Account</h2>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                Password <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <x-text-input id="password"
                                    class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 pr-10 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="password" name="password" required autocomplete="new-password" onkeyup="checkPasswords()" />
                                <button type="button"
                                    onclick="togglePassword('password', 'eyeOpen1', 'eyeClosed1')"
                                    class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-blue-700 transition duration-200 active:scale-90">
                                    <svg id="eyeOpen1" class="w-5 h-5 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg id="eyeClosed1" class="w-5 h-5 hidden transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.592M6.228 6.228A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.132 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 6L3 3"/>
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                Confirm Password <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <x-text-input id="password_confirmation"
                                    class="block w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 pr-10 focus:border-blue-700 focus:ring-blue-700 focus:bg-white transition"
                                    type="password" name="password_confirmation" required autocomplete="new-password" onkeyup="checkPasswords()" />
                                <button type="button"
                                    onclick="togglePassword('password_confirmation', 'eyeOpen2', 'eyeClosed2')"
                                    class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-blue-700 transition duration-200 active:scale-90">
                                    <svg id="eyeOpen2" class="w-5 h-5 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg id="eyeClosed2" class="w-5 h-5 hidden transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.592M6.228 6.228A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.132 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 6L3 3"/>
                                    </svg>
                                </button>
                            </div>
                            <p id="passwordMessage" class="mt-1 text-xs transition-all duration-300"></p>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevStep()"
                                class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold px-5 py-2.5 rounded-lg transition-all duration-150 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                Back
                            </button>
                            <button type="submit" id="submitBtn" disabled
                                class="inline-flex items-center gap-2 text-white text-sm font-semibold px-7 py-2.5 rounded-lg shadow-md transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed hover:opacity-90"
                                style="background: linear-gradient(135deg,#1d6fba,#0d2b56);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Submit Application
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            <p class="text-center text-sm text-slate-400 mt-5">
                Already have an account?
                <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color:#1d6fba;">Sign in</a>
            </p>

        </div>
    </div>

    {{-- Toast --}}
    <div id="toast"
         class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-red-500 text-white text-sm font-medium px-5 py-2.5 rounded-full shadow-lg shadow-red-200 z-50 hide">
        Please fill in all required fields.
    </div>

    <script>
        let currentStep = 0;
        const steps  = document.querySelectorAll('.step');
        const items  = document.querySelectorAll('.step-item');
        const bar    = document.getElementById('progressBar');
        const toast  = document.getElementById('toast');
        let toastTimer;

        function showStep(i) {
            steps.forEach((s, idx) => s.classList.toggle('hidden', idx !== i));

            items.forEach((item, idx) => {
                const bubble = item.querySelector('.step-bubble');
                const label  = item.querySelector('.step-label');

                bubble.className = 'step-bubble w-9 h-9 rounded-full border-2 flex items-center justify-center text-xs font-semibold shadow-sm transition-all duration-300';

                if (idx < i) {
                    bubble.classList.add('bg-emerald-500', 'border-emerald-500', 'text-white');
                    bubble.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
                    if (label) { label.classList.remove('text-slate-400','text-blue-800'); label.classList.add('text-emerald-500'); }
                } else if (idx === i) {
                    bubble.style.background = 'linear-gradient(135deg,#1d6fba,#0d2b56)';
                    bubble.style.borderColor = '#0d2b56';
                    bubble.classList.add('text-white', 'ring-4', 'ring-blue-100');
                    bubble.textContent = idx + 1;
                    if (label) { label.classList.remove('text-slate-400','text-emerald-500'); label.classList.add('text-blue-800'); }
                } else {
                    bubble.classList.add('border-slate-200', 'bg-white', 'text-slate-400');
                    bubble.textContent = idx + 1;
                    if (label) { label.classList.remove('text-blue-800','text-emerald-500'); label.classList.add('text-slate-400'); }
                }
            });

            bar.style.width = ((i + 1) / steps.length * 100) + '%';
            document.querySelector('.bg-white.rounded-2xl').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function showToast(msg) {
            toast.textContent = msg || 'Please fill in all required fields.';
            toast.classList.remove('hide');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => toast.classList.add('hide'), 3200);
        }

        function validateStep() {
            const required = steps[currentStep].querySelectorAll('input[required], select[required]');
            let ok = true;
            required.forEach(el => {
                el.classList.remove('border-red-400', 'ring-red-100');
                if (!el.value.trim()) {
                    el.classList.add('border-red-400', 'ring-2', 'ring-red-100');
                    ok = false;
                }
            });
            if (!ok) showToast('Please fill in all required fields.');
            return ok;
        }

        function nextStep() {
            if (!validateStep()) return;
            if (currentStep < steps.length - 1) { currentStep++; showStep(currentStep); }
        }
        function prevStep() {
            if (currentStep > 0) { currentStep--; showStep(currentStep); }
        }

        showStep(0);

        function togglePassword(inputId, eyeOpenId, eyeClosedId) {
            const input = document.getElementById(inputId);
            const eyeOpen = document.getElementById(eyeOpenId);
            const eyeClosed = document.getElementById(eyeClosedId);
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
                eyeClosed.classList.add('scale-110');
                setTimeout(() => eyeClosed.classList.remove('scale-110'), 200);
            } else {
                input.type = 'password';
                eyeClosed.classList.add('hidden');
                eyeOpen.classList.remove('hidden');
                eyeOpen.classList.add('scale-110');
                setTimeout(() => eyeOpen.classList.remove('scale-110'), 200);
            }
        }

        function checkPasswords() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const message = document.getElementById('passwordMessage');
            const submitBtn = document.getElementById('submitBtn');

            if (password === '' || confirmPassword === '') {
                message.textContent = '';
                submitBtn.disabled = true;
                return;
            }
            if (password.length < 8) {
                message.textContent = '✕ Password must be at least 8 characters';
                message.classList.remove('text-green-500');
                message.classList.add('text-red-500');
                submitBtn.disabled = true;
                return;
            }
            if (password === confirmPassword) {
                message.textContent = '✓ Passwords match';
                message.classList.remove('text-red-500');
                message.classList.add('text-green-500');
                submitBtn.disabled = false;
                submitBtn.classList.add('scale-105');
                setTimeout(() => submitBtn.classList.remove('scale-105'), 200);
            } else {
                message.textContent = '✕ Passwords do not match';
                message.classList.remove('text-green-500');
                message.classList.add('text-red-500');
                submitBtn.disabled = true;
            }
        }
    </script>

</x-guest-layout>