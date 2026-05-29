@extends('layouts.app')

@section('title', $buyer->exists ? 'Edit Buyer' : 'Add Buyer')
@section('subtitle', $buyer->exists ? 'Update buyer information' : 'Register new buyer')

@section('content')
<style>
    .form-input-wrapper {
        position: relative;
        animation: slideUp 0.6s ease-out;
    }

    .form-input-wrapper input,
    .form-input-wrapper select,
    .form-input-wrapper textarea {
        transition: all 0.3s ease;
    }

    .form-input-wrapper input:focus,
    .form-input-wrapper select:focus,
    .form-input-wrapper textarea:focus {
        background-color: rgba(255, 255, 255, 0.15);
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1), 0 0 20px rgba(2, 132, 199, 0.3);
        border-color: rgb(2, 132, 199);
        transform: translateY(-2px);
    }

    .icon-field {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(148, 163, 184, 0.8);
        width: 20px;
        height: 20px;
    }

    .input-with-icon {
        padding-left: 40px;
    }

    .error-message {
        animation: slideDown 0.3s ease-out;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .success-animation {
        animation: scaleIn 0.5s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

<!-- Header Section with Animation -->
<div class="mb-8 animate-fadeIn">
    <div class="flex items-center gap-3 mb-2">
        <div class="p-3 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-lg border border-blue-500/30">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-white">{{ $buyer->exists ? 'Edit Buyer' : 'Add New Buyer' }}</h2>
            <p class="text-sm text-gray-400 mt-1">{{ $buyer->exists ? 'Update buyer information' : 'Register a new buyer to your system' }}</p>
        </div>
    </div>
</div>

<!-- Validation Errors with Animation -->
@if (isset($errors) && $errors->any())
    <div class="mb-6 p-4 rounded-xl border border-red-500/30 bg-red-500/10 backdrop-blur-xl animate-slideDown" style="animation-delay: 0.1s;">
        <div class="flex items-start gap-3">
            <div class="p-2 rounded-lg bg-red-500/20">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-red-300 mb-2">Please correct the following errors:</h3>
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-red-200 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<!-- Form Card -->
<form method="POST" action="{{ $buyer->exists ? route('buyers.update', $buyer) : route('buyers.store') }}" class="glass-card rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
    @csrf
    @if($buyer->exists)
        @method('PUT')
    @endif

    <!-- Form Header Section -->
    <div class="bg-gradient-to-r from-blue-600/20 via-cyan-600/20 to-blue-600/20 px-8 py-6 border-b border-white/10 backdrop-blur-xl">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg bg-blue-500/20 border border-blue-500/30">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="font-bold text-lg text-white">Buyer Details</h3>
        </div>
    </div>

    <!-- Form Content -->
    <div class="p-8">
        <!-- First Row: Name and Phone -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Name Field -->
            <div class="form-input-wrapper">
                <label class="block text-sm font-semibold text-gray-200 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Full Name <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <input 
                        type="text"
                        name="name" 
                        value="{{ old('name', $buyer->name) }}" 
                        placeholder="Enter buyer's full name"
                        class="w-full bg-white/10 border {{ $errors && $errors->has('name') ? 'border-red-500' : 'border-white/20' }} rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none"
                        required>
                </div>
                @if($errors && $errors->has('name'))
                    <p class="text-red-400 text-xs mt-2 error-message">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L9 16.586 5.313 12.899a1 1 0 00-1.414 1.414l4.424 4.424a1 1 0 001.414 0l8.676-8.676z"/>
                        </svg>
                        {{ $errors->first('name') }}
                    </p>
                @endif
            </div>

            <!-- Phone Field -->
            <div class="form-input-wrapper">
                <label class="block text-sm font-semibold text-gray-200 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948.684l1.498 7.492a1 1 0 00.502.756l4.618 2.311a1 1 0 001.097-.126l2.82-2.82a1 1 0 011.412 0l2.82 2.82a1 1 0 001.126 1.097l2.311 4.618a1 1 0 00.756.502l7.492 1.498a1 1 0 00.684.948v3.28a2 2 0 01-2 2h-1C9.716 20 3 13.284 3 5z"/>
                    </svg>
                    Phone Number <span class="text-red-400">*</span>
                </label>
                <input 
                    type="tel"
                    name="phone" 
                    value="{{ old('phone', $buyer->phone) }}" 
                    placeholder="Enter phone number"
                    class="w-full bg-white/10 border {{ $errors && $errors->has('phone') ? 'border-red-500' : 'border-white/20' }} rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none"
                    required>
                @if($errors && $errors->has('phone'))
                    <p class="text-red-400 text-xs mt-2 error-message">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L9 16.586 5.313 12.899a1 1 0 00-1.414 1.414l4.424 4.424a1 1 0 001.414 0l8.676-8.676z"/>
                        </svg>
                        {{ $errors->first('phone') }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Second Row: Email and Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Email Field -->
            <div class="form-input-wrapper">
                <label class="block text-sm font-semibold text-gray-200 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Email Address
                </label>
                <input 
                    type="email"
                    name="email" 
                    value="{{ old('email', $buyer->email) }}" 
                    placeholder="Enter email address"
                    class="w-full bg-white/10 border {{ $errors && $errors->has('email') ? 'border-red-500' : 'border-white/20' }} rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none">
                @if($errors && $errors->has('email'))
                    <p class="text-red-400 text-xs mt-2 error-message">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L9 16.586 5.313 12.899a1 1 0 00-1.414 1.414l4.424 4.424a1 1 0 001.414 0l8.676-8.676z"/>
                        </svg>
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>

            <!-- Status Field -->
            <div class="form-input-wrapper">
                <label class="block text-sm font-semibold text-gray-200 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Status <span class="text-red-400">*</span>
                </label>
                <select 
                    name="status" 
                    class="w-full bg-white/10 border {{ $errors && $errors->has('status') ? 'border-red-500' : 'border-white/20' }} rounded-lg px-4 py-3 text-white focus:outline-none appearance-none cursor-pointer"
                    required>
                    <option value="" class="bg-gray-900">Choose status...</option>
                    @foreach(['active', 'inactive'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $buyer->status ?: 'active') === $status) class="bg-gray-900">
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                @if($errors && $errors->has('status'))
                    <p class="text-red-400 text-xs mt-2 error-message">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L9 16.586 5.313 12.899a1 1 0 00-1.414 1.414l4.424 4.424a1 1 0 001.414 0l8.676-8.676z"/>
                        </svg>
                        {{ $errors->first('status') }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Full Width: Address -->
        <div class="form-input-wrapper mb-6">
            <label class="block text-sm font-semibold text-gray-200 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Address
            </label>
            <input 
                type="text"
                name="address" 
                value="{{ old('address', $buyer->address) }}" 
                placeholder="Enter full address"
                class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none">
        </div>

        <!-- Info Box -->
        <div class="p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-blue-200">Fields marked with <span class="text-red-400 font-semibold">*</span> are required. Please ensure all information is accurate.</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white/5 px-8 py-6 border-t border-white/10 flex gap-4 flex-col sm:flex-row">
        <button 
            type="submit" 
            class="flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white px-8 py-3 rounded-lg transition-all font-semibold shadow-lg hover:shadow-blue-500/50 hover:shadow-xl transform hover:scale-105 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ $buyer->exists ? 'Update Buyer' : 'Create Buyer' }}
        </button>
        <a 
            href="{{ route('buyers.index') }}" 
            class="flex items-center justify-center gap-2 px-8 py-3 rounded-lg border border-white/20 hover:bg-white/10 hover:border-white/40 transition-all font-semibold text-gray-300 hover:text-white transform hover:scale-105 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Cancel
        </a>
    </div>
</form>
@endsection
