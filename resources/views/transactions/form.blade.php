@extends('layouts.app')

@section('title', $transaction->exists ? 'Edit Transaction' : 'Add Transaction')
@section('subtitle', $transaction->exists ? 'Update transaction details' : 'Record new vehicle sale')

@section('content')
<!-- Success Messages -->
@if (session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg animate-fadeInDown">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="font-semibold text-green-700">{{ session('success') }}</h3>
            </div>
        </div>
    </div>
@endif

<!-- Validation Errors -->
@if (isset($errors) && $errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="font-semibold text-red-700 mb-1">Please correct the following errors:</h3>
                <ul class="list-disc list-inside space-y-1 text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<form method="POST" action="{{ $transaction->exists ? route('transactions.update', $transaction) : route('transactions.store') }}" class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden" aria-describedby="transactionFormHelp">
    @csrf
    @if($transaction->exists)
        @method('PUT')
    @endif

    <!-- Transaction Details Section -->
    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
        <h3 class="font-bold text-slate-800">Transaction Details</h3>
    </div>
    <p id="transactionFormHelp" class="sr-only">Use this form to create or update transaction details, payment status, files, and OTP verification.</p>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="vehicle_id" class="block text-sm font-semibold text-slate-700 mb-2">Vehicle *</label>
            <select id="vehicle_id" name="vehicle_id" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required aria-required="true">
                <option value="">Select vehicle</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}" @selected(old('vehicle_id', $transaction->vehicle_id) == $vehicle->id)>{{ $vehicle->brand }} {{ $vehicle->model }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="buyer_id" class="block text-sm font-semibold text-slate-700 mb-2">Buyer *</label>
            <select id="buyer_id" name="buyer_id" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required aria-required="true">
                <option value="">Select buyer</option>
                @foreach($buyers as $buyer)
                    <option value="{{ $buyer->id }}" @selected(old('buyer_id', $transaction->buyer_id) == $buyer->id)>{{ $buyer->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="seller_id" class="block text-sm font-semibold text-slate-700 mb-2">Seller *</label>
            <select id="seller_id" name="seller_id" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required aria-required="true">
                <option value="">Select seller</option>
                @foreach($sellers as $seller)
                    <option value="{{ $seller->id }}" @selected(old('seller_id', $transaction->seller_id) == $seller->id)>{{ $seller->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">Payment Status *</label>
            <select id="status" name="status" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required aria-describedby="statusHelp" aria-required="true">
                @foreach(['pending_review' => 'Pending CBS Review', 'completed' => 'Approved / Completed', 'cancelled' => 'Rejected / Cancelled'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $transaction->status ?: 'pending_review') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <p id="statusHelp" class="mt-2 text-xs text-slate-500">New payments should start as pending review so CBS admin can approve before confirmation emails are sent.</p>
        </div>
    </div>

    <!-- OTP Verification Section (for pending_review transactions) -->
    @if($transaction->exists && $transaction->status === 'pending_review')
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-y border-purple-200" id="otpSection">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                One-Time Password (OTP) Verification
            </h3>
        </div>
        <div class="p-6 bg-white border-b border-slate-200 animation-slideDown">
            @if($transaction->otp_verified_at)
                <!-- OTP Already Verified -->
                <div class="p-4 bg-green-50 border border-green-300 rounded-lg badge-success">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-green-700">OTP Verified ✓</p>
                            <p class="text-sm text-green-600 mt-1">Verified on: <strong>{{ $transaction->otp_verified_at->format('M d, Y \a\t H:i:s') }}</strong></p>
                            <p class="text-xs text-green-600 mt-2">Awaiting CBS admin approval to complete the transaction. You will receive an email once approved.</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- OTP Verification Pending -->
                @php
                    $pendingOtp = $transaction->otps()->whereNull('used_at')->first();
                @endphp
                
                @if($pendingOtp)
                    <div class="space-y-4 card-hover">
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg badge-primary">
                            <p class="text-sm text-blue-700 mb-2">
                                <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 100 2h2a1 1 0 100-2H7zm0 4a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                                An OTP has been sent to the <strong>{{ ucfirst(str_replace('_', ' ', $pendingOtp->sent_to)) }}</strong> party.
                            </p>
                            <p class="text-xs text-blue-600">Expires at: <strong>{{ $pendingOtp->expires_at->format('M d, Y \a\t H:i') }}</strong></p>
                        </div>

                        <!-- OTP Verification Form -->
                        <form action="{{ route('transactions.verify-otp', $transaction) }}" method="POST" class="space-y-4" id="otpForm">
                            @csrf
                            <div>
                                <label for="otp_code" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Enter OTP Code <span class="text-red-600">*</span>
                                </label>
                                <input 
                                    id="otp_code"
                                    name="otp_code" 
                                    type="text" 
                                    inputmode="numeric"
                                    placeholder="000000"
                                    maxlength="6"
                                    pattern="[0-9]{6}"
                                       class="w-full input-field text-center text-2xl font-mono tracking-widest"
                                    required
                                    autocomplete="off"
                                    aria-describedby="otpHelp {{ $errors->has('otp_code') ? 'otpError' : '' }}"
                                    aria-invalid="{{ $errors->has('otp_code') ? 'true' : 'false' }}"
                                >
                                <p id="otpHelp" class="sr-only">Enter the 6 digit verification code sent to the relevant user.</p>
                                @if($errors->has('otp_code'))
                                    <p id="otpError" class="text-sm text-red-600 mt-2" role="alert">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 100 2h2a1 1 0 100-2H7zm0 4a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $errors->first('otp_code') }}
                                    </p>
                                @endif
                            </div>

                            <button 
                                type="submit" 
                                   class="btn-primary w-full"
                                aria-label="Verify OTP code"
                            >
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Verify OTP
                            </button>
                        </form>

                        <p class="text-xs text-slate-600 text-center">
                            Didn't receive the code? Check your email or contact the {{ ucfirst(str_replace('_', ' ', $pendingOtp->sent_to)) }} for resend.
                        </p>
                    </div>
                @else
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-sm text-amber-700">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            No pending OTP verification at this time. The OTP will be generated and sent once the transaction status changes to "Pending CBS Review".
                        </p>
                    </div>
                @endif
            @endif
        </div>
    @endif

    <!-- Financial Information Section -->
    <div class="bg-gradient-to-r from-emerald-50 via-amber-50 to-red-50 px-6 py-4 border-y border-amber-200">
        <h3 class="font-bold text-amber-800 flex items-center gap-2">
            <span>💵</span> Financial Information
        </h3>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="sale_price" class="block text-sm font-semibold text-slate-700 mb-2">Sale Price (Nu.) *</label>
            <input id="sale_price" name="sale_price" type="number" step="1" min="0" value="{{ old('sale_price', $transaction->sale_price) }}" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required aria-required="true" inputmode="numeric">
        </div>
        <div>
            <label for="broker_commission" class="block text-sm font-semibold text-slate-700 mb-2">Broker Commission (Nu.) *</label>
            <input id="broker_commission" name="broker_commission" type="number" step="1" min="0" value="{{ old('broker_commission', $transaction->broker_commission) }}" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required aria-required="true" inputmode="numeric">
        </div>
        <div>
            <label for="completed_at" class="block text-sm font-semibold text-slate-700 mb-2">Completed At</label>
            <input id="completed_at" name="completed_at" type="datetime-local" value="{{ old('completed_at', optional($transaction->completed_at)->format('Y-m-d\TH:i')) }}" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>
        <div class="md:col-span-2">
            <label for="notes" class="block text-sm font-semibold text-slate-700 mb-2">Notes</label>
            <textarea id="notes" name="notes" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('notes', $transaction->notes) }}</textarea>
        </div>
    </div>

    <!-- Agreement File Upload Section -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-y border-green-200">
        <h3 class="font-bold text-green-800 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Seller-Buyer Agreement
        </h3>
    </div>
    <div class="p-6 bg-white">
        @if($transaction->agreement_file)
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-green-700 mb-2">Current Agreement:</p>
                        <p class="text-sm text-gray-600">{{ basename($transaction->agreement_file) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Uploaded: {{ $transaction->agreement_uploaded_at?->format('M d, Y H:i') ?? 'Unknown' }}</p>
                    </div>
                    <a href="{{ route('transactions.download-agreement', $transaction) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download
                    </a>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4"><strong>Upload a new file to replace the existing agreement:</strong></p>
        @else
            <p class="text-sm text-gray-600 mb-4 text-amber-700">No agreement file uploaded yet. Upload the signed agreement between seller and buyer as evidence.</p>
        @endif

        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 hover:bg-blue-50 transition">
            <label for="agreementFileInput" class="block cursor-pointer focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 rounded-lg" role="button" tabindex="0" aria-label="Upload agreement file">
                <input id="agreementFileInput" name="agreement_file" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.txt" class="sr-only">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <p class="font-semibold text-gray-800 mb-1">Click to upload agreement</p>
            <p class="text-xs text-gray-600">or drag and drop</p>
            <p class="text-xs text-gray-500 mt-3">Supported formats: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, TXT (Max 10MB)</p>
            </label>
        </div>
        <div id="fileName" class="mt-3 text-sm text-gray-600"></div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <div class="text-xs sm:text-sm text-slate-600">
            <span class="font-semibold text-slate-800">Workflow:</span> buyer/seller payment is reviewed by CBS admin before confirmation is emailed.
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ $transaction->exists ? 'Update Transaction' : 'Request Payment Review' }}
            </button>
        <a href="{{ route('transactions.index') }}" class="px-6 py-2.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition font-medium text-slate-700">
            Cancel
        </a>
        </div>
    </div>
</form>

<script>
    const fileInput = document.getElementById('agreementFileInput');
    const fileNameDiv = document.getElementById('fileName');
    const dropZone = fileInput.parentElement;

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            fileNameDiv.textContent = '✓ Selected: ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            fileNameDiv.className = 'mt-3 text-sm text-green-600 font-medium';
        }
    });

    // Drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        if (files.length > 0) {
            fileNameDiv.textContent = '✓ Selected: ' + files[0].name;
            fileNameDiv.className = 'mt-3 text-sm text-green-600 font-medium';
        }
    }, false);

    // OTP Input Enhancement
    const otpInput = document.getElementById('otp_code');
    const otpForm = document.getElementById('otpForm');
    
    if (otpInput) {
        // Only allow numeric input
        otpInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '').substring(0, 6);
        });

        // Optional: Auto-submit when 6 digits entered
        otpInput.addEventListener('input', (e) => {
            if (e.target.value.length === 6 && otpForm) {
                // Uncomment the line below to auto-submit on 6 digits
                // otpForm.submit();
            }
        });

        // Focus styling
        otpInput.addEventListener('focus', (e) => {
            e.target.parentElement.classList.add('ring-2', 'ring-purple-500', 'ring-offset-2');
        });

        otpInput.addEventListener('blur', (e) => {
            e.target.parentElement.classList.remove('ring-2', 'ring-purple-500', 'ring-offset-2');
        });
    }
</script>
@endsection
