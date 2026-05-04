<x-app-layout>
<div class="flex min-h-screen bg-slate-50">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 z-40 hidden lg:block">
        @include('layouts.admin_side_bar')
    </aside>

    <!-- MAIN -->
    <div class="flex-1 lg:ml-64 flex flex-col">

        <!-- NAVBAR -->
        <header class="sticky top-0 z-30 bg-white shadow-sm">
            @include('layouts.navigation')
        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-5xl mx-auto w-full space-y-6">

                <!-- PAGE HEADER -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Edit Student</h1>
                        <p class="mt-1 text-sm text-slate-500">Update student information and manage account status.</p>
                    </div>

                    <!-- STATUS BADGE -->
                    <div class="flex items-center gap-2">
                        @if($student->is_verified ?? false)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Verified
                            </span>
                        @elseif($student->is_withdrawn ?? false)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Withdrawn
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                Pending
                            </span>
                        @endif
                    </div>
                </div>

                <!-- ACTION BUTTONS (Verify / Withdraw) -->
                <div class="flex flex-col sm:flex-row gap-3">

                    <!-- VERIFY -->
                    <form method="POST" action="{{ route('admin.students.verify', $student) }}" class="inline"
                        onsubmit="return confirm('Are you sure you want to verify this student?')">
                        @csrf
                        
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm shadow-emerald-200 transition-all duration-150 w-full sm:w-auto justify-center">
                            <!-- Check icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Verify Student
                        </button>
                    </form>

                    <!-- WITHDRAW -->
                    <form method="POST" action="{{ route('admin.students.withdraw', $student) }}" class="inline"
                          onsubmit="return confirm('Are you sure you want to withdraw this student? This action may affect enrollment records.')">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-white hover:bg-red-50 active:scale-95 text-red-600 border border-red-200 hover:border-red-400 text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-150 w-full sm:w-auto justify-center">
                            <!-- X-circle icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Withdraw Student
                        </button>
                    </form>

                </div>

                <!-- FORM CARD -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">

                    <form method="POST" action="{{ route('admin.students.update', $student) }}">
                        @csrf
                        @method('PUT')

                        <!-- ── PERSONAL INFORMATION ── -->
                        <div class="flex items-center gap-3 mb-5">
                            <h3 class="text-base font-bold text-slate-800 uppercase tracking-widest text-xs">Personal Information</h3>
                            <div class="flex-1 h-px bg-slate-100"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">First Name <span class="text-red-400">*</span></label>
                                <x-text-input id="first_name" class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    name="first_name" :value="old('first_name', $student->profile->first_name)" required />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                    Middle Name <span class="font-normal normal-case text-slate-400">(optional)</span>
                                </label>
                                <x-text-input id="middle_name" class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    name="middle_name" :value="old('middle_name', $student->profile->middle_name)" />
                                <x-input-error :messages="$errors->get('middle_name')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Last Name <span class="text-red-400">*</span></label>
                                <x-text-input id="last_name" class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    name="last_name" :value="old('last_name', $student->profile->last_name)" required />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Email <span class="text-red-400">*</span></label>
                                <x-text-input id="email" type="email" name="email"
                                    class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    value="{{ old('email', optional($student->profile->user)->email) }}" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Sex <span class="text-red-400">*</span></label>
                                <select name="sex"
                                    class="w-full mt-0 rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 pr-8 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                                    style="background-image:url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2712%27 height=%278%27 viewBox=%270 0 12 8%27%3E%3Cpath fill=%27%236b7280%27 d=%27M6 8L0 0h12z%27/%3E%3C/svg%3E');background-repeat:no-repeat;background-position:right 0.85rem center;-webkit-appearance:none;appearance:none;">
                                    <option value="male"   {{ old('sex', $student->profile->sex) == 'male'   ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('sex', $student->profile->sex) == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <x-input-error :messages="$errors->get('sex')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Birthdate</label>
                                <x-text-input id="birthdate" type="date"
                                    class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    name="birthdate" :value="old('birthdate', $student->profile->birthdate?->format('Y-m-d'))" />
                                <x-input-error :messages="$errors->get('birthdate')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Phone Number</label>
                                <x-text-input id="phone_number"
                                    class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    name="phone_number" :value="old('phone_number', $student->profile->phone_number)" />
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Program</label>
                                <select name="program"
                                    class="w-full mt-0 rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 pr-8 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                                    style="background-image:url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2712%27 height=%278%27 viewBox=%270 0 12 8%27%3E%3Cpath fill=%27%236b7280%27 d=%27M6 8L0 0h12z%27/%3E%3C/svg%3E');background-repeat:no-repeat;background-position:right 0.85rem center;-webkit-appearance:none;appearance:none;">
                                    @foreach(\App\Models\Program::all() as $program)
                                        <option value="{{ $program->id }}"
                                            {{ old('program', $student->program) == $program->id ? 'selected' : '' }}>
                                            {{ $program->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('program')" class="mt-1 text-xs" />
                            </div>

                        </div>

                        <!-- ── PASSWORD ── -->
                        <div class="flex items-center gap-3 mt-8 mb-5">
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-widest">Password</h3>
                            <div class="flex-1 h-px bg-slate-100"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">
                                    New Password <span class="font-normal normal-case text-slate-400">(leave blank to keep current)</span>
                                </label>
                                <div class="relative">
                                    <input id="password" name="password" type="password" autocomplete="new-password"
                                        class="w-full rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 pr-10 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition outline-none" />
                                    <button type="button" onclick="togglePassword('password', 'eye1')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition-colors focus:outline-none"
                                        tabindex="-1" aria-label="Toggle password visibility">
                                        <svg id="eye1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Confirm New Password</label>
                                <div class="relative">
                                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                                        class="w-full rounded-lg border border-slate-200 bg-slate-50 text-sm px-3 py-2.5 pr-10 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition outline-none" />
                                    <button type="button" onclick="togglePassword('password_confirmation', 'eye2')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition-colors focus:outline-none"
                                        tabindex="-1" aria-label="Toggle password visibility">
                                        <svg id="eye2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
                            </div>

                        </div>

                        <!-- ── ADDRESS ── -->
                        <div class="flex items-center gap-3 mt-8 mb-5">
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-widest">Address</h3>
                            <div class="flex-1 h-px bg-slate-100"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">House Number</label>
                                <x-text-input name="house_number"
                                    class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    placeholder="e.g. 12B" :value="old('house_number', $student->profile->address->house_number ?? '')" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Street</label>
                                <x-text-input name="street"
                                    class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    placeholder="Street name" :value="old('street', $student->profile->address->street ?? '')" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Barangay</label>
                                <x-text-input name="barangay"
                                    class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    placeholder="Barangay" :value="old('barangay', $student->profile->address->barangay ?? '')" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">City / Municipality</label>
                                <x-text-input name="city"
                                    class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    placeholder="City" :value="old('city', $student->profile->address->city ?? '')" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Province</label>
                                <x-text-input name="province"
                                    class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    placeholder="Province" :value="old('province', $student->profile->address->province ?? '')" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Postal Code</label>
                                <x-text-input name="postal_code" inputmode="numeric" maxlength="4"
                                    class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    placeholder="0000" :value="old('postal_code', $student->profile->address->postal_code ?? '')" />
                            </div>

                        </div>

                        <!-- ── EDUCATIONAL BACKGROUND ── -->
                        <div class="flex items-center gap-3 mt-8 mb-5">
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-widest">Educational Background</h3>
                            <div class="flex-1 h-px bg-slate-100"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">School Name</label>
                                <x-text-input name="school_name"
                                    class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    placeholder="School / University"
                                    :value="old('school_name', optional($student->educationalBackground->first())->school)" />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Year Graduated</label>
                                <x-text-input type="number" name="year_graduated" inputmode="numeric" maxlength="4"
                                    class="w-full mt-0 rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition"
                                    placeholder="e.g. 2023"
                                    :value="old('year_graduated', optional($student->educationalBackground->first())->grad_date ? date('Y', strtotime($student->educationalBackground->first()->grad_date)) : '')" />
                            </div>

                        </div>

                        <!-- ── FORM ACTIONS ── -->
                        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-sm font-semibold px-7 py-2.5 rounded-xl shadow-sm shadow-indigo-200 transition-all duration-150 w-full sm:w-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Student
                            </button>

                            <a href="{{ route('admin.students') }}"
                                class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold px-6 py-2.5 rounded-xl transition-all duration-150 w-full sm:w-auto active:scale-95">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>

            </div>
        </main>

    </div>
</div>

<script>
    // Icon paths for eye-open and eye-off states
    const eyeOpen = `
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    `;
    const eyeOff = `
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
    `;

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.innerHTML = isHidden ? eyeOff : eyeOpen;
    }
</script>

</x-app-layout>