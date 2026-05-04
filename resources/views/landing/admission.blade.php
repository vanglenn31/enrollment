<x-guest-layout>
    @include('layouts.landing_nav')
    
<!-- HERO SECTION -->
<section class="bg-gradient-to-r from-blue-700 to-blue-500 text-white py-16">
    <div class="max-w-5xl mx-auto text-center px-4">
        <h1 class="text-3xl md:text-5xl font-bold">Admissions & Enrollment</h1>
        <p class="mt-3 text-sm md:text-base text-blue-100">
            Take the first step towards your future. Join our community of learners and achieve your academic goals.
        </p>

        <button class="mt-6 bg-white text-blue-600 font-semibold px-6 py-2 rounded-lg shadow hover:bg-gray-100">
            Start Application
        </button>
    </div>
</section>

<!-- MAIN CONTAINER -->
<div class="max-w-6xl mx-auto px-4 space-y-10 py-10">

    <!-- ADMISSION REQUIREMENTS -->
    <section class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4">Admission Requirements</h2>
        <p class="text-sm text-gray-500 mb-6">Please ensure you meet all requirements before submitting your application.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

            <ul class="space-y-2">
                <li class="flex items-center gap-2">✔ High school diploma or equivalent</li>
                <li class="flex items-center gap-2">✔ Minimum GPA of 2.5 (if required)</li>
                <li class="flex items-center gap-2">✔ Letters of recommendation (2-3)</li>
                <li class="flex items-center gap-2">✔ Application fee payment</li>
            </ul>

            <ul class="space-y-2">
                <li class="flex items-center gap-2">✔ Official transcripts from previous institutions</li>
                <li class="flex items-center gap-2">✔ Personal statement or essay</li>
                <li class="flex items-center gap-2">✔ Proof of English proficiency (international students)</li>
            </ul>

        </div>
    </section>

    <!-- APPLICATION DEADLINES -->
    <section class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-6">Application Deadlines</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

            <div class="border rounded-lg p-4">
                <h3 class="font-semibold">Fall 2025</h3>
                <p class="text-gray-500">May 1, 2025</p>
                <span class="inline-block mt-2 text-green-600 font-medium">Open</span>
            </div>

            <div class="border rounded-lg p-4">
                <h3 class="font-semibold">Spring 2026</h3>
                <p class="text-gray-500">November 1, 2025</p>
                <span class="inline-block mt-2 text-green-600 font-medium">Open</span>
            </div>

            <div class="border rounded-lg p-4">
                <h3 class="font-semibold">Summer 2026</h3>
                <p class="text-gray-500">March 1, 2026</p>
                <span class="inline-block mt-2 text-yellow-500 font-medium">Coming Soon</span>
            </div>

        </div>
    </section>

    <!-- TUITION & FEES -->
    <section class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-6">Tuition & Fees</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">Program</th>
                        <th>Domestic Students</th>
                        <th>International Students</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="border-b">
                        <td class="py-2">Undergraduate (per year)</td>
                        <td>$15,000</td>
                        <td>$25,000</td>
                    </tr>

                    <tr class="border-b">
                        <td class="py-2">Graduate (per year)</td>
                        <td>$18,000</td>
                        <td>$28,000</td>
                    </tr>

                    <tr>
                        <td class="py-2">Online Programs (per year)</td>
                        <td>$12,000</td>
                        <td>$20,000</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="text-xs text-gray-400 mt-4">
            Tuition rates may vary by program. Additional fees may apply.
        </p>
    </section>

</div>

    
</x-guest-layout>