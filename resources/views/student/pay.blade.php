<x-app-layout>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap');
    * { font-family: 'DM Sans', sans-serif; }

    /* Make sure nothing from the fixed header overlaps clickable content */
    .pay-main {
        padding-top: 1.5rem;
    }

    /* Radio pill style */
    .method-radio { display: none; }
    .method-label {
        display: block;
        text-align: center;
        padding: 0.625rem 0.25rem;
        border-radius: 0.75rem;
        border: 2px solid #e5e7eb;
        background: #fff;
        font-size: 0.875rem;
        font-weight: 500;
        color: #4b5563;
        cursor: pointer;
        user-select: none;
        transition: border-color 0.15s, background 0.15s, color 0.15s;
    }
    .method-radio:checked + .method-label {
        border-color: #111827;
        background: #111827;
        color: #fff;
    }
    .method-label:hover {
        border-color: #6b7280;
    }
</style>

<div class="flex min-h-screen bg-gray-50">

    {{-- Sidebar --}}
    <aside class="hidden lg:block fixed inset-y-0 left-0 w-fit ">
        @include('layouts.student_side_bar')
    </aside>

    <div class="flex-1 w-full lg:ml-64 flex flex-col min-h-screen">

        {{-- Nav --}}
        <header class="sticky top-0 z-20 bg-white border-b border-gray-100">
            @include('layouts.navigation')
        </header>

        {{-- Page content — sits below the sticky header --}}
        <main class="flex-1 px-4 py-8 sm:px-6 lg:px-8 pay-main">
            <div class="max-w-2xl mx-auto space-y-6">

                {{-- Back + heading --}}
                <div>
                    <a href="{{ route('student.payment') }}"
                       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Payments
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">Make a Payment</h1>
                    <p class="text-sm text-gray-500 mt-1">Submit a payment request. The admin will review and confirm it.</p>
                </div>

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700 text-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700 text-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.07 16.5C2.3 17.333 3.262 19 4.8 19z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- ── NO BALANCE ── --}}
                @if($balance <= 0)

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                        <div class="mx-auto w-14 h-14 rounded-full bg-green-50 flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-800 text-lg">No outstanding balance</p>
                        <p class="text-sm text-gray-400 mt-1">Your tuition is fully paid. Nothing is due at the moment.</p>
                        <a href="{{ route('student.payment') }}"
                           style="display:inline-flex; margin-top:1.5rem;"
                           class="items-center gap-2 rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-700 transition-colors">
                            View Payment History
                        </a>
                    </div>

                @else

                    {{-- Balance summary --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-1">Paying for</p>
                                <p class="font-bold text-gray-900">
                                    {{ optional($enrollment->term)->label ?? 'Current Term' }} — Tuition
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Total:&nbsp;<span class="font-medium text-gray-700">₱{{ number_format($totalTuition, 2) }}</span>
                                    &nbsp;·&nbsp;
                                    Paid:&nbsp;<span class="font-medium text-gray-700">₱{{ number_format($amountPaid + $downpayment, 2) }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium mb-1">Remaining balance</p>
                                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($balance, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Payment form --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Payment Request</p>
                                <p class="text-xs text-gray-500">Fill in the details and the admin will review your request.</p>
                            </div>
                        </div>

                        <form action="{{ route('student.payment.request') }}"
                              method="POST"
                              enctype="multipart/form-data"
                              style="padding: 1.5rem; display:flex; flex-direction:column; gap:1.25rem;">
                            @csrf
                            <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">

                            {{-- Amount --}}
                            <div>
                                <label for="amount_paid" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.375rem;">
                                    Amount you are paying <span style="color:#ef4444;">*</span>
                                </label>
                                <div style="position:relative;">
                                    <span style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#6b7280; font-size:0.875rem; font-weight:500; pointer-events:none;">₱</span>
                                    <input id="amount_paid"
                                           type="number"
                                           name="amount_paid"
                                           min="1"
                                           step="0.01"
                                           max="{{ $balance }}"
                                           required
                                           placeholder="0.00"
                                           style="width:100%; padding:0.75rem 1rem 0.75rem 2rem; border:1px solid #e5e7eb; border-radius:0.75rem; font-size:0.875rem; outline:none; box-sizing:border-box;">
                                </div>
                                <p style="margin-top:0.375rem; font-size:0.75rem; color:#9ca3af;">
                                    Balance due:&nbsp;<strong style="color:#4b5563;">₱{{ number_format($balance, 2) }}</strong>. Partial payments are accepted.
                                </p>
                                @error('amount_paid')
                                    <p style="margin-top:0.375rem; font-size:0.75rem; color:#dc2626;">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Payment method --}}
                            <div>
                                <p style="font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.5rem;">
                                    Payment method <span style="color:#ef4444;">*</span>
                                </p>
                                <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:0.5rem;">
                                    @foreach([['gcash','GCash'],['maya','Maya'],['bank_transfer','Bank Transfer'],['cash','Cash'],['other','Other']] as [$v, $l])
                                        <div>
                                            <input class="method-radio" type="radio"
                                                   name="payment_method"
                                                   id="method_{{ $v }}"
                                                   value="{{ $v }}"
                                                   required>
                                            <label class="method-label" for="method_{{ $v }}">{{ $l }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('payment_method')
                                    <p style="margin-top:0.375rem; font-size:0.75rem; color:#dc2626;">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Reference number --}}
                            <div>
                                <label for="reference_number" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.375rem;">
                                    Reference / transaction number&nbsp;<span style="color:#9ca3af; font-weight:400;">(optional)</span>
                                </label>
                                <input id="reference_number"
                                       type="text"
                                       name="reference_number"
                                       placeholder="e.g. GCash ref # or bank transaction ID"
                                       style="width:100%; padding:0.75rem 1rem; border:1px solid #e5e7eb; border-radius:0.75rem; font-size:0.875rem; outline:none; box-sizing:border-box;">
                                @error('reference_number')
                                    <p style="margin-top:0.375rem; font-size:0.75rem; color:#dc2626;">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Proof of payment --}}
                            <div>
                                <p style="font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.375rem;">
                                    Proof of payment&nbsp;<span style="color:#9ca3af; font-weight:400;">(optional)</span>
                                </p>
                                <label for="proof_of_payment" id="proof-label"
                                       style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0.75rem;
                                              border:2px dashed #e5e7eb; border-radius:0.75rem; background:#f8fafc;
                                              padding:2rem 1rem; cursor:pointer; transition:border-color 0.15s;">
                                    <svg id="proof-icon" style="width:2rem; height:2rem; color:#d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                    </svg>
                                    <div id="file-placeholder" style="text-align:center;">
                                        <p style="font-size:0.875rem; font-weight:500; color:#4b5563;">Click to upload</p>
                                        <p style="font-size:0.75rem; color:#9ca3af; margin-top:0.125rem;">PNG, JPG, PDF — max 5 MB</p>
                                    </div>
                                    <p id="file-name" style="display:none; font-size:0.875rem; font-weight:500; color:#111827;"></p>
                                    <p id="file-size-error" style="display:none; font-size:0.75rem; color:#dc2626; margin-top:0.25rem;"></p>
                                    <input id="proof_of_payment"
                                           type="file"
                                           name="proof_of_payment"
                                           accept="image/jpeg,image/png,.pdf"
                                           onchange="handleProofUpload(this)"
                                           style="display:none;">
                                </label>
                                @error('proof_of_payment')
                                    <p style="margin-top:0.375rem; font-size:0.75rem; color:#dc2626; display:flex; align-items:center; gap:0.25rem;">
                                        <svg style="width:0.875rem; height:0.875rem; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Note --}}
                            <div>
                                <label for="note" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.375rem;">
                                    Note&nbsp;<span style="color:#9ca3af; font-weight:400;">(optional)</span>
                                </label>
                                <textarea id="note"
                                          name="note"
                                          rows="3"
                                          placeholder="Any additional information for the admin..."
                                          style="width:100%; padding:0.75rem 1rem; border:1px solid #e5e7eb; border-radius:0.75rem; font-size:0.875rem; outline:none; resize:none; box-sizing:border-box;"></textarea>
                            </div>

                            {{-- Submit --}}
                            <button type="submit"
                                    style="width:100%; display:flex; align-items:center; justify-content:center; gap:0.5rem;
                                           padding:0.875rem 1rem; border-radius:0.75rem; background:#111827; border:none;
                                           font-size:0.875rem; font-weight:600; color:#fff; cursor:pointer;
                                           transition: background 0.15s;">
                                <svg style="width:1rem; height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Submit Payment Request
                            </button>

                        </form>
                    </div>

                @endif

                <p style="font-size:0.75rem; text-align:center; color:#9ca3af; padding-bottom:2rem;">
                    Payment requests are reviewed by the admin within 1–2 business days.
                </p>

            </div>
        </main>
    </div>
