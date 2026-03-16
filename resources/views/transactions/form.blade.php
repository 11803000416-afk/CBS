@extends('layouts.app')

@section('title', $transaction->exists ? 'Edit Transaction' : 'Add Transaction')
@section('subtitle', $transaction->exists ? 'Update transaction details' : 'Record new vehicle sale')

@section('content')
<form method="POST" action="{{ $transaction->exists ? route('transactions.update', $transaction) : route('transactions.store') }}" class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
    @csrf
    @if($transaction->exists)
        @method('PUT')
    @endif

    <!-- Transaction Details Section -->
    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
        <h3 class="font-bold text-slate-800">Transaction Details</h3>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Vehicle *</label>
            <select name="vehicle_id" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                <option value="">Select vehicle</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}" @selected(old('vehicle_id', $transaction->vehicle_id) == $vehicle->id)>{{ $vehicle->brand }} {{ $vehicle->model }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Buyer *</label>
            <select name="buyer_id" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                <option value="">Select buyer</option>
                @foreach($buyers as $buyer)
                    <option value="{{ $buyer->id }}" @selected(old('buyer_id', $transaction->buyer_id) == $buyer->id)>{{ $buyer->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Seller *</label>
            <select name="seller_id" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                <option value="">Select seller</option>
                @foreach($sellers as $seller)
                    <option value="{{ $seller->id }}" @selected(old('seller_id', $transaction->seller_id) == $seller->id)>{{ $seller->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Status *</label>
            <select name="status" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                @foreach(['completed', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $transaction->status ?: 'completed') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Financial Information Section -->
    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-y border-slate-200">
        <h3 class="font-bold text-slate-800">Financial Information</h3>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Sale Price (Nu.) *</label>
            <input name="sale_price" type="number" step="0.01" value="{{ old('sale_price', $transaction->sale_price) }}" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Broker Commission (Nu.) *</label>
            <input name="broker_commission" type="number" step="0.01" value="{{ old('broker_commission', $transaction->broker_commission) }}" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Completed At</label>
            <input name="completed_at" type="datetime-local" value="{{ old('completed_at', optional($transaction->completed_at)->format('Y-m-d\TH:i')) }}" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Notes</label>
            <textarea name="notes" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('notes', $transaction->notes) }}</textarea>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex gap-3">
        <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all font-medium">
            {{ $transaction->exists ? 'Update Transaction' : 'Save Transaction' }}
        </button>
        <a href="{{ route('transactions.index') }}" class="px-6 py-2.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition font-medium text-slate-700">
            Cancel
        </a>
    </div>
</form>
@endsection
