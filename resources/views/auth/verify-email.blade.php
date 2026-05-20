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

        /* Email icon badge */
        .lf-icon-badge {
            width: 56px; height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(29,111,186,0.10), rgba(13,43,86,0.08));
            border: 1.5px solid rgba(29,111,186,0.15);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
        }

        .lf-icon-badge svg {
            width: 26px; height: 26px;
            stroke: #1d6fba;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Info message box */
        .lf-info-box {
            background: #f7f9fc;
            border: 1.5px solid #dce3ee;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 22px;
            font-size: 0.83rem;
            color: #4a6080;
            line-height: 1.55;
        }

        /* Success alert */
        .lf-success {
            background: #f0faf4;
            border: 1.5px solid #a7d9b8;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 0.83rem;
            color: #1e6e40;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.5;
        }

        .lf-success svg {
            width: 16px; height: 16px;
            stroke: #1e6e40; fill: none;
            stroke-width: 2.2; flex-shrink: 0;
            margin-top: 1px;
        }

        /* Buttons */
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
            margin-bottom: 12px;
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
            stroke-linecap: round; stroke-linejoin: round;
        }

        .lf-logout-wrap {
            text-align: center;
            padding-top: 4px;
        }

        .lf-logout {
            background: none;
            border: none;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.82rem;
            color: #7a93b4;
            cursor: pointer;
            text-decoration: underline;
            transition: color 0.2s;
            padding: 0;
        }

        .lf-logout:hover { color: #0d2b56; }

        /* Divider */
        .lf-divider {
            border: none;
            border-top: 1px solid #f0f2f7;
            margin: 18px 0;
        }
    </style>

    <div class="lf-wrap">
        <div class="lf-card">

            <!-- Brand -->
            <div class="lf-brand">
                <div class="lf-brand-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="UIM logo">
                </div>
                <div>
                    <p class="lf-brand-name">University of International Mindanao</p>
                    <p class="lf-brand-sub">Enrollment Management System</p>
                </div>
            </div>

            <!-- Email icon -->
            <div class="lf-icon-badge">
                <svg viewBox="0 0 24 24">
                    <rect x="2" y="4" width="20" height="16" rx="3"/>
                    <path d="M2 7l10 7 10-7"/>
                </svg>
            </div>

            <h1 class="lf-title">Verify Your Email</h1>
            <p class="lf-subtitle">One last step before you get started</p>

            <!-- Info message -->
            <div class="lf-info-box">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>

            <!-- Success alert -->
            @if (session('status') == 'verification-link-sent')
                <div class="lf-success">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <!-- Resend form -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="lf-btn">
                    <svg viewBox="0 0 24 24">
                        <path d="M22 2L11 13"/>
                        <path d="M22 2L15 22l-4-9-9-4 20-7z"/>
                    </svg>
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <hr class="lf-divider">

            <!-- Logout form -->
            <div class="lf-logout-wrap">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="lf-logout">
                        {{ __('Log out and return later') }}
                    </button>
                </form>
            </div>

        </div>
    </div>

</x-guest-layout>