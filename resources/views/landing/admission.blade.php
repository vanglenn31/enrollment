<x-guest-layout>
    @include('layouts.landing_nav')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap');
        .page-font { font-family: 'DM Sans', sans-serif; }
        .display-font { font-family: 'Playfair Display', serif; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.7s cubic-bezier(.22,.68,0,1.2) both; }
        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.30s; }

        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.55s ease, transform 0.55s cubic-bezier(.22,.68,0,1.2);
        }
        .reveal.visible { opacity: 1; transform: none; }

        .req-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s;
            border-radius: 0.5rem;
        }
        .req-item:last-child { border-bottom: none; }
        .req-item:hover { background: #f8fafc; }

        .deadline-card {
            transition: transform 0.25s cubic-bezier(.22,.68,0,1.2), box-shadow 0.25s ease;
        }
        .deadline-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px -6px rgba(29,111,186,0.15);
        }

        .step-connector::after {
            content: '';
            position: absolute;
            left: 1.375rem;
            top: 2.5rem;
            bottom: -1rem;
            width: 2px;
            background: linear-gradient(to bottom, #bfdbfe, transparent);
        }
    </style>

    <!-- HERO -->
    <section class="page-font relative overflow-hidden"
             style="background: linear-gradient(135deg, #0d2b56 0%, #1a4f8a 55%, #1d6fba 100%); padding: 5rem 1.5rem 8rem;">

        <!-- Decorative blobs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full opacity-10"
                 style="background: radial-gradient(circle, #93c5fd, transparent); transform: translate(25%, -25%);"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 rounded-full opacity-10"
                 style="background: radial-gradient(circle, #60a5fa, transparent); transform: translate(-30%, 30%);"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto text-center text-white space-y-5">
            <span class="fade-up inline-block bg-white/15 backdrop-blur-sm border border-white/25 text-blue-100 text-xs font-semibold px-4 py-1.5 rounded-full tracking-widest uppercase">
                Admissions & Enrollment
            </span>
            <h1 class="display-font fade-up delay-1 text-4xl md:text-5xl xl:text-6xl font-bold leading-tight">
                Begin Your <span style="color:#93c5fd;">Academic Journey</span>
            </h1>
            <p class="fade-up delay-2 text-blue-100 text-base md:text-lg max-w-xl mx-auto leading-relaxed">
                Join our vibrant community of learners. Take the first step toward achieving your academic and career goals at UIM.
            </p>
            <div class="fade-up delay-2 flex flex-wrap justify-center gap-3 pt-2">
                <a href="#apply"
                   class="inline-flex items-center gap-2 bg-white text-blue-800 font-semibold text-sm px-7 py-3 rounded-xl shadow-lg hover:bg-blue-50 transition-all hover:-translate-y-0.5">
                    Start Application
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                <a href="#requirements"
                   class="inline-flex items-center gap-2 border border-white/40 text-white font-semibold text-sm px-7 py-3 rounded-xl hover:bg-white/10 transition-all hover:-translate-y-0.5">
                    View Requirements
                </a>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <div class="page-font max-w-6xl mx-auto px-4 sm:px-6 -mt-12 pb-16 space-y-8 relative z-10">

        <!-- ===== ADMISSION REQUIREMENTS ===== -->
        <section id="requirements" class="reveal bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-3 px-7 py-5 border-b border-gray-100"
                 style="background: linear-gradient(90deg, #f0f6ff, #fff);">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                     style="background: linear-gradient(135deg,#1d6fba,#1a4f8a);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="display-font text-xl font-bold text-gray-800">Admission Requirements</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Ensure you meet all requirements before applying.</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-0 divide-y md:divide-y-0 md:divide-x divide-gray-100">
                <div class="px-7 py-6">
                    @foreach([
                        ['High school diploma or equivalent', 'Required for all undergraduate applicants.'],
                        ['Minimum GPA of 2.5', 'Academic records must reflect a cumulative GPA of 2.5 or above.'],
                        ['Letters of recommendation', '2–3 letters from teachers or counselors.'],
                        ['Application fee payment', 'Non-refundable processing fee required.'],
                    ] as $req)
                    <div class="req-item">
                        <span class="mt-0.5 w-5 h-5 rounded-full flex items-center justify-center shrink-0"
                              style="background:#dbeafe;">
                            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $req[0] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $req[1] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="px-7 py-6">
                    @foreach([
                        ['Official transcripts', 'Certified copies from all previous institutions attended.'],
                        ['Personal statement or essay', 'A 300–500 word essay on your academic goals.'],
                        ['Proof of English proficiency', 'Required for international applicants (TOEFL/IELTS).'],
                    ] as $req)
                    <div class="req-item">
                        <span class="mt-0.5 w-5 h-5 rounded-full flex items-center justify-center shrink-0"
                              style="background:#dbeafe;">
                            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $req[0] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $req[1] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ===== APPLICATION DEADLINES ===== -->
        <section class="reveal">
            <div class="text-center mb-6">
                <span class="text-xs font-semibold tracking-widest text-blue-600 uppercase">Don't Miss Out</span>
                <h2 class="display-font text-2xl md:text-3xl font-bold text-gray-800 mt-1">Application Deadlines</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                @foreach([
                    ['Fall 2025', 'May 1, 2025', 'open', 'Open'],
                    ['Spring 2026', 'November 1, 2025', 'open', 'Open'],
                    ['Summer 2026', 'March 1, 2026', 'soon', 'Coming Soon'],
                ] as $d)
                <div class="deadline-card bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <h3 class="display-font text-lg font-bold text-gray-800">{{ $d[0] }}</h3>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full
                            {{ $d[2] === 'open' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                            {{ $d[3] }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Deadline: <strong class="text-gray-700">{{ $d[1] }}</strong>
                    </div>
                    <div class="mt-auto pt-2">
                        @if($d[2] === 'open')
                        <a href="#apply"
                           class="block text-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors shadow-sm shadow-blue-200">
                            Apply for {{ explode(' ', $d[0])[0] }} {{ explode(' ', $d[0])[1] }}
                        </a>
                        @else
                        <div class="block text-center bg-gray-100 text-gray-400 text-sm font-semibold py-2.5 rounded-xl cursor-not-allowed">
                            Not Yet Open
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

            </div>
        </section>

        <!-- ===== HOW TO APPLY (Steps) ===== -->
        <section class="reveal bg-white rounded-2xl shadow-sm border border-gray-100 p-7" id="apply">
            <div class="mb-6">
                <span class="text-xs font-semibold tracking-widest text-blue-600 uppercase">Application Process</span>
                <h2 class="display-font text-2xl font-bold text-gray-800 mt-1">How to Apply</h2>
            </div>
            <div class="grid md:grid-cols-4 gap-6">
                @foreach([
                    ['01', 'Create Account', 'Register on our online enrollment portal with your email address.', '#1d6fba'],
                    ['02', 'Fill Out Form', 'Complete the application form with your personal and academic details.', '#0ea5e9'],
                    ['03', 'Submit Documents', 'Upload all required documents as specified in the requirements.', '#14b8a6'],
                    ['04', 'Pay & Confirm', 'Pay the application fee and await confirmation from our admissions team.', '#6366f1'],
                ] as $step)
                <div class="flex flex-col items-center text-center gap-3">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-md shrink-0"
                         style="background: {{ $step[3] }}1a; border: 2px solid {{ $step[3] }}30;">
                        <span class="display-font text-xl font-bold" style="color: {{ $step[3] }};">{{ $step[0] }}</span>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-sm">{{ $step[1] }}</p>
                        <p class="text-xs text-gray-400 mt-1 leading-relaxed">{{ $step[2] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <!-- ===== TUITION & FEES ===== -->
        <section class="reveal bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-3 px-7 py-5 border-b border-gray-100"
                 style="background: linear-gradient(90deg, #f0f6ff, #fff);">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                     style="background: linear-gradient(135deg, #0ea5e9, #0369a1);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="display-font text-xl font-bold text-gray-800">Tuition & Fees</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Rates may vary by program. Additional fees may apply.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th class="text-left py-3 px-7 text-gray-500 font-semibold text-xs uppercase tracking-wide">Program</th>
                            <th class="text-left py-3 px-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">Domestic</th>
                            <th class="text-left py-3 px-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">International</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach([
                            ['Undergraduate', 'per year', '$15,000', '$25,000'],
                            ['Graduate', 'per year', '$18,000', '$28,000'],
                            ['Online Programs', 'per year', '$12,000', '$20,000'],
                        ] as $row)
                        <tr class="hover:bg-blue-50/40 transition-colors">
                            <td class="py-4 px-7">
                                <p class="font-semibold text-gray-800">{{ $row[0] }}</p>
                                <p class="text-xs text-gray-400">{{ $row[1] }}</p>
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-bold text-gray-800">{{ $row[2] }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-bold text-gray-800">{{ $row[3] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-7 py-4 bg-blue-50/50 border-t border-blue-100">
                <p class="text-xs text-blue-600">
                    💡 <strong>Scholarships available.</strong> Contact our admissions office to learn about financial aid options and merit-based scholarships.
                </p>
            </div>
        </section>

    </div>

    @include('layouts.footer')

    <script>
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) e.target.classList.add('visible');
            });
        }, { threshold: 0.08 });
        reveals.forEach(el => observer.observe(el));
    </script>

</x-guest-layout>