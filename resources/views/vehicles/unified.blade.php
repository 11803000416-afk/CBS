@extends('layouts.app')

@section('title', 'Vehicles')
@section('subtitle', 'Browse and manage vehicle inventory')

@section('content')
<!-- Enhanced Search & Filter Section -->
<div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl p-8 text-white shadow-2xl mb-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-4xl font-bold mb-2">Vehicle Gallery</h1>
            <p class="text-blue-100 text-lg">Discover our collection of available vehicles</p>
        </div>
        @if(auth()->check() && auth()->user()->hasRole(['admin','broker']))
            <div class="flex gap-3">
                <a href="{{ route('vehicles.create') }}" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-lg transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Vehicle
                </a>
                <a href="{{ route('my-vehicles.index') }}" class="px-6 py-3 bg-white text-blue-600 font-bold rounded-lg transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    My Vehicles
                </a>
            </div>
        @endif
    </div>
    
    <form method="GET" action="{{ route('vehicles.browse') }}" class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <!-- Brand Filter -->
        <div>
            <label class="block text-sm font-medium text-blue-100 mb-2">Brand</label>
            <div class="relative">
                <input id="brandInput" type="text" name="brand" value="{{ request('brand') }}" autocomplete="off"
                       class="w-full px-4 py-3 rounded-lg bg-white text-gray-900 shadow-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                       placeholder="e.g. Toyota, BMW">

                <!-- Suggestions dropdown (populated by JS) -->
                <div id="brandSuggestions" class="hidden absolute left-0 right-0 mt-1 bg-white rounded-lg shadow-lg z-50 max-h-56 overflow-y-auto"></div>
            </div>
        </div>

        <!-- Model Filter -->
        <div>
            <label class="block text-sm font-medium text-blue-100 mb-2">Model</label>
            <input type="text" name="model" value="{{ request('model') }}" 
                   class="w-full px-4 py-3 rounded-lg bg-white text-gray-900 shadow-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                   placeholder="e.g. Civic, 3 Series">
        </div>

        <!-- Year Filter -->
        <div>
            <label class="block text-sm font-medium text-blue-100 mb-2">Year</label>
            <input type="number" name="year" value="{{ request('year') }}" 
                   class="w-full px-4 py-3 rounded-lg bg-white text-gray-900 shadow-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                   placeholder="2024">
        </div>

        <!-- Transmission Filter -->
        <div>
            <label class="block text-sm font-medium text-blue-100 mb-2">Transmission</label>
            <select name="transmission" class="w-full px-4 py-3 rounded-lg bg-white text-gray-900 shadow-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                <option value="">Any</option>
                @foreach(['Automatic','Manual','Clutchless Manual'] as $t)
                    <option value="{{ $t }}" @selected(request('transmission') === $t)>{{ $t }}</option>
                @endforeach
            </select>
        </div>

        <!-- Min Price Filter -->
        <div>
            <label class="block text-sm font-medium text-blue-100 mb-2">Min Price</label>
            <input type="number" name="min_price" value="{{ request('min_price') }}" 
                   class="w-full px-4 py-3 rounded-lg bg-white text-gray-900 shadow-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                   placeholder="0">
        </div>

        <!-- Max Price Filter -->
        <div>
            <label class="block text-sm font-medium text-blue-100 mb-2">Max Price</label>
            <input type="number" name="max_price" value="{{ request('max_price') }}" 
                   class="w-full px-4 py-3 rounded-lg bg-white text-gray-900 shadow-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                   placeholder="999999">
        </div>

        <!-- Fuel Type Filter -->
        <div>
            <label class="block text-sm font-medium text-blue-100 mb-2">Fuel Type</label>
            <select name="fuel_type" class="w-full px-4 py-3 rounded-lg bg-white text-gray-900 shadow-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                <option value="">Any</option>
                @foreach(['Petrol','Diesel','Electric','Hybrid','CNG','LPG'] as $f)
                    <option value="{{ $f }}" @selected(request('fuel_type') === $f)>{{ $f }}</option>
                @endforeach
            </select>
        </div>

        <!-- Status Filter (for admin) -->
        @if(auth()->check() && auth()->user()->hasRole(['admin','broker']))
            <div>
                <label class="block text-sm font-medium text-blue-100 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-3 rounded-lg bg-white text-gray-900 shadow-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                    <option value="">All Status</option>
                    @foreach(['available', 'reserved', 'sold'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Search Buttons -->
        <div class="flex items-end gap-2 lg:col-span-2">
            <button type="submit" class="flex-1 px-6 py-3 rounded-lg bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold shadow-lg hover:shadow-xl transition-all">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Search
            </button>
            <a href="{{ route('vehicles.browse') }}" class="px-4 py-3 rounded-lg bg-white text-gray-900 font-bold shadow-lg hover:shadow-xl transition-all">
                Clear
            </a>
        </div>
    </form>
</div>

<!-- Results Header -->
<div class="mb-8">
    @if($vehicles->count() > 0)
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Available Vehicles</h2>
                <p class="text-gray-600 mt-1">Found <span class="font-bold text-blue-600">{{ $vehicles->total() }}</span> vehicles</p>
            </div>
            
            <!-- View Options -->
            <div class="flex gap-2">
                <button onclick="setViewMode('grid')" class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button onclick="setViewMode('list')" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    @else
        <div class="text-center py-16 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 rounded-2xl border-2 border-dashed border-gray-300">
            <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-700 font-bold text-lg mb-2">No vehicles found</p>
            <p class="text-gray-600 text-sm mb-4">Try adjusting your search criteria or add new vehicles</p>
        @if(auth()->check() && auth()->user()->hasRole(['admin','broker']))
                <a href="{{ route('vehicles.create') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-bold text-base px-6 py-2 bg-white rounded-lg border-2 border-blue-600 hover:bg-blue-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Your First Vehicle
                </a>
            @endif
        </div>
    @endif
</div>

<!-- Vehicles Grid -->
<div id="vehiclesContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
    @forelse($vehicles as $vehicle)
        <div class="bg-white border-2 border-gray-200 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group" 
             data-vehicle-id="{{ $vehicle->id }}" 
             data-brand="{{ $vehicle->brand }}" 
             data-model="{{ $vehicle->model }}" 
             data-price="{{ number_format($vehicle->price) }}"
             data-seller-id="{{ $vehicle->seller_id ?? '' }}"
             data-seller-name="{{ $vehicle->seller?->name ?? '' }}">
            
            <!-- Vehicle Image with Carousel -->
            <div class="relative h-48 bg-gray-100 overflow-hidden carousel-container" data-carousel-id="carousel-{{ $vehicle->id }}">
                @if($vehicle->images && count($vehicle->images) > 0)
                    @foreach($vehicle->images as $index => $image)
                        <img src="{{ asset('storage/' . $image) }}" 
                             alt="{{ $vehicle->brand }} {{ $vehicle->model }}" 
                             class="carousel-slide absolute w-full h-full object-cover transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                             data-slide-index="{{ $index }}">
                    @endforeach
                    
                    <!-- Carousel Navigation -->
                    @if(count($vehicle->images) > 1)
                        <button onclick="prevImage('carousel-{{ $vehicle->id }}')" 
                                class="absolute left-2 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full flex items-center justify-center shadow-lg transition">
                            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        
                        <button onclick="nextImage('carousel-{{ $vehicle->id }}')" 
                                class="absolute right-2 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full flex items-center justify-center shadow-lg transition">
                            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    @endif
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-3 left-3 z-10">
                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full shadow-lg
                        {{ $vehicle->status === 'available' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $vehicle->status === 'reserved' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $vehicle->status === 'sold' ? 'bg-gray-100 text-gray-700' : '' }}">
                        {{ ucfirst($vehicle->status) }}
                    </span>
                </div>
                
                <!-- Image Counter -->
                @if($vehicle->images && count($vehicle->images) > 0)
                    <div class="absolute top-3 right-3 z-10">
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-black/50 text-white backdrop-blur-sm">
                            📷 {{ count($vehicle->images) }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- Vehicle Info -->
            <div class="p-4">
                <div class="mb-3">
                    <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $vehicle->brand }} {{ $vehicle->model }}</h4>
                    <p class="text-sm text-gray-600">{{ $vehicle->year }} • {{ $vehicle->fuel_type ?? 'N/A' }}</p>
                </div>
                
                <!-- Key Features -->
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div class="flex items-center gap-1 text-xs text-gray-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $vehicle->transmission ?? 'Manual' }}
                    </div>
                    <div class="flex items-center gap-1 text-xs text-gray-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        {{ $vehicle->mileage ?? 'N/A' }} km
                    </div>
                    <div class="flex items-center gap-1 text-xs text-gray-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        {{ $vehicle->color ?? 'N/A' }}
                    </div>
                    <div class="flex items-center gap-1 text-xs text-gray-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        {{ $vehicle->engine_capacity ?? 'N/A' }} cc
                    </div>
                </div>
                
                <!-- Seller Request Status (for admin) -->
                @if(auth()->check() && auth()->user()->hasRole('admin') && $vehicle->sellerRequest)
                    <div class="mb-3 p-2 rounded-lg border 
                        {{ $vehicle->sellerRequest->status === 'pending' ? 'bg-yellow-50 border-yellow-300' : '' }}
                        {{ $vehicle->sellerRequest->status === 'approved' ? 'bg-green-50 border-green-300' : '' }}
                        {{ $vehicle->sellerRequest->status === 'rejected' ? 'bg-red-50 border-red-300' : '' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold 
                                    {{ $vehicle->sellerRequest->status === 'pending' ? 'text-yellow-800' : '' }}
                                    {{ $vehicle->sellerRequest->status === 'approved' ? 'text-green-800' : '' }}
                                    {{ $vehicle->sellerRequest->status === 'rejected' ? 'text-red-800' : '' }}">
                                    Request: {{ ucfirst($vehicle->sellerRequest->status) }}
                                </span>
                            </div>
                            @if($vehicle->sellerRequest->status === 'pending')
                                <div class="flex gap-1">
                                    <form method="POST" action="{{ route('admin.seller-requests.approve', $vehicle->sellerRequest) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition"
                                                onclick="return confirm('Approve seller request for {{ $vehicle->sellerRequest->user?->name ?? 'Unknown User' }}?')">
                                            ✓
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.seller-requests.reject', $vehicle->sellerRequest) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition"
                                                onclick="return confirm('Reject seller request for {{ $vehicle->sellerRequest->user?->name ?? 'Unknown User' }}?')">
                                            ✗
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                <!-- Price and Seller Info -->
                <div class="border-t pt-3">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-xl font-bold text-green-600">Nu. {{ number_format($vehicle->price) }}</p>
                            <p class="text-xs text-gray-500">{{ $vehicle->condition ?? 'Good Condition' }}</p>
                        </div>
                        <div class="text-right">
                            @if($vehicle->seller)
                                <p class="text-xs text-gray-600">{{ $vehicle->seller->name }}</p>
                                <p class="text-xs text-gray-500">{{ $vehicle->seller->phone ?? 'N/A' }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="openVehicleModal({{ $vehicle->id }})" 
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition text-center text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View Details
                        </button>
                        
                        @auth
                            <button onclick="openOfferModal({{ $vehicle->id }})" 
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition text-sm">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Make Offer
                            </button>
                        @else
                            <a href="{{ route('login') }}" 
                               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition text-center text-sm">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Make Offer
                            </a>
                        @endauth
                    </div>
                    
                    <!-- Admin/Owner Actions -->
                    @if(auth()->check() && (auth()->user()->hasRole(['admin','broker']) || (auth()->user()->hasRole('seller') && $vehicle->seller && $vehicle->seller->user_id === auth()->id()) || (auth()->user()->hasRole('buyer') && $vehicle->created_by === auth()->id())))
                        <div class="flex gap-2 mt-2">
                            <a href="{{ route('vehicles.show', $vehicle) }}" class="flex-1 px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs transition text-center">
                                View
                            </a>
                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="flex-1 px-3 py-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold text-xs transition text-center">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Are you sure you want to delete this vehicle?')" class="flex-1">
                                @csrf @method('DELETE')
                                <button class="w-full px-3 py-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-bold text-xs transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <!-- Empty state handled above -->
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-12 mb-8">
    {{ $vehicles->links() }}
</div>

<!-- Vehicle Detail Modal -->
@include('vehicles.partials.vehicle-detail-modal')

<!-- Offer Modal -->
@include('vehicles.partials.offer-modal')

<!-- JavaScript -->
<script>
// View Mode Management
function setViewMode(mode) {
    const container = document.getElementById('vehiclesContainer');
    if (mode === 'list') {
        container.className = 'space-y-4 mb-8';
        // Convert cards to list view (implementation needed)
    } else {
        container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8';
    }
}

// Carousel Navigation
function nextImage(carouselId) {
    const container = document.querySelector(`[data-carousel-id="${carouselId}"]`);
    const slides = container.querySelectorAll('.carousel-slide');
    const currentSlide = container.querySelector('.carousel-slide.opacity-100');
    const currentIndex = Array.from(slides).indexOf(currentSlide);
    const nextIndex = (currentIndex + 1) % slides.length;
    
    currentSlide.classList.remove('opacity-100');
    currentSlide.classList.add('opacity-0');
    
    slides[nextIndex].classList.remove('opacity-0');
    slides[nextIndex].classList.add('opacity-100');
}

function prevImage(carouselId) {
    const container = document.querySelector(`[data-carousel-id="${carouselId}"]`);
    const slides = container.querySelectorAll('.carousel-slide');
    const currentSlide = container.querySelector('.carousel-slide.opacity-100');
    const currentIndex = Array.from(slides).indexOf(currentSlide);
    const prevIndex = (currentIndex - 1 + slides.length) % slides.length;
    
    currentSlide.classList.remove('opacity-100');
    currentSlide.classList.add('opacity-0');
    
    slides[prevIndex].classList.remove('opacity-0');
    slides[prevIndex].classList.add('opacity-100');
}

// Vehicle Detail Modal
function openVehicleModal(vehicleId) {
    const modal = document.getElementById('vehicleDetailModal');
    const loadingDiv = document.querySelector('#vehicleDetailModal .text-center');
    const contentDiv = document.querySelector('#vehicleDetailModal .p-6');
    
    document.getElementById('vehicleDetailId').value = vehicleId;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Show loading state
    loadingDiv.classList.remove('hidden');
    contentDiv.classList.add('hidden');
    
    // Fetch vehicle details
    fetch(`/api/vehicles/${vehicleId}`)
        .then(response => response.json())
        .then(data => {
            displayVehicleDetails(data);
            loadingDiv.classList.add('hidden');
            contentDiv.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            loadingDiv.innerHTML = '<p class="text-red-600">Error loading vehicle details</p>';
        });
}

function displayVehicleDetails(vehicle) {
    // Update modal content with vehicle details
    const modalContent = document.querySelector('#vehicleDetailModal .p-6');
    
    modalContent.innerHTML = `
        <!-- Vehicle Images Carousel -->
        <div class="mb-6">
            <div class="relative h-96 bg-gray-100 rounded-xl overflow-hidden" id="detailCarousel">
                ${vehicle.images && vehicle.images.length > 0 ? 
                    vehicle.images.map((img, index) => `
                        <img src="/storage/${img}" alt="${vehicle.brand} ${vehicle.model}" 
                             class="carousel-slide absolute w-full h-full object-cover transition-opacity duration-500 ${index === 0 ? 'opacity-100' : 'opacity-0'}"
                             data-slide-index="${index}">
                    `).join('') + 
                    (vehicle.images.length > 1 ? `
                        <button onclick="changeCarouselImage(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full flex items-center justify-center shadow-lg transition">
                            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button onclick="changeCarouselImage(1)" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full flex items-center justify-center shadow-lg transition">
                            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    ` : '') : 
                    '<div class="w-full h-full bg-gray-300 flex items-center justify-center"><span class="text-gray-600 text-xl font-semibold">No Image Available</span></div>'
                }
            </div>
        </div>

        <!-- Vehicle Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Left Column - Basic Info -->
            <div>
                <h4 class="text-2xl font-bold text-gray-900 mb-4">${vehicle.brand} ${vehicle.model}</h4>
                
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Year:</span>
                        <span class="font-semibold">${vehicle.year || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Fuel Type:</span>
                        <span class="font-semibold">${vehicle.fuel_type || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Transmission:</span>
                        <span class="font-semibold">${vehicle.transmission || 'Manual'}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Mileage:</span>
                        <span class="font-semibold">${vehicle.mileage ? vehicle.mileage.toLocaleString() + ' km' : 'N/A'}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Color:</span>
                        <span class="font-semibold">${vehicle.color || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Engine:</span>
                        <span class="font-semibold">${vehicle.engine_capacity ? vehicle.engine_capacity + ' cc' : 'N/A'}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">Condition:</span>
                        <span class="font-semibold">${vehicle.condition || 'Good'}</span>
                    </div>
                </div>
            </div>

            <!-- Right Column - Price & Actions -->
            <div>
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl p-6 mb-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Price Information</h4>
                    <div class="text-3xl font-bold text-green-600 mb-2">Nu. ${vehicle.price.toLocaleString()}</div>
                    <p class="text-sm text-gray-600">Negotiable • All inclusive</p>
                </div>

                <!-- Seller Information -->
                <div class="bg-white border-2 border-gray-200 rounded-xl p-6 mb-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-3">Seller Information</h4>
                    <div class="space-y-2">
                        <p class="font-semibold">${vehicle.seller?.name || 'Unknown Seller'}</p>
                        <p class="text-sm text-gray-600">${vehicle.seller?.phone || '+975 XXXX XXXX'}</p>
                        <p class="text-sm text-gray-600">${vehicle.seller?.email || 'seller@cbs.bt'}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <a href="/bookings/create/${vehicle.id}" class="w-full px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl transition text-center">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Book Test Drive
                    </a>
                    
                    <button onclick="startChat()" class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Send Message to Seller
                    </button>
                </div>
            </div>
        </div>

        <!-- Chat Section -->
        <div class="mt-8 border-t pt-6">
            <h4 class="text-xl font-bold text-gray-900 mb-4">💬 Live Chat with Seller</h4>
            
            <!-- Chat Messages -->
            <div id="chatMessages" class="bg-gray-50 rounded-lg p-4 h-64 overflow-y-auto mb-4 border border-gray-200">
                <div class="text-center text-gray-500 py-8">
                    <p class="text-sm">Start a conversation with seller...</p>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="flex gap-2">
                <input type="text" id="chatMessageInput" placeholder="Type your message..." 
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       onkeypress="if(event.key === 'Enter') sendChatMessage()">
                <button onclick="sendChatMessage()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    Send
                </button>
            </div>
        </div>
    `;
    
    // Update seller ID for chat
    document.getElementById('detailSellerId').value = vehicle.seller?.id || '';
}

let currentSlideIndex = 0;

function changeCarouselImage(direction) {
    const carousel = document.getElementById('detailCarousel');
    const images = carousel.querySelectorAll('img');
    
    if (images.length === 0) return;
    
    currentSlideIndex += direction;
    if (currentSlideIndex < 0) currentSlideIndex = images.length - 1;
    if (currentSlideIndex >= images.length) currentSlideIndex = 0;
    
    images.forEach((img, index) => {
        if (index === currentSlideIndex) {
            img.classList.remove('opacity-0');
            img.classList.add('opacity-100');
        } else {
            img.classList.remove('opacity-100');
            img.classList.add('opacity-0');
        }
    });
}

function startChat() {
    const chatSection = document.querySelector('#vehicleDetailModal .mt-8');
    if (chatSection) {
        chatSection.scrollIntoView({ behavior: 'smooth' });
    }
    document.getElementById('chatMessageInput').focus();
}

function sendChatMessage() {
    const message = document.getElementById('chatMessageInput').value.trim();
    if (!message) return;
    
    const vehicleId = document.getElementById('vehicleDetailId').value;
    const sellerId = document.getElementById('detailSellerId').value;
    
    fetch('/chat/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            recipient_id: sellerId,
            message: message,
            vehicle_id: vehicleId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('chatMessageInput').value = '';
            addChatMessage(message, true);
        }
    })
    .catch(error => console.error('Error:', error));
}

function addChatMessage(message, isOwn = false) {
    const chatMessages = document.getElementById('chatMessages');
    const msgDiv = document.createElement('div');
    msgDiv.className = `mb-3 flex ${isOwn ? 'justify-end' : 'justify-start'}`;
    msgDiv.innerHTML = `
        <div class="bg-${isOwn ? 'blue' : 'gray'}-600 text-white rounded-lg px-4 py-2 max-w-xs">
            <p class="text-sm">${message}</p>
        </div>
    `;
    chatMessages.appendChild(msgDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function closeVehicleModal() {
    const modal = document.getElementById('vehicleDetailModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Offer Modal
function openOfferModal(vehicleId) {
    const modal = document.getElementById('offerModal');
    const vehicleInfo = document.getElementById('offerVehicleInfo');
    const vehicleIdInput = document.getElementById('offerVehicleId');
    
    // Get vehicle data from card
    const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
    const brand = vehicleCard.dataset.brand;
    const model = vehicleCard.dataset.model;
    const price = vehicleCard.dataset.price;
    
    vehicleInfo.textContent = `${brand} ${model} - Nu. ${price}`;
    vehicleIdInput.value = vehicleId;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeOfferModal() {
    const modal = document.getElementById('offerModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Brand input suggestions (images + names)
(function() {
    const input = document.getElementById('brandInput');
    const suggestionsBox = document.getElementById('brandSuggestions');
    if (!input || !suggestionsBox) return;

    let debounceTimer = null;

    function clearSuggestions() {
        suggestionsBox.innerHTML = '';
        suggestionsBox.classList.add('hidden');
    }

    function renderSuggestions(items) {
        if (!items || items.length === 0) {
            clearSuggestions();
            return;
        }

        suggestionsBox.innerHTML = items.map(item => `
            <button type="button" class="w-full text-left px-3 py-2 hover:bg-gray-50 flex items-center gap-3 border-b last:border-b-0" data-brand="${item.brand}">
                <img src="${item.image || '/images/placeholder.jpg'}" alt="${item.brand}" class="w-12 h-8 object-cover rounded-md flex-shrink-0">
                <div class="truncate">
                    <div class="text-sm font-medium text-gray-900">${item.brand}</div>
                </div>
            </button>
        `).join('');

        suggestionsBox.classList.remove('hidden');

        // Attach click handlers
        suggestionsBox.querySelectorAll('button[data-brand]').forEach(btn => {
            btn.addEventListener('click', function() {
                const brand = this.dataset.brand;
                input.value = brand;
                clearSuggestions();
            });
        });
    }

    function fetchSuggestions(q) {
        if (!q || q.trim().length === 0) {
            clearSuggestions();
            return;
        }

        fetch(`/api/brands/suggest?q=${encodeURIComponent(q)}`)
            .then(r => r.json())
            .then(data => {
                renderSuggestions(Array.isArray(data) ? data : []);
            })
            .catch(() => {
                clearSuggestions();
            });
    }

    input.addEventListener('input', function() {
        const q = this.value;
        if (debounceTimer) clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchSuggestions(q), 250);
    });

    // Close suggestions on outside click
    document.addEventListener('click', function(e) {
        if (!suggestionsBox.contains(e.target) && e.target !== input) {
            clearSuggestions();
        }
    });

    // Close on escape
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') clearSuggestions();
    });
})();

// Keyboard shortcuts
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeVehicleModal();
        closeOfferModal();
    }
});
</script>

@endsection
