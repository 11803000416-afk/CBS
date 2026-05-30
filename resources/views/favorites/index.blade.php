@extends('layouts.app')

@section('title', 'My Favorites - CBS')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-12">
            <div class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-cyan-50 to-blue-50 px-4 py-2 mb-4">
                <svg class="w-5 h-5 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.172 2.172a4 4 0 015.656 0l2.828 2.828a4 4 0 010 5.656l-8.485 8.485a4 4 0 01-5.656-5.656l2.828-2.828"></path>
                </svg>
                <span class="text-sm font-semibold text-cyan-700">Saved Vehicles</span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">My Favorite Vehicles</h1>
            <p class="text-lg text-gray-600">{{ $favorites->total() }} vehicle{{ $favorites->total() !== 1 ? 's' : '' }} saved</p>
        </div>

        @if($favorites->count() > 0)
            <!-- Action Bar -->
            <div class="mb-8 flex flex-col sm:flex-row gap-4">
                <button onclick="clearAllFavorites()" class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Clear All
                    </span>
                </button>

                @if($favorites->count() >= 2)
                    <button onclick="compareSelected()" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h2m0-16V3m0 16h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 16v2m0-16h16"></path>
                            </svg>
                            Compare Selected
                        </span>
                    </button>
                @endif
            </div>

            <!-- Favorites Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($favorites as $favorite)
                    <div class="group bg-white rounded-xl shadow hover:shadow-xl transition duration-300 overflow-hidden">
                        <!-- Image -->
                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            @if($favorite->vehicle->image)
                                <img src="{{ asset('storage/' . $favorite->vehicle->image) }}" alt="{{ $favorite->vehicle->brand }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Favorite Badge -->
                            <button onclick="removeFavorite({{ $favorite->id }})" class="absolute top-3 right-3 p-2 bg-red-500 hover:bg-red-600 text-white rounded-full transition shadow-lg">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.172 2.172a4 4 0 015.656 0l2.828 2.828a4 4 0 010 5.656l-8.485 8.485a4 4 0 01-5.656-5.656l2.828-2.828"></path>
                                </svg>
                            </button>

                            <!-- Status Badge -->
                            @if($favorite->vehicle->status === 'sold')
                                <div class="absolute top-3 left-3 px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full">SOLD</div>
                            @elseif($favorite->vehicle->status === 'active')
                                <div class="absolute top-3 left-3 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">ACTIVE</div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $favorite->vehicle->brand }} {{ $favorite->vehicle->model }}</h3>
                            <p class="text-sm text-gray-500 mb-3">{{ $favorite->vehicle->year }}</p>

                            <!-- Price -->
                            <div class="mb-3 p-3 bg-gradient-to-r from-cyan-50 to-blue-50 rounded-lg">
                                <p class="text-sm text-gray-600">Price</p>
                                <p class="text-2xl font-bold text-cyan-600">{{ number_format($favorite->vehicle->price) }}</p>
                            </div>

                            <!-- Details -->
                            <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 9a2 2 0 104 0A2 2 0 005 9z"></path>
                                    </svg>
                                    <span class="text-gray-600">{{ number_format($favorite->vehicle->mileage) }} km</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 9a2 2 0 104 0A2 2 0 005 9z"></path>
                                    </svg>
                                    <span class="text-gray-600">{{ $favorite->vehicle->transmission ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('vehicles.show', $favorite->vehicle->id) }}" class="w-full py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white text-center font-medium rounded-lg transition text-sm">
                                    View
                                </a>
                                <button onclick="toggleCompare({{ $favorite->vehicle->id }})" class="w-full py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition text-sm">
                                    Compare
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $favorites->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 2.172a4 4 0 015.656 0l2.828 2.828a4 4 0 010 5.656l-8.485 8.485a4 4 0 01-5.656-5.656l2.828-2.828"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Favorites Yet</h3>
                <p class="text-gray-600 mb-6">Start adding vehicles to your favorites to keep track of your favorites</p>
                <a href="{{ route('vehicles.browse') }}" class="inline-block px-8 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-lg transition">
                    Browse Vehicles
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function removeFavorite(favoriteId) {
    fetch(`/favorites/${favoriteId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(r => r.json()).then(d => {
        if (d.success) {
            location.reload();
        }
    });
}

function clearAllFavorites() {
    if (confirm('Are you sure you want to clear all favorites?')) {
        fetch('/favorites', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(r => r.json()).then(d => {
            if (d.success) location.reload();
        });
    }
}

let selectedForComparison = [];

function toggleCompare(vehicleId) {
    if (selectedForComparison.includes(vehicleId)) {
        selectedForComparison = selectedForComparison.filter(id => id !== vehicleId);
    } else {
        if (selectedForComparison.length >= 4) {
            alert('Maximum 4 vehicles can be compared');
            return;
        }
        selectedForComparison.push(vehicleId);
    }
}

function compareSelected() {
    if (selectedForComparison.length < 2) {
        alert('Select at least 2 vehicles to compare');
        return;
    }
    
    const url = new URLSearchParams();
    selectedForComparison.forEach(id => url.append('vehicle_ids[]', id));
    window.location.href = `/comparisons/quick/compare?${url}`;
}
</script>
@endsection
