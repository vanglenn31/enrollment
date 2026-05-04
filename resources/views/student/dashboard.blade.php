<x-app-layout>
    <div class="grid grid-cols-5 2xl:grid-cols-6 gap-x-0 gap-y-0">
        <div class="col-span-1 fixed h-screen z-30 w-fit lg:w-auto">
            @include('layouts.student_side_bar')
        </div>
        <div class="col-span-5 col-start-2 z-20 sticky top-0 z-50">
            @include('layouts.navigation')
        </div>
        <div class="col-span-4 col-start-2 p-6 z-10 md:z-30 w-full">
            <div class="max-w-7xl mx-auto space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->profile->first_name ?? 'Student' }}!</h1>
                        <p class="text-gray-700">This is your student dashboard. Here you can track your enrollment progress and payment status.</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($student?->status ?? 'unknown') }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <p class="text-sm text-gray-500">Program</p>
                        <p class="mt-2 text-xl font-semibold text-gray-900 ">{{ $student?->programRelation->name ?? 'Not assigned' }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <p class="text-sm text-gray-500">Courses assigned</p>
                        <p class="mt-2 text-xl font-semibold text-gray-900 text-end">{{ $enrollments->count() }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <p class="text-sm text-gray-500">Downpayment paid</p>
                        <p class="mt-2 text-xl font-semibold text-gray-900 text-end">₱{{ number_format($downpaymentPaid, 2) }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm">
                        <p class="text-sm text-gray-500">Pending payments</p>
                        <p class="mt-2 text-xl font-semibold text-gray-900 text-end">₱{{ number_format($pendingAmount, 2) }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Enrollment flow</h2>
                            <p class="text-sm text-gray-500 mt-1">This is the current status of your application, downpayment, verification and enrollment.</p>
                        </div>
                        <div class="rounded-full bg-blue-50 px-4 py-2 text-sm text-blue-700">{{ $nextAction }}</div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="rounded-2xl border border-gray-200 p-4 bg-slate-50">
                            <p class="text-sm text-gray-500">Downpayment</p>
                            <p class="mt-2 font-semibold {{ $hasDownpayment ? 'text-green-700' : 'text-orange-600' }}">{{ $hasDownpayment ? 'Paid' : 'Pending' }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 p-4 bg-slate-50">
                            <p class="text-sm text-gray-500">Verification</p>
                            <p class="mt-2 font-semibold {{ $verified ? 'text-green-700' : 'text-orange-600' }}">{{ $verified ? 'Verified' : 'Unverified' }}</p>
                        </div>
                        <div class="rounded-2xl border border-gray-200 p-4 bg-slate-50">
                            <p class="text-sm text-gray-500">Admin enrollment</p>
                            <p class="mt-2 font-semibold {{ $enrollments->isNotEmpty() ? 'text-green-700' : 'text-orange-600' }}">{{ $enrollments->isNotEmpty() ? 'Courses assigned' : 'Pending assignment' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Current Courses</h2>
                    @if($enrollments->isEmpty())
                        <div class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-gray-500">
                            <p class="font-medium">No course assignments yet.</p>
                            <p class="mt-2">Once your downpayment is complete and your record is verified, the admin will assign your courses.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($enrollments as $enrollment)
                                <div class="rounded-2xl border border-gray-200 p-4 sm:flex sm:items-center sm:justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ optional($enrollment->course)->course_code }} - {{ optional($enrollment->course)->course_name }}</p>
                                        <p class="text-sm text-gray-500">{{ optional($enrollment->course)->units ?? 0 }} units</p>
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        Prof: {{ $enrollment->course?->professor?->profile?->last_name ?? 'TBA' }}
                                    </p>
                                    <div class="text-sm text-gray-600">Assigned on {{ $enrollment->enrollment_date?->format('M d, Y') ?? 'N/A' }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
