@extends('layouts.app')

@section('title', 'Browse Vehicles')
@section('subtitle', 'Find your perfect vehicle')

@section('content')
<!-- Search & Filter Section -->
<div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl p-8 text-white shadow-2xl mb-8">
    <h1 class="text-4xl font-bold mb-2">Browse Our Vehicles</h1>
    <p class="text-blue-100 text-lg mb-6">Discover our collection of available vehicles for test drives and purchase</p>
    
    <form method="GET" action="{{ route('vehicles.browse') }}" class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <!-- Brand Filter -->
        <div>
            <label class="block text-sm font-medium text-blue-100 mb-2">Brand</label>
            <input type="text" name="brand" value="{{ request('brand') }}" 
                   class="w-full px-4 py-3 rounded-lg bg-white text-gray-900 shadow-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                   placeholder="e.g. Toyota, BMW">
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
            <label class="block text-sm font-medium text-blue-100 mb-2">Min Price (Nu.)</label>
            <input type="number" name="min_price" value="{{ request('min_price') }}" 
                   class="w-full px-4 py-3 rounded-lg bg-white text-gray-900 shadow-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                   placeholder="0">
        </div>

        <!-- Max Price Filter -->
        <div>
            <label class="block text-sm font-medium text-blue-100 mb-2">Max Price (Nu.)</label>
            <input type="number" name="max_price" value="{{ request('max_price') }}" 
                   class="w-full px-4 py-3 rounded-lg bg-white text-gray-900 shadow-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                   placeholder="999999">
        </div>

        <!-- Search Button -->
        <div class="flex items-end gap-2">
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

<!-- Vehicles Grid -->
<div class="mb-8">
    @if($vehicles->count() > 0)
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-3xl font-bold text-gray-900">Available Vehicles</h2>
            <span class="text-lg font-semibold text-gray-600 bg-blue-50 px-4 py-2 rounded-full">
                {{ $vehicles->total() }} vehicles
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($vehicles as $vehicle)
                <div class="flex flex-col bg-white border-2 border-gray-200 rounded-2xl shadow-sm hover:shadow-xl hover:border-blue-300 transition-all duration-300 overflow-hidden group h-full" 
                     data-vehicle-id="{{ $vehicle->id }}" 
                     data-brand="{{ $vehicle->brand }}" 
                     data-model="{{ $vehicle->model }}" 
                     data-price="{{ number_format($vehicle->price) }}"
                     data-seller-id="{{ $vehicle->seller_id ?? '' }}"
                     data-seller-name="{{ $vehicle->seller->name ?? '' }}">
                    <!-- Vehicle Image with Carousel -->
                    <div class="relative h-56 md:h-64 lg:h-72 bg-gray-100 overflow-hidden carousel-container" data-carousel-id="carousel-{{ $vehicle->id }}">
                        @if($vehicle->images && count($vehicle->images) > 0)
                            @foreach($vehicle->images as $index => $image)
                                <img src="{{ asset('storage/' . $image) }}" 
                                     alt="{{ $vehicle->brand }} {{ $vehicle->model }}" 
                                     loading="lazy" decoding="async"
                                     class="carousel-slide absolute inset-0 w-full h-full object-cover object-center transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                                     data-slide-index="{{ $index }}">
                            @endforeach
                            
                            <!-- Carousel Navigation -->
                            @if(count($vehicle->images) > 1)
                                <!-- Previous Button -->
                                <button onclick="prevImage('carousel-{{ $vehicle->id }}')" 
                                        class="absolute left-2 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full flex items-center justify-center shadow-lg transition">
                                    <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                
                                <!-- Next Button -->
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
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $vehicle->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($vehicle->status) }}
                            </span>
                        </div>
                        
                        <!-- Close/Expand Button -->
                        <div class="absolute top-3 right-3 z-10 flex gap-2">
                            <button onclick="openVehicleModal({{ $vehicle->id }})" 
                                    class="w-8 h-8 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full flex items-center justify-center shadow-lg transition">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4m-4-4l8-8m0 0l-4 4m4-4v12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                        <!-- Vehicle Info -->
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div class="mb-3">
                                <h4 class="text-lg font-bold text-gray-900 mb-1 truncate">{{ $vehicle->brand }} {{ $vehicle->model }}</h4>
                                <p class="text-sm text-gray-600 truncate">{{ $vehicle->year }} • {{ $vehicle->fuel_type }}</p>
                            </div>

                            <!-- Key Features -->
                            <div class="grid grid-cols-2 gap-2 mb-3 text-xs text-gray-600">
                                <div class="flex items-center gap-2" title="Transmission: {{ $vehicle->transmission ?? 'Manual' }}">
                                    <!-- Gear icon -->
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l2 2m4-6l1.5-2.598L19 4l-1.2.6A7.966 7.966 0 0012 4a7.966 7.966 0 00-5.8 1.6L5 4 3.5 4.402 5 7l1 .5A7.966 7.966 0 006 12c0 .68.083 1.344.238 1.976L4 15l1.5 2.598L7 20l1.2-.6A7.966 7.966 0 0012 20a7.966 7.966 0 005.8-1.6L19 20l1.5-1.402L19 17l-1-.5c.155-.632.238-1.296.238-1.976 0-1.1-.18-2.156-.512-3.12L20 10z"/>
                                    </svg>
                                    <span class="truncate">{{ $vehicle->transmission ?? 'Manual' }}</span>
                                </div>

                                <div class="flex items-center gap-2" title="Mileage: {{ $vehicle->mileage ?? 'N/A' }} km">
                                    <!-- Speedometer icon -->
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.79-4 4m8 0a4 4 0 00-4-4M3 20h18"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7l-4 4-4-4"/>
                                    </svg>
                                    <span>{{ $vehicle->mileage ? number_format($vehicle->mileage) . ' km' : 'N/A' }}</span>
                                </div>

                                <div class="flex items-center gap-2" title="Color: {{ $vehicle->color ?? 'N/A' }}">
                                    <!-- Palette / droplet icon -->
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3C8 3 5 6 5 10a7 7 0 0014 0c0-4-3-7-7-7z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10"/>
                                    </svg>
                                    <span class="truncate">{{ $vehicle->color ?? 'N/A' }}</span>
                                </div>

                                <div class="flex items-center gap-2" title="Engine: {{ $vehicle->engine_capacity ?? 'N/A' }} cc">
                                    <!-- Engine icon -->
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13v-2a2 2 0 012-2h1V8a2 2 0 012-2h6a2 2 0 012 2v1h1a2 2 0 012 2v2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21v-4h10v4"/>
                                    </svg>
                                    <span>{{ $vehicle->engine_capacity ? $vehicle->engine_capacity . ' cc' : 'N/A' }}</span>
                                </div>
                            </div>
                        
                        <!-- Price and Actions -->
                        <div class="border-t pt-3">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-2xl font-bold text-blue-600">Nu. {{ number_format($vehicle->price) }}</p>
                                    <p class="text-xs text-gray-500">{{ $vehicle->condition ?? 'Good Condition' }}</p>
                                </div>
                                <div class="text-right">
                                    @if($vehicle->seller)
                                        <p class="text-xs text-gray-600">Seller: {{ $vehicle->seller->name }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('vehicles.show', $vehicle) }}" 
                                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition text-center text-sm">
                                    View
                                </a>
                                
                                @auth
                                    <button onclick="openOfferModal({{ $vehicle->id }})" 
                                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition text-sm">
                                        Make Offer
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition text-center text-sm">
                                        Make Offer
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $vehicles->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No vehicles found</h3>
            <p class="text-gray-600 mb-6">Try adjusting your search filters</p>
            <a href="{{ route('vehicles.browse') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 110-18 9 9 0 010 18zM12 8v4m0 4h.01"/>
                </svg>
                Clear Filters
            </a>
        </div>
    @endif
