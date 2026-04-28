<x-app-layout>
    <div class="grid grid-cols-5 2xl:grid-cols-6 gap-x-0 gap-y-0">
        <div class="col-span-1 fixed h-screen z-0">
            @include('layouts.student_side_bar')
        </div>
        <div class="col-span-5 col-start-2 sticky top-0 z-50">
            @include('layouts.navigation')
        </div>
        
        <div class="col-span-4 col-start-2 p-6 z-30 w-full">
            <h1 class="text-2xl font-bold mb-4">Payments</h1>
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-semibold">Payment Information</h1>
        <p class="text-sm text-gray-500">
            View your payment history and make payments.
        </p>
    </div>

    <!-- PAYMENT SUMMARY -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-6">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

            <!-- LEFT -->
            <div>
                <h2 class="text-sm text-gray-500">Payment Summary</h2>

                <p class="text-2xl sm:text-3xl font-bold text-blue-600 mt-2">
                    ₱2,500.00
                </p>

                <p class="text-xs text-gray-500 mt-1">
                    Current Balance
                </p>

                <div class="mt-3">
                    <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded">
                        1/20/2025 Overdue
                    </span>
                </div>

                <p class="text-xs text-red-500 mt-2">
                    Your payment is overdue. Please make a payment to avoid late fees.
                </p>
            </div>

            <!-- RIGHT BUTTONS -->
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                <button class="w-full sm:w-auto bg-blue-700 text-white px-5 py-2 rounded-lg">
                    Make Payment
                </button>

                <button class="w-full sm:w-auto border px-5 py-2 rounded-lg">
                    Set Up Payment Plan
                </button>

            </div>

        </div>
    </div>

    <!-- PAYMENT HISTORY -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-6">

        <h2 class="text-sm text-gray-500 mb-4">Payment History</h2>

        <!-- MOBILE: CARD VIEW -->
        <div class="space-y-4 md:hidden">

            <div class="border rounded-lg p-3">
                <p class="text-sm font-medium">Fall 2024 Tuition</p>
                <p class="text-xs text-gray-500">12/15/2024</p>
                <p class="text-sm mt-1">$2,500.00</p>

                <div class="flex justify-between items-center mt-2">
                    <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded">
                        Paid
                    </span>
                    <button class="text-xs text-blue-600">Receipt</button>
                </div>
            </div>

            <!-- repeat cards... -->

        </div>

        <!-- DESKTOP: TABLE -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-gray-500 border-b">
                    <tr>
                        <th class="py-2">Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    <tr>
                        <td class="py-3">12/15/2024</td>
                        <td>Fall 2024 Tuition</td>
                        <td>$2,500.00</td>
                        <td>
                            <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded">
                                Paid
                            </span>
                        </td>
                        <td>
                            <button class="text-blue-600 text-xs">Receipt</button>
                        </td>
                    </tr>

                    <!-- repeat rows... -->

                </tbody>
            </table>
        </div>

    </div>

    <!-- PAYMENT INFO -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- METHODS -->
            <div>
                <h3 class="text-sm font-medium mb-2">Accepted Payment Methods</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>Credit Cards (Visa, MasterCard, Amex)</li>
                    <li>Debit Cards</li>
                    <li>Bank Transfer (ACH)</li>
                </ul>
            </div>

            <!-- NOTES -->
            <div>
                <h3 class="text-sm font-medium mb-2">Important Notes</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>Payments are due by the due date</li>
                    <li>Late payments may incur fees</li>
                    <li>Payment plans are available</li>
                    <li>Contact the treasury office for questions</li>
                </ul>
            </div>

        </div>

    </div>

</div>

    
    </div>
    

</x-app-layout>