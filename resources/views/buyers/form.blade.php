@extends('layouts.app')

@section('title', $buyer->exists ? 'Edit Buyer' : 'Add Buyer')
@section('subtitle', $buyer->exists ? 'Update buyer information' : 'Register new buyer')

@section('content')
<form method="POST" action="{{ $buyer->exists ? route('buyers.update', $buyer) : route('buyers.store') }}" class="glass-card rounded-xl overflow-hidden">
    @csrf
    @if($buyer->exists)
        @method('PUT')
    @endif

    <!-- Buyer Information Section -->
    <div class="bg-white/10 px-6 py-4 border-b border-white/10">
        <h3 class="font-bold text-white">Buyer Information</h3>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Name *</label>
            <input name="name" value="{{ old('name', $buyer->name) }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Phone *</label>
            <input name="phone" value="{{ old('phone', $buyer->phone) }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Email</label>
            <input name="email" type="email" value="{{ old('email', $buyer->email) }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-2">Status *</label>
            <select name="status" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                @foreach(['active', 'inactive'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $buyer->status ?: 'active') === $status) class="bg-gray-800">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-300 mb-2">Address</label>
            <input name="address" value="{{ old('address', $buyer->address) }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white/5 px-6 py-4 border-t border-white/10 flex gap-3">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg transition-all font-medium">
            {{ $buyer->exists ? 'Update Buyer' : 'Save Buyer' }}
        </button>
        <a href="{{ route('buyers.index') }}" class="px-6 py-2.5 rounded-lg border border-white/20 hover:bg-white/10 transition font-medium text-gray-300">
            Cancel
        </a>
    </div>
</form>
@endsection
