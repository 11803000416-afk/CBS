@extends('layouts.app')

@section('title', 'Transactions')
@section('subtitle', 'View and manage vehicle sales transactions')

@section('content')
<div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center mb-6">
    <p class="text-sm text-slate-600">Total: <span class="font-semibold text-slate-800">{{ $transactions->total() }}</span> transactions</p>
    <a href="{{ route('transactions.create') }}" class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-5 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Transaction
    </a>
</div>

<div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-emerald-50 via-amber-50 to-red-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-4 text-left font-semibold text-slate-700">Vehicle</th>
                    <th class="px-4 py-4 text-left font-semibold text-slate-700">Buyer</th>
                    <th class="px-4 py-4 text-left font-semibold text-slate-700">Seller</th>
                    <th class="px-4 py-4 text-left font-semibold text-emerald-700">💰 Sale Price (Nu.)</th>
                    <th class="px-4 py-4 text-left font-semibold text-amber-700">Commission (Nu.)</th>
                    <th class="px-4 py-4 text-left font-semibold text-slate-700">Status</th>
                    <th class="px-4 py-4 text-left font-semibold text-purple-700">🔐 OTP Status</th>
                    <th class="px-4 py-4 text-left font-semibold text-slate-700">Agreement</th>
                    <th class="px-4 py-4 text-left font-semibold text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transactions as $transaction)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-4 font-semibold text-slate-800">{{ $transaction->vehicle->brand ?? '-' }} {{ $transaction->vehicle->model ?? '' }}</td>
                        <td class="px-4 py-4 text-slate-600">{{ $transaction->buyer->name ?? '-' }}</td>
                        <td class="px-4 py-4 text-slate-600">{{ $transaction->seller->name ?? '-' }}</td>
                        <td class="px-4 py-4 font-semibold text-emerald-600">💵 {{ $transaction->currency ?? 'Nu.' }} {{ number_format($transaction->sale_price, 2) }}</td>
                        <td class="px-4 py-4 font-semibold text-amber-600">💵 {{ $transaction->currency ?? 'Nu.' }} {{ number_format($transaction->broker_commission, 2) }}</td>
                        <td class="px-4 py-4">
                            @php
                                $statusClasses = [
                                    'pending_review' => 'bg-amber-100 text-amber-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                                $statusLabels = [
                                    'pending_review' => 'Pending Review',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                ];
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$transaction->status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            @if($transaction->status === 'pending_review')
                                @if($transaction->otp_verified_at)
                                    <div class="flex items-center gap-2">
                                           <span class="badge badge-success">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Verified
                                        </span>
                                        <span title="Verified at: {{ $transaction->otp_verified_at->format('M d, Y H:i') }}" class="text-xs text-slate-500">
                                            {{ $transaction->otp_verified_at->format('M d') }}
                                        </span>
                                    </div>
                                @else
                                        <span class="badge badge-warning">
                                        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Awaiting
                                    </span>
                                @endif
                            @else
                                <span class="text-xs text-slate-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($transaction->agreement_file)
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-700">✓ Uploaded</span>
                                    <a href="{{ route('transactions.download-agreement', $transaction) }}" class="text-blue-600 hover:text-blue-800 font-medium" title="Download agreement">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>
                                </div>
                            @else
                                <span class="inline-flex px-2 py-1 rounded text-xs font-semibold bg-amber-100 text-amber-700">✗ Missing</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <a href="{{ route('transactions.edit', $transaction) }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 transition focus:outline-none focus:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                    </a>
                                    <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" onsubmit="return confirm('Are you sure?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800 font-medium flex items-center gap-1 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Delete
                                    </button>
                                    </form>
                                </div>

                                @if(auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN) && $transaction->status === 'pending_review')
                                    <div class="flex flex-wrap gap-2 pt-2 border-t border-slate-100">
                                        <button type="button" onclick="openReviewModal('approve', {{ $transaction->id }})" class="px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Approve
                                        </button>
                                        <button type="button" onclick="openReviewModal('reject', {{ $transaction->id }})" class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Reject
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-slate-400">No transaction records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">{{ $transactions->links() }}</div>

<!-- Admin Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="reviewModalTitle">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <h2 id="reviewModalTitle" class="text-lg font-bold text-slate-800">Review Payment Request</h2>
            <button type="button" onclick="closeReviewModal()" class="text-slate-400 hover:text-slate-600 focus:outline-none" aria-label="Close modal">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="reviewForm" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="reviewAction" name="action">
            <input type="hidden" id="reviewTransactionId" name="transaction_id">

            <div>
                <label for="reviewNotes" class="block text-sm font-semibold text-slate-700 mb-2">
                    Review Notes <span class="text-slate-400 text-xs">(optional)</span>
                </label>
                <textarea id="reviewNotes" name="review_notes" rows="4" maxlength="1000" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 resize-none" placeholder="Add any notes about this transaction..."></textarea>
                <p class="text-xs text-slate-500 mt-1">
                    <span id="charCount">0</span>/1000 characters
                </p>
            </div>

            <div class="pt-4 flex gap-3 justify-end">
                <button type="button" onclick="closeReviewModal()" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition">
                    Cancel
                </button>
                <button type="submit" id="submitBtn" class="px-4 py-2 rounded-lg text-white font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 transition">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentAction = '';
    let currentTransactionId = '';

    function openReviewModal(action, transactionId) {
        currentAction = action;
        currentTransactionId = transactionId;

        const modal = document.getElementById('reviewModal');
        const form = document.getElementById('reviewForm');
        const submitBtn = document.getElementById('submitBtn');
        const actionInput = document.getElementById('reviewAction');
        const transactionInput = document.getElementById('reviewTransactionId');
        const textarea = document.getElementById('reviewNotes');

        // Reset form
        textarea.value = '';
        document.getElementById('charCount').textContent = '0';

        // Update form action
        const endpoint = action === 'approve'
            ? `{{ route('transactions.approve-payment', ':id') }}`
            : `{{ route('transactions.reject-payment', ':id') }}`;

        form.action = endpoint.replace(':id', transactionId);
        actionInput.value = action;
        transactionInput.value = transactionId;

        // Update button styling
        if (action === 'approve') {
            submitBtn.className = 'px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition';
            submitBtn.textContent = 'Approve Payment';
        } else {
            submitBtn.className = 'px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition';
            submitBtn.textContent = 'Reject Payment';
        }

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        textarea.focus();
    }

    function closeReviewModal() {
        const modal = document.getElementById('reviewModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Character counter
    document.getElementById('reviewNotes').addEventListener('input', function() {
        document.getElementById('charCount').textContent = this.value.length;
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeReviewModal();
        }
    });

    // Close modal on outside click
    document.getElementById('reviewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReviewModal();
        }
    });
</script>
@endsection
