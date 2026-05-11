<x-guest-layout>
    @php $request = request(); @endphp
    @include('layouts.landing_nav')

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap');

        .fp-wrap {
            font-family: 'DM Sans', sans-serif;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .fp-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border: 1px solid #e5e9f0;
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: 0 8px 32px rgba(13,43,86,0.10), 0 1.5px 4px rgba(13,43,86,0.06);
            animation: fpFadeUp 0.6s cubic-bezier(.22,.68,0,1.2) both;
        }

        @keyframes fpFadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Brand */
        .fp-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
            padding-bottom: 22px;
            border-bottom: 1px solid #f0f2f7;
        }

        .fp-brand-logo {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            background: linear-gradient(135deg, #0d2b56, #1d6fba);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .fp-brand-logo img { width: 100%; height: 100%; object-fit: contain; }

        .fp-brand-name {
            font-size: 0.72rem;
            font-weight: 600;
            color: #1a3a5c;
            line-height: 1.3;
        }

        .fp-brand-sub {
            font-size: 0.65rem;
            color: #7a93b4;
            margin-top: 1px;
        }

        /* Icon badge */
        .fp-icon-badge {
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

        .fp-icon-badge svg {
            width: 26px;
            height: 26px;
            stroke: #1d6fba;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Headings */
        .fp-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.55rem;
            font-weight: 700;
            color: #0d2b56;
            letter-spacing: -0.01em;
            margin-bottom: 4px;
        }

        .fp-subtitle {
            font-size: 0.82rem;
            color: #7a93b4;
            margin-bottom: 28px;
            line-height: 1.6;
        }

        /* Success status box */
        .fp-status {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: #f0faf5;
            border: 1.5px solid #a7f3d0;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
            font-size: 0.82rem;
            color: #065f46;
            line-height: 1.5;
        }

        .fp-status svg {
            width: 16px;
            height: 16px;
            stroke: #10b981;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* Field */
        .fp-field { margin-bottom: 16px; }

        .fp-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #4a6080;
            margin-bottom: 6px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .fp-input {
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

        .fp-input:focus {
            border-color: #1d6fba !important;
            background: #fff !important;
            box-shadow: 0 0 0 3px rgba(29,111,186,0.10) !important;
        }

        /* Submit button */
        .fp-btn {
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

        .fp-btn:hover {
            opacity: 0.93;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(13,43,86,0.28);
        }

        .fp-btn:active { transform: translateY(0); }

        .fp-btn svg {
            width: 15px;
            height: 15px;
            stroke: white;
            fill: none;
            stroke-width: 2.5;
        }

        /* Back to login */
        .fp-back {
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

        .fp-back svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
        }

        .fp-back:hover { color: #1d6fba; }

        /* Help note */
        .fp-help {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            padding: 12px 14px;
            background: #f7f9fc;
            border-radius: 10px;
            border: 1px solid #e5e9f0;
            font-size: 0.76rem;
            color: #7a93b4;
            line-height: 1.5;
        }

        .fp-help svg {
            width: 15px;
            height: 15px;
            stroke: #a0b4cc;
            fill: none;
            stroke-width: 2;
            flex-shrink: 0;
        }
    </style>

    <div class="fp-wrap">
        <div class="fp-card">

            <!-- Brand -->
            <div class="fp-brand">
                <div class="fp-brand-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="UIM logo">
                </div>
                <div>
                    <p class="fp-brand-name">University of International Mindanao</p>
                    <p class="fp-brand-sub">Enrollment Management System</p>
                </div>
            </div>

            <!-- Icon badge -->
            <div class="fp-icon-badge">
                <svg viewBox="0 0 24 24">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
            </div>

            <h1 class="fp-title">Forgot Password?</h1>
            <p class="fp-subtitle">No worries — enter your email and we'll send you a link to reset your password.</p>

            {{-- Session success status --}}
            @if (session('status'))
                <div class="fp-status">
                    <svg viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div class="fp-field">
                    <label for="email" class="fp-label">Email Address</label>
                    <input
                        id="email"
                        class="fp-input"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@uim.edu.ph"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Submit -->
                <button type="submit" class="fp-btn">
                    {{ __('Send Reset Link') }}
                    <svg viewBox="0 0 24 24">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                </button>

            </form>

            <!-- Help note -->
            <div class="fp-help">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Check your spam folder if you don't see the email within a few minutes.
            </div>

            <!-- Back to login -->
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="fp-back">
                    <svg viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Login
                </a>
            @endif

        </div>
    </div>

</x-guest-layout>