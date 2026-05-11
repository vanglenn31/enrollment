<x-app-layout>

@php
    $role = Auth::user()->role->role ?? 'student'; // adjust 'role' to match your column name
    $isAdmin   = $role === 'admin';
    $isTeacher = $role === 'teacher';
    $isStudent = $role === 'student';
@endphp

<div class="flex min-h-screen bg-gray-100">

    <!-- SIDEBAR — rendered based on role -->
    <aside class="hidden lg:block fixed inset-y-0 left-0 w-fit ">
        @if($isAdmin)
            @include('layouts.admin_side_bar')
        @else
            @include('layouts.student_side_bar')
        @endif
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="flex-1 w-full lg:ml-64 flex flex-col">

        <!-- NAV -->
        <header class="sticky top-0 z-50 bg-white shadow-sm">
            @include('layouts.navigation')
        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <div class="min-h-screen bg-gray-100 flex items-start justify-center py-8">
                <div class="w-full max-w-3xl space-y-6">

                    <!-- PAGE HEADER -->
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Account Settings</h1>
                        <p class="mt-1 text-sm text-gray-500">Manage your account security and preferences.</p>
                    </div>

                    <!-- PROFILE CARD (read-only) -->
                    <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-6">
                        <h2 class="text-base font-semibold text-gray-700 mb-4">Profile Information</h2>

                        <div class="flex items-center gap-4">
                            <!-- Avatar -->
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white font-bold text-lg shrink-0
                                {{ $isAdmin ? 'bg-violet-600' : ($isTeacher ? 'bg-emerald-600' : 'bg-indigo-600') }}">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>

                            <div>
                                <p class="font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                                <!-- Role badge -->
                                <span class="mt-1 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                                    {{ $isAdmin   ? 'bg-violet-100 text-violet-700' :
                                       ($isTeacher ? 'bg-emerald-100 text-emerald-700' :
                                                     'bg-indigo-100 text-indigo-700') }}">
                                    {{ Auth::user()->role->role ?? 'student' }}
                                </span>
                            </div>

                            @if(Auth::user()->email_verified_at)
                                <span class="ml-auto inline-flex items-center gap-1.5 text-xs font-semibold bg-green-100 text-green-700 px-3 py-1.5 rounded-full">
                                    ✓ Verified
                                </span>
                            @else
                                <span class="ml-auto inline-flex items-center gap-1.5 text-xs font-semibold bg-orange-100 text-orange-700 px-3 py-1.5 rounded-full">
                                    Unverified
                                </span>
                            @endif
                        </div>

                        <p class="mt-4 text-xs text-gray-400">
                            To update your name or email address, please contact your system administrator.
                        </p>
                    </div>

                    <!-- UPDATE PASSWORD -->
                    <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-6">
                        <h2 class="text-base font-semibold text-gray-700 mb-1">Update Password</h2>
                        <p class="text-sm text-gray-500 mb-5">
                            Use a long, random password to keep your account secure.
                        </p>

                        @if (session('status') === 'password-updated')
                            <div class="mb-5 bg-green-50 border border-green-200 rounded-2xl px-5 py-4 text-sm text-green-700">
                                ✓ Password updated successfully.
                            </div>
                        @endif

                        @if ($errors->updatePassword->any())
                            <div class="mb-5 bg-red-50 border border-red-200 rounded-2xl px-4 py-3 text-sm text-red-700 space-y-1">
                                @foreach ($errors->updatePassword->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">
                                    Current Password
                                </label>
                                <input
                                    id="current_password"
                                    type="password"
                                    name="current_password"
                                    autocomplete="current-password"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                @if ($errors->updatePassword->get('current_password'))
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $errors->updatePassword->first('current_password') }}
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    New Password
                                </label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    autocomplete="new-password"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                @if ($errors->updatePassword->get('password'))
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $errors->updatePassword->first('password') }}
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    Confirm New Password
                                </label>
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                @if ($errors->updatePassword->get('password_confirmation'))
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $errors->updatePassword->first('password_confirmation') }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-700 transition-colors">
                                    Update Password
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </main>

    </div>

</div>

</x-app-layout>