<x-guest-layout>
    @include('layouts.landing_nav')

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap');

        .lf-wrap {
            font-family: 'DM Sans', sans-serif;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .lf-card {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            border: 1px solid #e5e9f0;
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: 0 8px 32px rgba(13,43,86,0.10), 0 1.5px 4px rgba(13,43,86,0.06);
            animation: lfFadeUp 0.6s cubic-bezier(.22,.68,0,1.2) both;
        }

        @keyframes lfFadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .lf-brand {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 28px;
            padding-bottom: 22px;
            border-bottom: 1px solid #f0f2f7;
        }

        .lf-brand-logo {
            width: 38px; height: 38px;
            border-radius: 9px;
            background: linear-gradient(135deg, #0d2b56, #1d6fba);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .lf-brand-logo img { width: 100%; height: 100%; object-fit: contain; }

        .lf-brand-name {
            font-size: 0.72rem;
            font-weight: 600;
            color: #1a3a5c;
            line-height: 1.3;
        }

        .lf-brand-sub {
            font-size: 0.65rem;
            color: #7a93b4;
            margin-top: 1px;
        }

        .lf-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.55rem;
            font-weight: 700;
            color: #0d2b56;
            letter-spacing: -0.01em;
            margin-bottom: 4px;
        }

        .lf-subtitle {
            font-size: 0.82rem;
            color: #7a93b4;
            margin-bottom: 24px;
        }

        .lf-field { margin-bottom: 16px; }

        .lf-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #4a6080;
            margin-bottom: 6px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .lf-input-wrap { position: relative; }

        .lf-input {
            width: 100%;
            background: #f7f9fc !important;
            border: 1.5px solid #dce3ee !important;
            border-radius: 10px !important;
            padding: 10px 14px !important;
            font-size: 0.9rem !important;
            color: #1a2e44 !important;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .lf-input:focus {
            border-color: #1d6fba !important;
            background: #fff !important;
            box-shadow: 0 0 0 3px rgba(29,111,186,0.10) !important;
        }

        .lf-input-pw { padding-right: 42px !important; }

        .lf-eye {
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; padding: 2px;
            color: #a0b4cc;
            display: flex; align-items: center;
            transition: color 0.2s;
        }

        .lf-eye:hover { color: #1d6fba; }
        .lf-eye svg { width: 18px; height: 18px; }
        .lf-eye .icon-hide { display: none; }
        .lf-eye.is-visible .icon-show { display: none; }
        .lf-eye.is-visible .icon-hide { display: block; }

        .lf-row {
            display: flex; align-items: center; justify-content: space-between;
            margin: 16px 0 22px;
        }

        .lf-remember {
            display: flex; align-items: center; gap: 7px; cursor: pointer;
        }

        .lf-remember input[type="checkbox"] {
            appearance: none; -webkit-appearance: none;
            width: 16px; height: 16px;
            border-radius: 5px;
            border: 1.5px solid #dce3ee;
            background: #f7f9fc;
            cursor: pointer; flex-shrink: 0; position: relative;
            transition: background 0.2s, border-color 0.2s;
        }

        .lf-remember input[type="checkbox"]:checked {
            background: linear-gradient(135deg, #1d6fba, #0d2b56);
            border-color: transparent;
        }

        .lf-remember input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            width: 9px; height: 5px;
            border-left: 2px solid white;
            border-bottom: 2px solid white;
            transform: rotate(-45deg) translate(2px, 3px);
        }

        .lf-remember-text {
            font-size: 0.8rem;
            color: #5a7494;
        }

        .lf-forgot {
            font-size: 0.8rem;
            color: #1d6fba;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .lf-forgot:hover { color: #0d2b56; text-decoration: underline; }

        .lf-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #1d6fba, #0d2b56);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.01em;
            display: flex; align-items: center; justify-content: center; gap: 7px;
            box-shadow: 0 4px 16px rgba(13,43,86,0.22);
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
        }

        .lf-btn:hover {
            opacity: 0.93;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(13,43,86,0.28);
        }

        .lf-btn:active { transform: translateY(0); }

        .lf-btn svg {
            width: 15px; height: 15px;
            stroke: white; fill: none; stroke-width: 2.5;
        }
    </style>

    <div class="lf-wrap">
        <div class="lf-card">

            <!-- Brand -->
            <div class="lf-brand">
                <div class="lf-brand-logo">
                    <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="UIM logo">
                </div>
                <div>
                    <p class="lf-brand-name">University of International Mindanao</p>
                    <p class="lf-brand-sub">Enrollment Management System</p>
                </div>
            </div>

            <h1 class="lf-title">Welcome Back</h1>
            <p class="lf-subtitle">Sign in to your account to continue</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="lf-field">
                    <label for="email" class="lf-label">Email Address</label>
                    <div class="lf-input-wrap">
                        <input
                            id="email"
                            class="lf-input"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="you@uim.edu.ph"
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="lf-field">
                    <label for="password" class="lf-label">Password</label>
                    <div class="lf-input-wrap">
                        <input
                            id="password"
                            class="lf-input lf-input-pw"
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        />
                        <button type="button" class="lf-eye" id="togglePw" aria-label="Toggle password visibility">
                            <!-- Eye open -->
                            <svg class="icon-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <!-- Eye crossed -->
                            <svg class="icon-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember & Forgot -->
                <div class="lf-row">
                    <label class="lf-remember">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span class="lf-remember-text">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="lf-forgot" href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit" class="lf-btn">
                    {{ __('Log in') }}
                    <svg viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </button>

            </form>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('togglePw');
        const passwordInput = document.getElementById('password');

        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleBtn.classList.toggle('is-visible', isPassword);
        });
    </script>

</x-guest-layout>