</div>

<!-- Vehicle Detail Modal -->
<div id="vehicleDetailModal" class="hidden fixed inset-0 z-50 flex">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeVehicleModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b flex items-center justify-between p-6 rounded-t-2xl z-10">
                <h3 class="text-2xl font-bold text-gray-900">Vehicle Details</h3>
                <button onclick="closeVehicleModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <input type="hidden" id="vehicleDetailId">
                <input type="hidden" id="detailSellerId">

                <!-- Coming Soon Message -->
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-600 text-lg mb-6">Full vehicle details loading...</p>
                </div>

                <!-- Chat Section -->
                <div class="mt-8 border-t pt-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">💬 Send Message to Seller</h4>
                    
                    <!-- Chat Messages -->
                    <div id="chatMessages" class="bg-gray-50 rounded-lg p-4 h-64 overflow-y-auto mb-4 border border-gray-200">
                        <div class="text-center text-gray-500 py-8">
                            <p class="text-sm">No messages yet. Start a conversation!</p>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="flex gap-2">
                        <input type="text" id="chatMessage" placeholder="Type your message..." 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               onkeypress="if(event.key === 'Enter') sendChatMessage()">
                        <button onclick="sendChatMessage()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                            Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Offer Modal -->
<div id="offerModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeOfferModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Make an Offer</h3>
                    <button onclick="closeOfferModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form id="offerForm" method="POST" action="{{ route('offers.store') }}">
                    @csrf
                    <input type="hidden" name="vehicle_id" id="offerVehicleId">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle</label>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p id="offerVehicleInfo" class="font-medium text-gray-900"></p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Offer Amount (Nu.)</label>
                        <input type="number" name="offer_amount" required min="0" step="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message to Seller (Optional)</label>
                        <textarea name="message" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Add any questions or special requests..."></textarea>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                            Submit Offer
                        </button>
                        <button type="button" onclick="closeOfferModal()" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
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

// Send Chat Message
function sendChatMessage() {
    const vehicleId = document.getElementById('vehicleDetailId').value;
    const sellerId = document.getElementById('detailSellerId').value;
    const message = document.getElementById('chatMessage').value.trim();
    
    if (!message) {
        alert('Please enter a message');
        return;
    }
    
    // Send via AJAX or form
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
            document.getElementById('chatMessage').value = '';
            document.getElementById('chatMessages').innerHTML += `
                <div class="mb-3 flex justify-end">
                    <div class="bg-blue-600 text-white rounded-lg px-4 py-2 max-w-xs">
                        <p class="text-sm">${message}</p>
                    </div>
                </div>
            `;
        }
    })
    .catch(error => console.error('Error:', error));
}

function openOfferModal(vehicleId) {
    const modal = document.getElementById('offerModal');
    const vehicleInfo = document.getElementById('offerVehicleInfo');
    const vehicleIdInput = document.getElementById('offerVehicleId');
    
    // Get vehicle data from the card
    const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
    if (vehicleCard) {
        const brand = vehicleCard.dataset.brand;
        const model = vehicleCard.dataset.model;
        const price = vehicleCard.dataset.price;
        
        vehicleInfo.textContent = `${brand} ${model} - Nu. ${price}`;
    }
    
    vehicleIdInput.value = vehicleId;
    modal.classList.remove('hidden');
}

function closeOfferModal() {
    const modal = document.getElementById('offerModal');
    modal.classList.add('hidden');
    document.getElementById('offerForm').reset();
}

// Allow Enter key to close modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeVehicleModal();
        closeOfferModal();
    }
});
</script>
@endsection
