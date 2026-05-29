@extends('layouts.app')

@section('title', 'Broker Application')
@section('subtitle', 'Submit your dealer license for admin approval to become a broker')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4" role="status" aria-live="polite">
            <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 p-4" role="alert" aria-live="assertive">
            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <header class="border-b border-slate-200 bg-gradient-to-r from-cyan-50 to-blue-50 px-6 py-4">
            <h2 class="text-lg font-bold text-slate-900">Broker Application Status</h2>
        </header>

        <div class="space-y-5 p-6">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-slate-600">Current status:</span>
                <span class="badge {{ $user->dealer_license_status === 'approved' ? 'badge-success' : ($user->dealer_license_status === 'pending' ? 'badge-warning' : ($user->dealer_license_status === 'rejected' ? 'badge-danger' : 'badge-primary')) }}">
                    {{ ucfirst(str_replace('_', ' ', $user->dealer_license_status ?? 'not_submitted')) }}
                </span>
            </div>

            @if($user->dealer_license_admin_notes)
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4" role="note">
                    <p class="text-xs font-bold uppercase tracking-wide text-amber-700">Admin Notes</p>
                    <p class="mt-1 text-sm text-amber-900">{{ $user->dealer_license_admin_notes }}</p>
                </div>
            @endif

            <p class="text-sm text-slate-600" id="broker-license-help">
                Until your dealer license is approved, broker deal actions for vehicles and transactions remain restricted. Buyers can use this form to request broker access, and existing brokers can submit license details for approval.
            </p>

            <form action="{{ route('broker.license.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4" aria-describedby="broker-license-help">
                @csrf
                <div>
                    <label for="dealer_license_number" class="label-light">Dealer License Number <span class="text-red-600">*</span></label>
                    <input
                        id="dealer_license_number"
                        name="dealer_license_number"
                        type="text"
                        value="{{ old('dealer_license_number', $user->dealer_license_number) }}"
                        class="input-field-light"
                        required
                        aria-required="true"
                        maxlength="100"
                    >
                    @error('dealer_license_number')
                        <p class="mt-2 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="dealer_license_document" class="label-light">Dealer License Document (PDF/JPG/PNG)</label>
                    <input
                        id="dealer_license_document"
                        name="dealer_license_document"
                        type="file"
                        class="input-field-light"
                        accept=".pdf,.jpg,.jpeg,.png"
                    >
                    @if($user->dealer_license_document_path)
                        <p class="mt-2 text-xs text-slate-500">Current document uploaded and stored.</p>
                    @endif
                    @error('dealer_license_document')
                        <p class="mt-2 text-sm text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button type="submit" class="btn btn-primary">
                        Submit For Approval
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
