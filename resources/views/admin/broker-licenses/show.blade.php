@extends('layouts.app')

@section('title', 'Broker License Review')
@section('subtitle', 'Approve or reject broker dealer license access')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <header class="border-b border-slate-200 bg-gradient-to-r from-cyan-600 to-blue-700 px-6 py-4">
            <h2 class="text-lg font-bold text-white">Broker: {{ $broker->name }}</h2>
        </header>

        <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-2">
            <article class="space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-4" aria-label="Broker profile details">
                <h3 class="text-base font-bold text-slate-900">Profile Details</h3>
                <p class="text-sm text-slate-700"><span class="font-semibold">Email:</span> {{ $broker->email }}</p>
                <p class="text-sm text-slate-700"><span class="font-semibold">Phone:</span> {{ $broker->phone ?: 'N/A' }}</p>
                <p class="text-sm text-slate-700"><span class="font-semibold">Address:</span> {{ $broker->address ?: 'N/A' }}</p>
                <p class="text-sm text-slate-700"><span class="font-semibold">Role:</span> {{ ucfirst($broker->role) }}</p>
                <p class="text-sm text-slate-700"><span class="font-semibold">Current status:</span>
                    <span class="badge {{ $broker->dealer_license_status === 'approved' ? 'badge-success' : ($broker->dealer_license_status === 'pending' ? 'badge-warning' : ($broker->dealer_license_status === 'rejected' ? 'badge-danger' : 'badge-primary')) }}">
                        {{ ucfirst(str_replace('_', ' ', $broker->dealer_license_status)) }}
                    </span>
                </p>
                <p class="text-sm text-slate-700"><span class="font-semibold">Submitted:</span> {{ optional($broker->dealer_license_submitted_at)->format('M d, Y H:i') ?: 'N/A' }}</p>
                @if($broker->dealer_license_reviewed_at)
                    <p class="text-sm text-slate-700"><span class="font-semibold">Reviewed:</span> {{ $broker->dealer_license_reviewed_at->format('M d, Y H:i') }}</p>
                @endif
                @if($reviewer)
                    <p class="text-sm text-slate-700"><span class="font-semibold">Reviewer:</span> {{ $reviewer->name }}</p>
                @endif
            </article>

            <article class="space-y-3 rounded-xl border border-slate-200 bg-white p-4" aria-label="Dealer license details">
                <h3 class="text-base font-bold text-slate-900">Dealer License</h3>
                <p class="text-sm text-slate-700"><span class="font-semibold">License Number:</span> {{ $broker->dealer_license_number ?: 'Not provided' }}</p>
                @if($broker->dealer_license_document_path)
                    <a href="{{ asset('storage/' . $broker->dealer_license_document_path) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        View Uploaded License Document
                    </a>
                @endif
                @if($broker->dealer_license_admin_notes)
                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-3" role="note">
                        <p class="text-xs font-bold uppercase tracking-wide text-amber-700">Existing Admin Notes</p>
                        <p class="mt-1 text-sm text-amber-900">{{ $broker->dealer_license_admin_notes }}</p>
                    </div>
                @endif
            </article>
        </div>
    </section>

    @if($broker->dealer_license_status === 'pending')
        <section class="grid grid-cols-1 gap-6 lg:grid-cols-2" aria-label="License review actions">
            <form method="POST" action="{{ route('admin.broker-licenses.approve', $broker) }}" class="rounded-2xl border border-green-200 bg-green-50 p-5">
                @csrf
                <h3 class="text-base font-bold text-green-900">Approve License</h3>
                <label for="approve_notes" class="mt-3 block text-sm font-semibold text-slate-800">Admin Notes (optional)</label>
                <textarea id="approve_notes" name="dealer_license_admin_notes" rows="4" class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                <button type="submit" class="mt-4 w-full rounded-lg bg-green-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-green-700" onclick="return confirm('Approve this broker dealer license?');">
                    Approve Broker Access
                </button>
            </form>

            <form method="POST" action="{{ route('admin.broker-licenses.reject', $broker) }}" class="rounded-2xl border border-red-200 bg-red-50 p-5">
                @csrf
                <h3 class="text-base font-bold text-red-900">Reject License</h3>
                <label for="reject_notes" class="mt-3 block text-sm font-semibold text-slate-800">Rejection Reason *</label>
                <textarea id="reject_notes" name="dealer_license_admin_notes" rows="4" required class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                <button type="submit" class="mt-4 w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-red-700" onclick="return confirm('Reject this broker dealer license?');">
                    Reject Broker Access
                </button>
            </form>
        </section>
    @endif

    <div>
        <a href="{{ route('admin.broker-licenses.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
            Back to Requests
        </a>
    </div>
</div>
@endsection
