@extends('layouts.app')

@section('title', 'My Bookings')
@section('subtitle', 'Manage your vehicle bookings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 bg-white border-2 border-gray-200 rounded-2xl p-6 shadow-lg">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b-2 border-gray-200">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="font-bold text-gray-900 text-lg">My Bookings</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $bookings->where('status', 'pending')->count() }}</p>
                <p class="text-sm text-gray-600">Pending</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ $bookings->where('status', 'confirmed')->count() }}</p>
                <p class="text-sm text-gray-600">Confirmed</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-red-600">{{ $bookings->where('status', 'cancelled')->count() }}</p>
                <p class="text-sm text-gray-600">Cancelled</p>
            </div>
        </div>
    </div>

    <!-- Bookings List -->
    @if($bookings->count() > 0)
        <div class="bg-white border-2 border-gray-200 rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($booking->vehicle->images && count($booking->vehicle->images) > 0)
                                            <img src="{{ asset('storage/' . $booking->vehicle->images[0]) }}" 
                                                 alt="{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}" 
                                                 class="w-10 h-10 rounded-lg object-cover mr-3">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center mr-3">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                                            <p class="text-sm text-gray-500">{{ $booking->vehicle->year }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <p>{{ optional($booking->booking_date)->format('M d, Y') }}</p>
                                        <p>{{ optional($booking->booking_time)->format('g:i A') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $booking->status_badge }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        @if($booking->status === 'pending' && auth()->user()->hasRole(\App\Models\User::ROLE_SELLER))
                                            <button onclick="confirmBooking({{ $booking->id }})" 
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md text-xs font-medium transition">
                                                Confirm
                                            </button>
                                        @endif
                                        
                                        @if($booking->status === 'pending' && auth()->user()->id === $booking->buyer_id)
                                            <button onclick="cancelBooking({{ $booking->id }})" 
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs font-medium transition">
                                                Cancel
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('bookings.show', $booking) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-xs font-medium">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-white border-2 border-gray-200 rounded-2xl p-8">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2v0a2 2 0 00-2-2H9z"/>
            </svg>
            <p class="text-gray-500 font-semibold text-lg mb-2">No bookings found</p>
            <p class="text-gray-400 text-sm">Your booking requests and confirmations will appear here</p>
        </div>
    @endif
</div>

<script>
function confirmBooking(bookingId) {
    if (confirm('Are you sure you want to confirm this booking?')) {
        fetch(`/bookings/${bookingId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: 'confirmed',
                seller_message: 'Booking confirmed by seller'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to confirm booking');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to confirm booking');
        });
    }
}

function cancelBooking(bookingId) {
    if (confirm('Are you sure you want to cancel this booking?')) {
        fetch(`/bookings/${bookingId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to cancel booking');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to cancel booking');
        });
    }
}
</script>
@endsection