</div>

<script>
    var MAX_FILE_BYTES = 5 * 1024 * 1024; // 5 MB
    var ALLOWED_TYPES  = ['image/jpeg', 'image/png', 'application/pdf'];

    function handleProofUpload(input) {
        var placeholder = document.getElementById('file-placeholder');
        var nameEl      = document.getElementById('file-name');
        var errEl       = document.getElementById('file-size-error');
        var label       = document.getElementById('proof-label');
        var submitBtn   = document.querySelector('button[type="submit"]');

        // Reset state
        errEl.style.display      = 'none';
        errEl.textContent        = '';
        label.style.borderColor  = '#e5e7eb';
        label.style.background   = '#f8fafc';

        if (!input.files || !input.files[0]) {
            placeholder.style.display = 'block';
            nameEl.style.display      = 'none';
            return;
        }

        var file = input.files[0];
        var errors = [];

        // Type check
        if (!ALLOWED_TYPES.includes(file.type)) {
            errors.push('Only JPG, PNG, or PDF files are allowed.');
        }

        // Size check
        if (file.size > MAX_FILE_BYTES) {
            var sizeMB = (file.size / 1024 / 1024).toFixed(2);
            errors.push('File is ' + sizeMB + ' MB — maximum allowed is 5 MB.');
        }

        if (errors.length > 0) {
            // Show error, highlight border, clear input, block submit
            label.style.borderColor  = '#dc2626';
            label.style.background   = '#fff5f5';
            errEl.textContent        = errors.join(' ');
            errEl.style.display      = 'block';
            placeholder.style.display = 'block';
            nameEl.style.display      = 'none';
            input.value = ''; // clear the bad file
            if (submitBtn) submitBtn.disabled = false; // file cleared — form can still submit without file
            return;
        }

        // Valid file
        label.style.borderColor  = '#16a34a';
        label.style.background   = '#f0fdf4';
        placeholder.style.display = 'none';
        nameEl.textContent        = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
        nameEl.style.display      = 'block';
    }

    /* Hover tint on submit button */
    var btn = document.querySelector('button[type="submit"]');
    if (btn) {
        btn.addEventListener('mouseover', function() { this.style.background = '#374151'; });
        btn.addEventListener('mouseout',  function() { this.style.background = '#111827'; });
    }
</script>

</x-app-layout>