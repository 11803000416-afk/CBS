@extends('layouts.app')

@section('title', 'Book Test Drive')
@section('subtitle', 'Schedule a test drive appointment')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Vehicle Details -->
    <div class="mb-8 bg-white border-2 border-gray-200 rounded-2xl p-6 shadow-lg">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b-2 border-gray-200">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="font-bold text-gray-900 text-lg">Schedule Test Drive</h3>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Vehicle Image -->
            <div class="lg:col-span-1">
                @if($vehicle->images && count($vehicle->images) > 0)
                    <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden">
                        <img src="{{ asset('storage/' . $vehicle->images[0]) }}" 
                             alt="{{ $vehicle->brand }} {{ $vehicle->model }}" 
                             class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="aspect-video bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>
            
            <!-- Vehicle Info & Booking Form -->
            <div class="lg:col-span-1">
                <div class="mb-6">
                    <h4 class="font-bold text-gray-900 text-xl mb-3">{{ $vehicle->brand }} {{ $vehicle->model }}</h4>
                    
                    <!-- Vehicle Info -->
                    <div class="flex flex-col gap-3 text-sm">
                        <div class="flex items-center gap-4 text-gray-600">
                            <span>📅 {{ $vehicle->year }}</span>
                            <span>•</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                ✓ {{ ucfirst($vehicle->status) }}
                            </span>
                        </div>
                        
                        <!-- CAR PRICE -->
                        <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded">
                            <p class="text-xs text-gray-600 font-medium">💰 Price</p>
                            <p class="text-2xl font-bold text-blue-600">Nu. {{ number_format($vehicle->price) }}</p>
                        </div>
                </div>
                
                <form method="POST" action="{{ route('bookings.store', $vehicle) }}" class="space-y-4">
                    @csrf
                    
                    <!-- Date Selection (Calendar) -->
                    <div>
                        <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-2">
                            📅 Select Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="booking_date" 
                               name="booking_date" 
                               min="{{ now()->format('Y-m-d') }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        @error('booking_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Time Selection -->
                    <div>
                        <label for="booking_time" class="block text-sm font-medium text-gray-700 mb-2">
                            🕐 Select Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               id="booking_time" 
                               name="booking_time" 
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        @error('booking_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Message -->
                    <div>
                        <label for="buyer_message" class="block text-sm font-medium text-gray-700 mb-2">
                            💬 Message (Optional)
                        </label>
                        <textarea id="buyer_message" 
                                  name="buyer_message" 
                                  rows="4" 
                                  placeholder="Tell the seller about any specific requirements or questions..."
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"></textarea>
                        @error('buyer_message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirmation Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h5 class="font-bold text-gray-900 mb-2">📋 Booking Details</h5>
                        <p class="text-sm text-gray-700 mb-2">
                            <span class="font-medium">Vehicle:</span> {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})
                        </p>
                        <p class="text-sm text-gray-600 italic">
                            ✓ The seller will confirm your test drive by your registered date and time.
                        </p>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('vehicles.show', $vehicle) }}" 
                           class="text-gray-700 hover:text-gray-900 font-medium py-3 px-6 rounded-lg border border-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Book Test Drive
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Handle form submission with AJAX
document.querySelector('form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Booking Test Drive...
    `;
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccessAlert(data.message);
            setTimeout(() => {
                window.location.href = '{{ route("bookings.index") }}';
            }, 2000);
        } else {
            showErrorAlert(data.message || 'Booking failed. Please try again.');
        }
    } catch (error) {
        console.error(error);
        showErrorAlert('Network error. Please try again.');
    } finally {
        // Restore button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

function showSuccessAlert(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 animate-fade-in';
    alertDiv.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl border border-green-200 p-8 max-w-md w-full">
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">🎉 Success!</h3>
                <p class="text-gray-700 text-sm leading-relaxed">${message}</p>
            </div>
        </div>
    `;
    document.body.appendChild(alertDiv);
}

function showErrorAlert(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed top-4 right-4 z-50 max-w-md';
    alertDiv.innerHTML = `
        <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4 shadow-lg">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="font-bold text-red-900">Error</h4>
                    <p class="text-sm text-red-700">${message}</p>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 5000);
}
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>

@endsection

