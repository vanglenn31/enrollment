<x-guest-layout>
    @include('layouts.landing_nav')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap');

        .rp-wrap {
            font-family: 'DM Sans', sans-serif;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .rp-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border: 1px solid #e5e9f0;
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: 0 8px 32px rgba(13,43,86,0.10), 0 1.5px 4px rgba(13,43,86,0.06);
            animation: rpFadeUp 0.6s cubic-bezier(.22,.68,0,1.2) both;
        }

        @keyframes rpFadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Brand */
        .rp-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
            padding-bottom: 22px;
            border-bottom: 1px solid #f0f2f7;
        }

        .rp-brand-logo {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            background: linear-gradient(135deg, #0d2b56, #1d6fba);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rp-brand-logo img { width: 100%; height: 100%; object-fit: contain; }

        .rp-brand-name {
            font-size: 0.72rem;
            font-weight: 600;
            color: #1a3a5c;
            line-height: 1.3;
        }

        .rp-brand-sub {
            font-size: 0.65rem;
            color: #7a93b4;
            margin-top: 1px;
        }

        /* Icon badge */
        .rp-icon-badge {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #e8f1fb 0%, #d4e8f9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            border: 1.5px solid #c8dff4;
        }

        .rp-icon-badge svg {
            width: 26px;
            height: 26px;
            stroke: #1d6fba;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Headings */
        .rp-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.55rem;
            font-weight: 700;
            color: #0d2b56;
            letter-spacing: -0.01em;
            margin-bottom: 4px;
        }

        .rp-subtitle {
            font-size: 0.82rem;
            color: #7a93b4;
            margin-bottom: 28px;
            line-height: 1.5;
        }

        /* Fields */
        .rp-field { margin-bottom: 16px; }

        .rp-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #4a6080;
            margin-bottom: 6px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .rp-input-wrap { position: relative; }

        .rp-input {
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
            box-sizing: border-box;
        }

        .rp-input:focus {
            border-color: #1d6fba !important;
            background: #fff !important;
            box-shadow: 0 0 0 3px rgba(29,111,186,0.10) !important;
        }

        .rp-input-pw { padding-right: 42px !important; }

        /* Eye toggle */
        .rp-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 2px;
            color: #a0b4cc;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .rp-eye:hover { color: #1d6fba; }
        .rp-eye svg { width: 18px; height: 18px; }
        .rp-eye .icon-hide { display: none; }
        .rp-eye.is-visible .icon-show { display: none; }
        .rp-eye.is-visible .icon-hide { display: block; }

        /* Password strength bar */
        .rp-strength {
            margin-top: 8px;
        }

        .rp-strength-bars {
            display: flex;
            gap: 4px;
            margin-bottom: 4px;
        }

        .rp-strength-bar {
            flex: 1;
            height: 3px;
            border-radius: 2px;
            background: #e5e9f0;
            transition: background 0.3s ease;
        }

        .rp-strength-label {
            font-size: 0.7rem;
            font-weight: 500;
            color: #a0b4cc;
            transition: color 0.3s;
        }

        /* Strength levels */
        .strength-weak .rp-strength-bar:nth-child(1) { background: #ef4444; }
        .strength-fair .rp-strength-bar:nth-child(1),
        .strength-fair .rp-strength-bar:nth-child(2) { background: #f59e0b; }
        .strength-good .rp-strength-bar:nth-child(1),
        .strength-good .rp-strength-bar:nth-child(2),
        .strength-good .rp-strength-bar:nth-child(3) { background: #3b82f6; }
        .strength-strong .rp-strength-bar { background: #10b981; }

        .strength-weak .rp-strength-label { color: #ef4444; }
        .strength-fair .rp-strength-label { color: #f59e0b; }
        .strength-good .rp-strength-label { color: #3b82f6; }
        .strength-strong .rp-strength-label { color: #10b981; }

        /* Divider */
        .rp-divider {
            height: 1px;
            background: #f0f2f7;
            margin: 20px 0;
        }

        /* Submit button */
        .rp-btn {
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            box-shadow: 0 4px 16px rgba(13,43,86,0.22);
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            margin-top: 22px;
        }

        .rp-btn:hover {
            opacity: 0.93;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(13,43,86,0.28);
        }

        .rp-btn:active { transform: translateY(0); }

        .rp-btn svg {
            width: 15px;
            height: 15px;
            stroke: white;
            fill: none;
            stroke-width: 2.5;
        }

        /* Back to login link */
        .rp-back {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            margin-top: 18px;
            font-size: 0.8rem;
            color: #7a93b4;
            text-decoration: none;
            transition: color 0.2s;
        }

        .rp-back svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
        }

        .rp-back:hover { color: #1d6fba; }

        /* Match indicator for confirm field */
        .rp-match-hint {
            font-size: 0.72rem;
            margin-top: 5px;
            font-weight: 500;
            min-height: 16px;
            transition: color 0.2s;
        }

        .rp-match-ok  { color: #10b981; }
        .rp-match-err { color: #ef4444; }
    </style>

    <div class="rp-wrap">
        <div class="rp-card">

            <!-- Brand -->
            <div class="rp-brand">
                <div class="rp-brand-logo">
                    <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="UIM logo">
                </div>
                <div>
                    <p class="rp-brand-name">University of International Mindanao</p>
                    <p class="rp-brand-sub">Enrollment Management System</p>
                </div>
            </div>

            <!-- Icon badge -->
            <div class="rp-icon-badge">
                <svg viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>

            <h1 class="rp-title">Reset Password</h1>
            <p class="rp-subtitle">Choose a strong new password for your account.</p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Hidden token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div class="rp-field">
                    <label for="email" class="rp-label">Email Address</label>
                    <div class="rp-input-wrap">
                        <input
                            id="email"
                            class="rp-input"
                            type="email"
                            name="email"
                            value="{{ old('email', $request->email) }}"
                            placeholder="you@uim.edu.ph"
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="rp-divider"></div>

                <!-- New Password -->
                <div class="rp-field">
                    <label for="password" class="rp-label">New Password</label>
                    <div class="rp-input-wrap">
                        <input
                            id="password"
                            class="rp-input rp-input-pw"
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                        />
                        <button type="button" class="rp-eye" id="togglePw" aria-label="Toggle password visibility">
                            <svg class="icon-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="icon-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Strength meter -->
                    <div class="rp-strength" id="strengthMeter" style="display:none;">
                        <div class="rp-strength-bars">
                            <div class="rp-strength-bar"></div>
                            <div class="rp-strength-bar"></div>
                            <div class="rp-strength-bar"></div>
                            <div class="rp-strength-bar"></div>
                        </div>
                        <span class="rp-strength-label" id="strengthLabel"></span>
                    </div>

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="rp-field">
                    <label for="password_confirmation" class="rp-label">Confirm Password</label>
                    <div class="rp-input-wrap">
                        <input
                            id="password_confirmation"
                            class="rp-input rp-input-pw"
                            type="password"
                            name="password_confirmation"
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                        />
                        <button type="button" class="rp-eye" id="toggleConfirm" aria-label="Toggle confirm password visibility">
                            <svg class="icon-show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="icon-hide" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    <p class="rp-match-hint" id="matchHint"></p>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Submit -->
                <button type="submit" class="rp-btn">
                    {{ __('Reset Password') }}
                    <svg viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>

            </form>

            <!-- Back to login -->
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="rp-back">
                    <svg viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Login
                </a>
            @endif

        </div>
    </div>

    <script>
        /* ── Password visibility toggles ── */
        function makeToggle(btnId, inputId) {
            const btn   = document.getElementById(btnId);
            const input = document.getElementById(inputId);
            btn.addEventListener('click', () => {
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.classList.toggle('is-visible', show);
            });
        }
        makeToggle('togglePw',      'password');
        makeToggle('toggleConfirm', 'password_confirmation');

        /* ── Password strength meter ── */
        const pwInput      = document.getElementById('password');
        const strengthMeter = document.getElementById('strengthMeter');
        const strengthLabel = document.getElementById('strengthLabel');
        const strengthLevels = ['strength-weak', 'strength-fair', 'strength-good', 'strength-strong'];
        const strengthNames  = ['Weak', 'Fair', 'Good', 'Strong'];

        function getStrength(val) {
            let score = 0;
            if (val.length >= 8)  score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;
            return score - 1; // 0-3
        }

        pwInput.addEventListener('input', () => {
            const val = pwInput.value;
            if (!val) { strengthMeter.style.display = 'none'; return; }
            strengthMeter.style.display = 'block';
            const level = Math.max(0, getStrength(val));
            strengthLevels.forEach(c => strengthMeter.classList.remove(c));
            strengthMeter.classList.add(strengthLevels[level]);
            strengthLabel.textContent = strengthNames[level];
            checkMatch();
        });

        /* ── Password match indicator ── */
        const confirmInput = document.getElementById('password_confirmation');
        const matchHint    = document.getElementById('matchHint');

        function checkMatch() {
            const pw  = pwInput.value;
            const cfm = confirmInput.value;
            if (!cfm) { matchHint.textContent = ''; matchHint.className = 'rp-match-hint'; return; }
            if (pw === cfm) {
                matchHint.textContent = '✓ Passwords match';
                matchHint.className = 'rp-match-hint rp-match-ok';
            } else {
                matchHint.textContent = '✗ Passwords do not match';
                matchHint.className = 'rp-match-hint rp-match-err';
            }
        }

        confirmInput.addEventListener('input', checkMatch);
    </script>

</x-guest-layout>