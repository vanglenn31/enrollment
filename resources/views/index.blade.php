<x-guest-layout>

    @include('layouts.landing_nav')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=DM+Sans:wght@400;500;600&display=swap');

        .page-font { font-family: 'DM Sans', sans-serif; }
        .display-font { font-family: 'Playfair Display', serif; }

        /* Hero fade-up animation */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.75s cubic-bezier(.22,.68,0,1.2) both; }
        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.30s; }
        .delay-3 { animation-delay: 0.45s; }
        .delay-4 { animation-delay: 0.60s; }

        /* Floating badge */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-8px); }
        }
        .floating { animation: float 4s ease-in-out infinite; }

        /* Feature card hover */
        .feature-card {
            transition: transform 0.3s cubic-bezier(.22,.68,0,1.2), box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 48px -8px rgba(29,111,186,0.18);
        }

        /* Diagonal section separator */
        .clip-diagonal {
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        /* Stats counter */
        .stat-card {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.25);
        }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s ease, transform 0.6s cubic-bezier(.22,.68,0,1.2);
        }
        .reveal.visible {
            opacity: 1;
            transform: none;
        }
    </style>

    <!-- ========= HERO ========= -->
    <section class="relative overflow-hidden clip-diagonal"
             style="background: linear-gradient(135deg, #0d2b56 0%, #1a4f8a 50%, #1d6fba 100%); min-height: 92vh; display: flex; align-items: center;">

        <!-- Background image overlay -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/index-hero-pic.jpg') }}">
                 alt="Campus"
                 class="w-full h-full object-cover opacity-20">
        </div>

        <!-- Decorative shapes -->
        <div class="absolute top-0 right-0 w-[40vw] h-[40vw] rounded-full opacity-10"
             style="background: radial-gradient(circle, #60a5fa, transparent 70%); transform: translate(20%, -20%);"></div>
        <div class="absolute bottom-0 left-0 w-[30vw] h-[30vw] rounded-full opacity-10"
             style="background: radial-gradient(circle, #93c5fd, transparent 70%); transform: translate(-30%, 30%);"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 py-20 grid lg:grid-cols-2 gap-12 items-center w-full">

            <!-- Left: Text -->
            <div class="text-white space-y-6">
                <span class="fade-up inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/25 text-blue-100 text-xs font-semibold px-4 py-1.5 rounded-full tracking-widest uppercase">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                    Now Accepting Applications
                </span>

                <h1 class="display-font fade-up delay-1 text-4xl md:text-5xl xl:text-6xl font-bold leading-tight">
                    Fostering Growth,<br>
                    <em class="not-italic" style="color: #93c5fd;">Inspiring Futures.</em>
                </h1>

                <p class="fade-up delay-2 text-blue-100 text-base md:text-lg leading-relaxed max-w-lg page-font">
                    Welcome to <strong class="text-white font-semibold">UIM — University of International Mindanao</strong>, Davao.
                    A place where curiosity is celebrated and achievement is nurtured.
                </p>

                <div class="fade-up delay-3 flex flex-wrap gap-3">
                    <a href="{{ route('programs') }}"
                       class="page-font inline-flex items-center gap-2 bg-white text-blue-800 font-semibold text-sm px-6 py-3 rounded-xl shadow-lg hover:bg-blue-50 transition-all hover:-translate-y-0.5">
                        Explore Programs
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('admission') }}"
                       class="page-font inline-flex items-center gap-2 border border-white/40 text-white font-semibold text-sm px-6 py-3 rounded-xl hover:bg-white/15 transition-all hover:-translate-y-0.5">
                        Apply Now
                    </a>
                </div>
            </div>

            <!-- Right: Floating stats cards -->
            <div class="hidden lg:grid grid-cols-3 gap-4 fade-up delay-4">
                <div class="stat-card rounded-2xl p-6 text-white floating" style="animation-delay:0s">
                    <p class="display-font text-4xl font-bold">15+</p>
                    <p class="page-font text-blue-200 text-sm mt-1 font-medium">Academic Programs</p>
                </div>
                <div class="stat-card rounded-2xl p-6 text-white floating" style="animation-delay:0.8s">
                    <p class="display-font text-4xl font-bold">5K+</p>
                    <p class="page-font text-blue-200 text-sm mt-1 font-medium">Enrolled Students</p>
                </div>
                <div class="stat-card rounded-2xl p-6 text-white floating" style="animation-delay:1.4s">
                    <p class="display-font text-4xl font-bold">30+</p>
                    <p class="page-font text-blue-200 text-sm mt-1 font-medium">Expert Faculty</p>
                </div>
            </div>

        </div>
    </section>

    <!-- Mobile stats strip -->
    <div class="lg:hidden bg-[#0d2b56] text-white">
        <div class="grid grid-cols-3 max-w-7xl mx-auto">
            @foreach([['15+','Programs'],['5K+','Students'],['30+','Faculty']] as $stat)
            <div class="text-center py-5 border-r border-b border-white/10 last:border-r-0">
                <p class="display-font text-3xl font-bold">{{ $stat[0] }}</p>
                <p class="page-font text-blue-300 text-xs mt-0.5">{{ $stat[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- ========= WHY CHOOSE UIM ========= -->
    <section class="page-font max-w-6xl mx-auto px-6 py-20">

        <div class="text-center mb-12 reveal">
            <span class="text-xs font-semibold tracking-widest text-blue-600 uppercase">Our Commitment</span>
            <h2 class="display-font text-3xl md:text-4xl font-bold text-gray-900 mt-2">
                Why Choose <span style="color:#1d6fba;">University of International Mindanao</span>?
            </h2>
            <p class="text-gray-500 text-sm md:text-base mt-3 max-w-xl mx-auto">
                We're dedicated to providing an exceptional education experience that prepares you for the future.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Card 1 -->
            <div class="feature-card reveal bg-white rounded-2xl p-8 shadow-sm border border-gray-100 text-center space-y-4">
                <div class="w-14 h-14 rounded-2xl mx-auto flex items-center justify-center shadow-md"
                     style="background: linear-gradient(135deg, #1d6fba, #1a4f8a);">
                    <svg viewBox="0 0 1024 1024" fill="white" class="w-7 h-7" xmlns="http://www.w3.org/2000/svg">
                        <path d="M678.584675 765.172506v157.995691l75.697852 31.505938V723.768586a429.379161 429.379161 0 0 1-75.697852 41.40392zM269.717473 723.768586V953.098138l75.697852-31.505938v-156.419694a429.309162 429.309162 0 0 1-75.697852-41.40392zM511.999 798.78444a428.955162 428.955162 0 0 1-105.993793-13.241974v238.457534L511.999 979.886086 617.992793 1023.998V785.542466A429.025162 429.025162 0 0 1 511.999 798.78444zM511.999 0C308.479398 0 142.903721 165.575677 142.903721 369.097279S308.479398 738.192558 511.999 738.192558s369.097279-165.575677 369.097279-369.097279S715.520602 0 511.999 0z"/>
                    </svg>
                </div>
                <div>
                    <h5 class="display-font text-lg font-bold text-gray-800">Quality Education</h5>
                    <p class="text-gray-500 text-sm leading-relaxed mt-2">
                        UIM delivers education that cultivates the knowledge, critical thinking, and practical skills essential for academic and professional excellence.
                    </p>
                </div>
                <div class="pt-2">
                    <span class="inline-block w-10 h-0.5 rounded-full" style="background:#1d6fba;"></span>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="feature-card reveal bg-white rounded-2xl p-8 shadow-sm border border-gray-100 text-center space-y-4"
                 style="transition-delay: 0.1s;">
                <div class="w-14 h-14 rounded-2xl mx-auto flex items-center justify-center shadow-md"
                     style="background: linear-gradient(135deg, #0ea5e9, #0369a1);">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7">
                        <path d="M10 16V14M10 14V12M10 14L12 14M10 14L8 14M21 12V11.2C21 10.08 21 9.52 20.782 9.09C20.59 8.72 20.284 8.41 19.908 8.22C19.48 8 18.92 8 17.8 8H3M21 12V16M21 12H19C17.895 12 17 12.895 17 14C17 15.105 17.895 16 19 16H21M21 16V16.8C21 17.92 21 18.48 20.782 18.908C20.59 19.284 20.284 19.59 19.908 19.782C19.48 20 18.92 20 17.8 20H6.2C5.08 20 4.52 20 4.092 19.782C3.716 19.59 3.41 19.284 3.218 18.908C3 18.48 3 17.92 3 16.8V8M18 8V7.2C18 6.08 18 5.52 17.782 5.092C17.59 4.716 17.284 4.41 16.908 4.218C16.48 4 15.92 4 14.8 4H6.2C5.08 4 4.52 4 4.092 4.218C3.716 4.41 3.41 4.716 3.218 5.092C3 5.52 3 6.08 3 7.2V8"
                            stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <h5 class="display-font text-lg font-bold text-gray-800">Affordable Tuition</h5>
                    <p class="text-gray-500 text-sm leading-relaxed mt-2">
                        We believe world-class education should be accessible. UIM offers competitive tuition with flexible payment options and scholarship opportunities.
                    </p>
                </div>
                <div class="pt-2">
                    <span class="inline-block w-10 h-0.5 rounded-full" style="background:#0ea5e9;"></span>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="feature-card reveal bg-white rounded-2xl p-8 shadow-sm border border-gray-100 text-center space-y-4"
                 style="transition-delay: 0.2s;">
                <div class="w-14 h-14 rounded-2xl mx-auto flex items-center justify-center shadow-md"
                     style="background: linear-gradient(135deg, #14b8a6, #0f766e);">
                    <svg class="w-7 h-7" viewBox="0 0 32 32" fill="white" xmlns="http://www.w3.org/2000/svg">
                        <path d="M28.9,9.4C28.9,9.4,28.9,9.4,28.9,9.4C28.9,9.3,29,9.2,29,9.1c0,0,0,0,0-0.1c0,0,0,0,0-0.1c0-0.1,0-0.2,0-0.3c0,0,0,0,0-0.1c0-0.1-0.1-0.2-0.1-0.3c0,0,0,0,0,0c-0.1-0.1-0.1-0.1-0.2-0.2l-11-7c-0.3-0.2-0.8-0.2-1.1,0l-13,9c0,0-0.1,0.1-0.1,0.1c0,0,0,0-0.1,0c-0.1,0.1-0.1,0.2-0.2,0.3c0,0,0,0,0,0.1C3,10.8,3,10.9,3,11c0,0,0,0,0,0v6v6c0,0.3,0.2,0.7,0.5,0.8l11,7c0.2,0.1,0.4,0.2,0.5,0.2c0.2,0,0.4-0.1,0.6-0.2l13-9c0.2-0.2,0.4-0.4,0.4-0.7s-0.1-0.6-0.3-0.8c-0.9-0.9-1.1-2.2-0.5-3.4l0.7-1.5c0-0.1,0.1-0.2,0.1-0.3c0,0,0-0.1,0-0.1c0,0,0,0,0,0c0-0.1,0-0.3-0.1-0.4c0,0,0-0.1,0-0.1c0-0.1-0.1-0.2-0.2-0.3c0,0,0,0,0,0c-0.9-0.9-1.1-2.2-0.5-3.4L28.9,9.4z M26.6,14.8l-11.6,8L5,16.5v-3.6l9.5,6c0.2,0.1,0.4,0.2,0.5,0.2c0.2,0,0.4-0.1,0.6-0.2l10.3-7.1C25.8,12.8,26,13.8,26.6,14.8z M15,28.8L5,22.5v-3.6l9.5,6c0.2,0.1,0.4,0.2,0.5,0.2c0.2,0,0.4-0.1,0.6-0.2l10.3-7.1c-0.1,1.1,0.1,2.2,0.7,3.1L15,28.8z"/>
                    </svg>
                </div>
                <div>
                    <h5 class="display-font text-lg font-bold text-gray-800">Supportive Environment</h5>
                    <p class="text-gray-500 text-sm leading-relaxed mt-2">
                        Our student-first campus culture pairs dedicated faculty mentorship with resources and communities that help every learner reach their potential.
                    </p>
                </div>
                <div class="pt-2">
                    <span class="inline-block w-10 h-0.5 rounded-full" style="background:#14b8a6;"></span>
                </div>
            </div>

        </div>
    </section>

    <!-- ========= CTA BANNER ========= -->
    <section class="reveal page-font"
             style="background: linear-gradient(135deg, #0d2b56 0%, #1a4f8a 100%);">
        <div class="max-w-5xl mx-auto px-6 py-16 text-center text-white space-y-5">
            <span class="text-xs font-semibold tracking-widest text-blue-300 uppercase">Ready to Begin?</span>
            <h2 class="display-font text-3xl md:text-4xl font-bold">
                Your Journey Starts Here.
            </h2>
            <p class="text-blue-200 text-base max-w-lg mx-auto">
                Applications are now open. Take the next step toward a transformative education at UIM.
            </p>
            <div class="flex flex-wrap gap-3 justify-center pt-2">
                <a href="{{ route('admission') }}"
                   class="bg-white text-blue-800 font-semibold text-sm px-7 py-3 rounded-xl hover:bg-blue-50 transition-all hover:-translate-y-0.5 shadow-lg">
                    View Admissions
                </a>
                <a href="{{ route('programs') }}"
                   class="border border-white/40 text-white font-semibold text-sm px-7 py-3 rounded-xl hover:bg-white/10 transition-all hover:-translate-y-0.5">
                    Browse Programs
                </a>
            </div>
        </div>
    </section>

    @include('layouts.footer')

    <script>
        // Scroll reveal
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((e, i) => {
                if (e.isIntersecting) {
                    e.target.style.transitionDelay = e.target.style.transitionDelay || (i * 0.05) + 's';
                    e.target.classList.add('visible');
                }
            });
        }, { threshold: 0.12 });
        reveals.forEach(el => observer.observe(el));
    </script>

</x-guest-layout>