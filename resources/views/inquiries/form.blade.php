@extends('layouts.app')

@section('title', $inquiry->exists ? 'Edit Inquiry' : 'Add Inquiry')
@section('subtitle', $inquiry->exists ? 'Update inquiry details' : 'Create new customer inquiry')

@section('content')
<form method="POST" action="{{ $inquiry->exists ? route('inquiries.update', $inquiry) : route('inquiries.store') }}" class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
    @csrf
    @if($inquiry->exists)
        @method('PUT')
    @endif

    <!-- Inquiry Information Section -->
    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
        <h3 class="font-bold text-slate-800">Inquiry Information</h3>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Vehicle *</label>
            @if($vehicles->count() > 0)
                <select name="vehicle_id" class="w-full border @error('vehicle_id') border-red-500 @else border-slate-300 @enderror rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                    <option value="">Select vehicle</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" @selected(old('vehicle_id', $inquiry->vehicle_id) == $vehicle->id)>{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})</option>
                    @endforeach
                </select>
                @error('vehicle_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            @else
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-amber-800 text-sm">
                    <p class="font-semibold mb-1">No Available Vehicles</p>
                    <p>There are currently no vehicles available for inquiry. Please check back later.</p>
                </div>
            @endif
        </div>
        @if(auth()->user()->role !== 'buyer')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Inquiry From</label>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-700 font-medium">This inquiry will be created as: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})</p>
                </div>
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-700 font-medium">Inquiry from: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})</p>
            </div>
        @endif
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Message *</label>
            <textarea name="message" rows="4" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="I'm interested in this vehicle..." required>{{ old('message', $inquiry->message) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Preferred Meeting Location</label>
            <input type="text" name="meeting_location" value="{{ old('meeting_location', $inquiry->meeting_location) }}" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="e.g., Thimphu, Paro">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Preferred Meeting Time</label>
            <input type="datetime-local" name="preferred_time" value="{{ old('preferred_time', $inquiry->preferred_time?->format('Y-m-d\TH:i')) }}" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Special Requirements</label>
            <textarea name="special_requirements" rows="3" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Any special requests or requirements...">{{ old('special_requirements', $inquiry->special_requirements) }}</textarea>
        </div>
    </div>

    @if($inquiry->exists)
        <!-- Response Section -->
        <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-y border-slate-200">
            <h3 class="font-bold text-slate-800">Response Details</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Response</label>
                <textarea name="response" rows="4" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('response', $inquiry->response) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                <select name="status" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                    @foreach(['pending','responded','closed'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $inquiry->status ?: 'pending') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex gap-3">
        <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all font-medium">
            {{ $inquiry->exists ? 'Update Inquiry' : 'Save Inquiry' }}
        </button>
        <a href="{{ route('inquiries.index') }}" class="px-6 py-2.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition font-medium text-slate-700">
            Cancel
        </a>
    </div>
</form>
@endsection
