@extends('layouts.app')

@section('title', $vehicle->brand . ' ' . $vehicle->model)
@section('subtitle', 'Vehicle Details')

@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('vehicles.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        ← Back to Vehicles
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Image Gallery - Enhanced with Bhutanese Colors -->
        <div class="group overflow-hidden rounded-2xl shadow-2xl border border-gray-100">
            @if($vehicle->images && count($vehicle->images) > 0)
                <!-- Main Image with Premium Styling -->
                <div class="relative h-96 lg:h-[520px] bg-gradient-to-br from-gray-900 via-gray-800 to-black overflow-hidden">
                    <img id="mainImage" 
                         src="{{ asset('storage/' . $vehicle->images[0]) }}" 
                         alt="{{ $vehicle->brand }} {{ $vehicle->model }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out"
                         loading="eager"
                         decoding="async">
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <!-- Status Badge - Premium Styling with Bhutanese Colors -->
                    <div class="absolute top-6 right-6 z-10">
                        @if($vehicle->status === 'available')
                            <span class="inline-flex items-center gap-2 px-5 py-3 rounded-full text-sm font-bold shadow-2xl bg-gradient-to-r from-emerald-500 via-lime-400 to-emerald-600 text-white backdrop-blur-sm border border-emerald-300/50">
                                <svg class="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Available Now
                            </span>
                        @elseif($vehicle->status === 'reserved')
                            <span class="inline-flex items-center gap-2 px-5 py-3 rounded-full text-sm font-bold shadow-2xl bg-gradient-to-r from-orange-500 via-amber-400 to-orange-600 text-white backdrop-blur-sm border border-orange-300/50">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v4h8v-4zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" clip-rule="evenodd"/>
                                </svg>
                                Reserved
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-5 py-3 rounded-full text-sm font-bold shadow-2xl bg-gradient-to-r from-red-500 via-rose-400 to-red-600 text-white backdrop-blur-sm border border-red-300/50">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 0 1 5.11 6.524A6 6 0 1 0 13.476 14.89ZM16.657 16.657l2.828 2.828M9 11a2 2 0 1 1 4 0 2 2 0 0 1-4 0Z" clip-rule="evenodd"/>
                                </svg>
                                Sold
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Thumbnail Gallery - Enhanced -->
                @if(count($vehicle->images) > 1)
                    <div class="p-5 lg:p-6 bg-gradient-to-r from-gray-50 via-emerald-50/30 to-gray-50 border-t border-gray-200/50">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-xs font-bold text-gray-700 uppercase tracking-widest">📸 Gallery Preview ({{ count($vehicle->images) }} photos)</p>
                        </div>
                        <div class="flex gap-3 overflow-x-auto pb-2 -mx-2 px-2">
                            @foreach($vehicle->images as $index => $image)
                                <button onclick="document.getElementById('mainImage').src = '{{ asset('storage/' . $image) }}'; this.classList.add('ring-2', 'ring-offset-2', 'ring-emerald-500'); Array.from(this.parentElement.children).forEach(el => el !== this && el.classList.remove('ring-2', 'ring-offset-2', 'ring-emerald-500'));"
                                        class="relative min-w-24 h-24 lg:min-w-28 lg:h-28 flex-shrink-0 object-cover rounded-xl shadow-md hover:shadow-xl transition-all overflow-hidden group {{ $index === 0 ? 'ring-2 ring-offset-2 ring-emerald-500' : '' }}">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         alt="Gallery image {{ $index + 1 }}" 
                                         class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-300"
                                         loading="lazy"
                                         decoding="async">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300"></div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div class="h-96 lg:h-[520px] bg-gradient-to-br from-gray-100 via-emerald-50 to-gray-100 flex items-center justify-center relative overflow-hidden">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-50 mb-4">
                            <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-gray-700 font-bold text-lg">No images available</p>
                        <p class="text-gray-500 text-sm mt-2">Images will appear here when uploaded</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Vehicle Details - Enhanced Specs -->
        <div class="bg-gradient-to-br from-white via-emerald-50/30 to-white rounded-2xl shadow-xl border border-gray-200/50 p-6 lg:p-8">
            <div class="flex items-center gap-3 mb-8 pb-6 border-b-2 border-emerald-200">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-500 to-lime-500 flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-emerald-700 via-lime-600 to-emerald-700 bg-clip-text text-transparent">Vehicle Specifications</h2>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6">
                <!-- Brand Card -->
                <div class="group relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-emerald-50 to-emerald-100/50 border-2 border-emerald-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-200/20 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500"></div>
                    <label class="text-xs font-bold text-emerald-700 uppercase tracking-widest">🚗 Brand</label>
                    <p class="text-2xl font-bold text-emerald-900 mt-2">{{ $vehicle->brand }}</p>
                </div>
                
                <!-- Model Card -->
                <div class="group relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-lime-50 to-lime-100/50 border-2 border-lime-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-lime-200/20 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500"></div>
                    <label class="text-xs font-bold text-lime-700 uppercase tracking-widest">🏷️ Model</label>
                    <p class="text-2xl font-bold text-lime-900 mt-2">{{ $vehicle->model }}</p>
                </div>
                
                <!-- Year Card -->
                <div class="group relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-orange-50 to-orange-100/50 border-2 border-orange-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-orange-200/20 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500"></div>
                    <label class="text-xs font-bold text-orange-700 uppercase tracking-widest">📅 Year</label>
                    <p class="text-2xl font-bold text-orange-900 mt-2">{{ $vehicle->year }}</p>
                </div>

                <!-- Mileage Card -->
                <div class="group relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-red-50 to-red-100/50 border-2 border-red-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-red-200/20 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500"></div>
                    <label class="text-xs font-bold text-red-700 uppercase tracking-widest">⚙️ Mileage</label>
                    <p class="text-2xl font-bold text-red-900 mt-2">{{ number_format($vehicle->mileage) }} <span class="text-sm text-red-700 font-semibold">km</span></p>
                </div>
                
                <!-- Price Card - Premium -->
                <div class="group relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-emerald-100 via-lime-50 to-emerald-100 border-2 border-emerald-400 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 lg:col-span-1">
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500 rounded-full -mr-16 -mt-16 group-hover:scale-200 transition-transform duration-500"></div>
                    </div>
                    <label class="text-xs font-bold text-emerald-700 uppercase tracking-widest relative z-10">💰 Price</label>
                    <p class="text-3xl font-bold bg-gradient-to-r from-emerald-900 to-lime-700 bg-clip-text text-transparent mt-3 relative z-10">Nu. {{ number_format($vehicle->price, 0) }}</p>
                </div>

                <!-- Status Card -->
                <div class="group relative overflow-hidden rounded-xl p-5 bg-gradient-to-br from-purple-50 to-purple-100/50 border-2 border-purple-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-purple-200/20 rounded-full -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-500"></div>
                    <label class="text-xs font-bold text-purple-700 uppercase tracking-widest">📌 Status</label>
                    <p class="mt-3">
                        @if($vehicle->status === 'available')
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold bg-gradient-to-r from-emerald-500 to-lime-500 text-white shadow-lg">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Available
                            </span>
                        @elseif($vehicle->status === 'reserved')
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-lg">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 11a1 1 0 110-2h.01a1 1 0 110 2H5zM9 11a1 1 0 110-2h.01a1 1 0 110 2H9zm4 0a1 1 0 110-2h.01a1 1 0 110 2h-.01z"/>
                                </svg>
                                Reserved
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                Sold
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Description Section -->
            @if($vehicle->description)
                <div class="mt-8 pt-8 border-t-2 border-emerald-200/50">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-lg">📝</span>
                        <label class="text-sm font-bold text-gray-700 uppercase tracking-wide">Vehicle Description</label>
                    </div>
                    <p class="text-gray-700 leading-relaxed bg-gradient-to-br from-emerald-50/50 to-transparent p-5 rounded-xl border border-emerald-100/50">{{ $vehicle->description }}</p>
                </div>
            @endif
        </div>

        <!-- Booking Actions Sidebar - Enhanced with Bhutanese Colors -->
        @if($vehicle->status === 'available')
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-emerald-50 via-white to-lime-50 rounded-2xl shadow-2xl border-2 border-emerald-200 p-6 lg:p-8 sticky top-24 space-y-4">
                    <!-- Price Preview Card -->
                    <div class="bg-gradient-to-br from-emerald-100 to-lime-100 rounded-xl p-6 text-center border-2 border-emerald-300 shadow-lg">
                        <p class="text-sm text-emerald-700 font-bold uppercase tracking-wider mb-2">💰 Listed Price</p>
                        <p class="text-4xl lg:text-5xl font-bold bg-gradient-to-r from-emerald-700 via-lime-600 to-emerald-700 bg-clip-text text-transparent">Nu. {{ number_format($vehicle->price) }}</p>
                        <p class="text-xs text-emerald-600 mt-2 font-semibold">Bhutanese Ngultrum</p>
                    </div>

                    <!-- Test Drive Button - Bhutanese Orange -->
                    @auth
                        <a href="{{ route('bookings.create', $vehicle) }}" 
                           class="w-full group relative overflow-hidden px-6 py-4 bg-gradient-to-r from-orange-500 via-orange-600 to-red-500 hover:from-orange-600 hover:via-orange-700 hover:to-red-600 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all text-center flex items-center justify-center gap-2">
                            <div class="absolute inset-0 bg-white/20 translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                            <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <span class="relative z-10">Book Test Drive</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="w-full group relative overflow-hidden px-6 py-4 bg-gradient-to-r from-orange-500 via-orange-600 to-red-500 hover:from-orange-600 hover:via-orange-700 hover:to-red-600 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all text-center flex items-center justify-center gap-2">
                            <div class="absolute inset-0 bg-white/20 translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                            <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <span class="relative z-10">Login to Book</span>
                        </a>
                    @endauth

                    <!-- Send Inquiry Button - Bhutanese Green -->
                    @auth
                        <a href="{{ route('inquiries.create') }}?vehicle_id={{ $vehicle->id }}" 
                           class="w-full group relative overflow-hidden px-6 py-4 bg-gradient-to-r from-emerald-600 via-lime-600 to-emerald-600 hover:from-emerald-700 hover:via-lime-700 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all text-center flex items-center justify-center gap-2">
                            <div class="absolute inset-0 bg-white/20 translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                            <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M9 16H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2h-4l-4 4v-4z"/>
                            </svg>
                            <span class="relative z-10">Send Inquiry</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="w-full group relative overflow-hidden px-6 py-4 bg-gradient-to-r from-emerald-600 via-lime-600 to-emerald-600 hover:from-emerald-700 hover:via-lime-700 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all text-center flex items-center justify-center gap-2">
                            <div class="absolute inset-0 bg-white/20 translate-x-full group-hover:translate-x-0 transition-transform duration-300"></div>
                            <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M9 16H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2h-4l-4 4v-4z"/>
                            </svg>
                            <span class="relative z-10">Login to Inquire</span>
                        </a>
                    @endauth

                    <!-- Contact Seller Card -->
                    @if($vehicle->seller)
                        <div class="border-t-2 border-emerald-200 pt-6 mt-6">
                            <p class="text-xs font-bold text-emerald-800 uppercase tracking-widest mb-4">👤 Seller Information</p>
                            <div class="bg-white rounded-xl p-4 mb-4 border-2 border-emerald-100 shadow-sm">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-lime-500 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper($vehicle->seller->name[0]) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $vehicle->seller->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $vehicle->seller->phone ?? 'No phone' }}</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 break-all bg-gray-50 p-2 rounded mt-2">{{ $vehicle->seller->email }}</p>
                            </div>
                            
                            @auth
                                <a href="{{ route('chat.show', $vehicle->broker_id ?? $vehicle->seller_id) }}" 
                                   class="w-full block px-5 py-3 bg-gradient-to-r from-lime-600 to-emerald-600 hover:from-lime-700 hover:to-emerald-700 text-white font-bold rounded-lg transition-all text-center text-sm shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    💬 Chat with Seller
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="w-full block px-5 py-3 bg-gradient-to-r from-lime-600 to-emerald-600 hover:from-lime-700 hover:to-emerald-700 text-white font-bold rounded-lg transition-all text-center text-sm shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    💬 Login to Chat
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Vehicle Not Available Card -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl shadow-2xl border-2 border-gray-300 p-6 lg:p-8 sticky top-24">
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center shadow-lg">
                            <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Not Available</h3>
                        <p class="text-gray-600 text-base mb-2">
                            This vehicle is currently
                        </p>
                        <span class="inline-block px-4 py-2 rounded-lg text-sm font-bold
                            {{ $vehicle->status === 'reserved' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($vehicle->status) }}
                        </span>
                        <p class="text-gray-500 text-sm mt-4">Check back later or browse other vehicles</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Seller Request Management (Admin Only) -->
        @if(auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN) && $vehicle->sellerRequest)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 lg:p-8">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b-2 border-gray-200">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h2 class="text-xl font-bold text-gray-900">Seller Request Management</h2>
                    <span class="ml-auto px-3 py-1 rounded-full text-sm font-bold
                        {{ $vehicle->sellerRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $vehicle->sellerRequest->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $vehicle->sellerRequest->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($vehicle->sellerRequest->status) }}
                    </span>
                </div>

                <!-- Request Details -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">User</label>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($vehicle->sellerRequest->user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $vehicle->sellerRequest->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $vehicle->sellerRequest->user->email }}</p>
                                    <p class="text-xs text-gray-500">Role: {{ ucfirst($vehicle->sellerRequest->user->role) }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Requested</label>
                            <p class="text-gray-900 mt-2">{{ $vehicle->sellerRequest->created_at->format('M d, Y \a\t H:i') }}</p>
                        </div>
                        @if($vehicle->sellerRequest->reviewed_at)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Reviewed</label>
                                <p class="text-gray-900 mt-2">{{ $vehicle->sellerRequest->reviewed_at->format('M d, Y \a\t H:i') }}</p>
                                @if($vehicle->sellerRequest->reviewer)
                                    <p class="text-sm text-gray-600">by {{ $vehicle->sellerRequest->reviewer->name }}</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">User Message</label>
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200 mt-2">
                                <p class="text-sm text-gray-700">{{ $vehicle->sellerRequest->user_message ?? 'No message provided' }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Admin Notes</label>
                            @if($vehicle->sellerRequest->admin_notes)
                                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200 mt-2">
                                    <p class="text-sm text-gray-700">{{ $vehicle->sellerRequest->admin_notes }}</p>
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mt-2">
                                    <p class="text-sm text-gray-400 italic">No admin notes added yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                @if($vehicle->sellerRequest->status === 'pending')
                    <div class="border-t-2 border-gray-200 pt-6">
                        <!-- Approve Form -->
                        <form method="POST" action="{{ route('admin.seller-requests.approve', $vehicle->sellerRequest) }}" 
                              onsubmit="return confirm('Are you sure you want to approve this seller request? This will upgrade the user to seller role and make their vehicle visible to all users.')">
                            @csrf
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700">Admin Notes (Optional)</label>
                                <textarea name="admin_notes" rows="3" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                          placeholder="Add notes about this approval...">{{ $vehicle->sellerRequest->admin_notes }}</textarea>
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-3 rounded-lg transition-all font-bold flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Approve Request
                                </button>
                            </div>
                        </form>

                        <!-- Reject Button -->
                        <form method="POST" action="{{ route('admin.seller-requests.reject', $vehicle->sellerRequest) }}" 
                              onsubmit="return confirm('Are you sure you want to reject this seller request? This will deny seller privileges for this user.')">
                            @csrf
                            <div class="space-y-3 mt-4">
                                <label class="block text-sm font-medium text-gray-700">Rejection Reason (Optional)</label>
                                <textarea name="admin_notes" rows="3" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                          placeholder="Please provide reason for rejection (optional)...">{{ $vehicle->sellerRequest->admin_notes }}</textarea>
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-4 py-3 rounded-lg transition-all font-bold flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Reject Request
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @endif

        <!-- Inquiries -->
        @if(auth()->user()->hasRole(['admin', 'broker']) && $vehicle->inquiries && $vehicle->inquiries->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 lg:p-8">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b-2 border-gray-200">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2h-4l-4 4v-4z"/>
                    </svg>
                    <h2 class="text-xl font-bold text-gray-900">Inquiries <span class="text-sm font-normal text-gray-600">({{ $vehicle->inquiries->count() }})</span></h2>
                </div>
                <div class="space-y-4">
                    @foreach($vehicle->inquiries as $inquiry)
                        <div class="p-5 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200 hover:shadow-md transition-all">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-900 text-base">{{ $inquiry->user_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $inquiry->user_email }}</p>
                                </div>
                                <span class="text-xs text-gray-500 font-medium bg-white px-2.5 py-1 rounded-full">{{ $inquiry->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700 text-sm leading-relaxed">{{ $inquiry->message }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Seller Information -->
        <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Seller Information</h2>
            @if($vehicle->seller)
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-slate-500">Name</label>
                        <p class="text-slate-800 font-semibold mt-1">{{ $vehicle->seller->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Email</label>
                        <p class="text-slate-700 mt-1">{{ $vehicle->seller->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Phone</label>
                        <p class="text-slate-700 mt-1">{{ $vehicle->seller->phone }}</p>
                    </div>
                    @if($vehicle->seller->address)
                        <div>
                            <label class="text-sm font-medium text-slate-500">Address</label>
                            <p class="text-slate-700 mt-1">{{ $vehicle->seller->address }}</p>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-slate-500">No seller information available</p>
            @endif
        </div>

        <!-- Broker Information -->
        @if(auth()->user()->hasRole(['admin', 'broker']) && $vehicle->broker)
            <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Listed By</h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-slate-500">Broker</label>
                        <p class="text-slate-800 font-semibold mt-1">{{ $vehicle->broker->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Email</label>
                        <p class="text-slate-700 mt-1">{{ $vehicle->broker->email }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Transaction Information -->
        @if(auth()->user()->hasRole(['admin', 'broker']) && $vehicle->transaction)
            <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Transaction Details</h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-slate-500">Buyer</label>
                        <p class="text-slate-800 font-semibold mt-1">{{ $vehicle->transaction->buyer->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Sale Price</label>
                        <p class="text-lg font-bold text-emerald-600 mt-1">Nu. {{ number_format($vehicle->transaction->sale_price, 0) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Commission</label>
                        <p class="text-slate-800 font-semibold mt-1">Nu. {{ number_format($vehicle->transaction->broker_commission, 0) }}</p>
                    </div>
                    @if($vehicle->transaction->completed_at)
                        <div>
                            <label class="text-sm font-medium text-slate-500">Completed At</label>
                            <p class="text-slate-700 mt-1">{{ $vehicle->transaction->completed_at->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Reviews & Ratings -->
        <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Reviews & Ratings</h2>
                    <p class="text-sm text-slate-500 mt-1">Verified buyer feedback for this vehicle</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-extrabold text-amber-500">{{ number_format($reviewStats['average'], 1) }}</p>
                    <p class="text-xs uppercase tracking-widest text-slate-500">Average Rating · {{ $reviewStats['count'] }} review(s)</p>
                </div>
            </div>

            @if($reviewStats['count'] > 0)
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6">
                    @foreach([5,4,3,2,1] as $rating)
                        <div class="rounded-lg border border-slate-200 p-3">
                            <div class="flex items-center justify-between text-sm font-medium text-slate-700">
                                <span>{{ $rating }}★</span>
                                <span>{{ $reviewStats['breakdown'][$rating] }}</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-2 bg-amber-400 rounded-full" style="width: {{ $reviewStats['count'] > 0 ? ($reviewStats['breakdown'][$rating] / $reviewStats['count']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-5 text-slate-600 mb-6">
                    No reviews have been published for this vehicle yet.
                </div>
            @endif

            @if($canReview)
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 mb-6">
                    <h3 class="text-base font-bold text-emerald-900 mb-3">Share your purchase experience</h3>
                    <form method="POST" action="{{ route('vehicle-reviews.store', $vehicle) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Rating</label>
                            <select name="rating" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="5">5 - Excellent</option>
                                <option value="4">4 - Very Good</option>
                                <option value="3">3 - Good</option>
                                <option value="2">2 - Fair</option>
                                <option value="1">1 - Poor</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Title</label>
                            <input type="text" name="title" maxlength="120" placeholder="Summarize your experience"
                                   class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Comment</label>
                            <textarea name="comment" rows="4" maxlength="2000" placeholder="Tell others what you liked about the vehicle..."
                                      class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Pros</label>
                                <textarea name="pros" rows="3" maxlength="1000" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Cons</label>
                                <textarea name="cons" rows="3" maxlength="1000" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                            </div>
                        </div>
                        <label class="flex items-center gap-3 text-sm text-slate-700">
                            <input type="checkbox" name="would_recommend" value="1" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            I would recommend this vehicle to other buyers
                        </label>
                        <button type="submit" class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-5 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                            Submit Review
                        </button>
                    </form>
                </div>
            @elseif($existingReview)
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 mb-6 text-emerald-900">
                    You already reviewed this completed purchase. Thank you for helping other buyers.
                </div>
            @else
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 mb-6 text-slate-600">
                    Only verified buyers with a completed transaction can submit a review.
                </div>
            @endif

            @if($reviewStats['count'] > 0)
                <div class="space-y-4">
                    @foreach($vehicle->reviews->where('status', 'published')->take(5) as $review)
                        <article class="rounded-xl border border-slate-200 p-5">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 text-amber-500 text-sm font-bold">
                                        <span>{{ str_repeat('★', $review->rating) }}</span>
                                        <span class="text-slate-500">{{ $review->ratingLabel() }}</span>
                                    </div>
                                    <h3 class="mt-1 text-base font-bold text-slate-800">{{ $review->title ?? 'Buyer review' }}</h3>
                                    <p class="text-sm text-slate-500 mt-1">By {{ $review->reviewer->name ?? 'Verified Buyer' }} · {{ $review->created_at->format('M d, Y') }}</p>
                                </div>
                                @if($review->would_recommend)
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Recommended</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Not Recommended</span>
                                @endif
                            </div>
                            @if($review->comment)
                                <p class="mt-4 text-slate-700 leading-relaxed">{{ $review->comment }}</p>
                            @endif
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm">
                                @if($review->pros)
                                    <div class="rounded-lg bg-emerald-50 p-4 border border-emerald-100">
                                        <p class="font-semibold text-emerald-900 mb-1">Pros</p>
                                        <p class="text-emerald-800">{{ $review->pros }}</p>
                                    </div>
                                @endif
                                @if($review->cons)
                                    <div class="rounded-lg bg-rose-50 p-4 border border-rose-100">
                                        <p class="font-semibold text-rose-900 mb-1">Cons</p>
                                        <p class="text-rose-800">{{ $review->cons }}</p>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Actions</h2>
            <div class="space-y-3">
                @if($canEdit)
                    <a href="{{ $editRoute ?? route('vehicles.edit', $vehicle) }}" 
                       class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Vehicle
                    </a>
                      <form method="POST" action="{{ $deleteRoute ?? route('vehicles.destroy', $vehicle) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this vehicle? This action cannot be undone.')">
                        @csrf @method('DELETE')
                        <button class="w-full bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2.5 rounded-lg transition flex items-center justify-center gap-2 font-medium border border-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Vehicle
                        </button>
                    </form>
                @else
                    <a href="{{ route('inquiries.create', ['vehicle_id' => $vehicle->id]) }}" 
                       class="w-full bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-4 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        Send Inquiry
                    </a>
                    
                    @if(auth()->user()->hasRole('buyer') && $vehicle->status === 'available')
                        <a href="{{ route('bookings.create', $vehicle) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 font-medium mt-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Book Vehicle
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" 
                       class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-lg transition flex items-center justify-center gap-2 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Dashboard
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Similar Vehicles Section - Enhanced -->
<div class="mt-16 pt-12 border-t-2 border-emerald-200">
    <div class="mx-auto">
        <!-- Section Header -->
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-500 to-lime-500 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl lg:text-4xl font-bold bg-gradient-to-r from-emerald-700 via-lime-600 to-emerald-700 bg-clip-text text-transparent">Similar Vehicles</h2>
                    <p class="text-gray-600 text-sm mt-1">Browse other {{ $vehicle->brand }} models and similar options</p>
                </div>
            </div>
        </div>

        <!-- Vehicles Grid -->
        @php
            // Get similar vehicles (same brand or similar price range)
            $similarVehicles = \App\Models\Vehicle::where('id', '!=', $vehicle->id)
                ->where(function($q) use ($vehicle) {
                    $q->where('brand', $vehicle->brand)
                      ->orWhereBetween('price', [$vehicle->price * 0.8, $vehicle->price * 1.2]);
                })
                ->where('status', 'available')
                ->limit(6)
                ->get();
        @endphp

        @if($similarVehicles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($similarVehicles as $similar)
                    <a href="{{ route('vehicles.show', $similar) }}" class="group">
                        <div class="overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 bg-white border border-gray-200">
                            <!-- Image Container -->
                            <div class="relative h-56 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                                @if($similar->images && count($similar->images) > 0)
                                    <img src="{{ asset('storage/' . $similar->images[0]) }}" 
                                         alt="{{ $similar->brand }} {{ $similar->model }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Badge -->
                                <div class="absolute top-4 right-4">
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-emerald-500 to-lime-500 text-white shadow-lg">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Available
                                    </span>
                                </div>

                                <!-- Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <!-- Title -->
                                <h3 class="text-lg lg:text-xl font-bold text-gray-900 mb-2 group-hover:text-emerald-700 transition-colors">
                                    {{ $similar->brand }} {{ $similar->model }}
                                </h3>

                                <!-- Specs -->
                                <div class="grid grid-cols-2 gap-3 mb-4 pb-4 border-b border-gray-200">
                                    <div>
                                        <p class="text-xs text-gray-500 font-semibold uppercase">Year</p>
                                        <p class="text-base font-bold text-gray-900">{{ $similar->year }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-semibold uppercase">Mileage</p>
                                        <p class="text-base font-bold text-gray-900">{{ number_format($similar->mileage) }} km</p>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="flex items-baseline justify-between">
                                    <p class="text-2xl font-bold bg-gradient-to-r from-emerald-700 to-lime-600 bg-clip-text text-transparent">
                                        Nu. {{ number_format($similar->price, 0) }}
                                    </p>
                                    <span class="text-emerald-600 font-semibold text-sm group-hover:translate-x-1 transition-transform">View →</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <!-- No Similar Vehicles Message -->
            <div class="py-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No Similar Vehicles Available</h3>
                <p class="text-gray-600 mb-6">We don't have other vehicles matching this criteria at the moment</p>
                <a href="{{ route('vehicles.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-lime-600 hover:from-emerald-700 hover:to-lime-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6"/>
                    </svg>
                    Browse All Vehicles
                </a>
            </div>
        @endif
    </div>
</div>

@endsection
