@extends('layouts.app')

@section('title', $vehicle->brand . ' ' . $vehicle->model)
@section('subtitle', 'Vehicle Details')

@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('vehicles.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 font-medium transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Vehicles
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Image Gallery -->
        <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
            @if($vehicle->images && count($vehicle->images) > 0)
                <!-- Main Image -->
                <div class="relative h-96 bg-gradient-to-br from-slate-100 to-slate-200">
                    <img id="mainImage" 
                         src="{{ asset('storage/' . $vehicle->images[0]) }}" 
                         alt="{{ $vehicle->brand }} {{ $vehicle->model }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4">
                        <span class="inline-flex px-4 py-2 rounded-full text-sm font-semibold shadow-lg backdrop-blur-sm
                            {{ $vehicle->status === 'available' ? 'bg-emerald-500/90 text-white' : '' }}
                            {{ $vehicle->status === 'reserved' ? 'bg-blue-500/90 text-white' : '' }}
                            {{ $vehicle->status === 'sold' ? 'bg-slate-500/90 text-white' : '' }}">
                            {{ ucfirst($vehicle->status) }}
                        </span>
                    </div>
                </div>

                <!-- Thumbnail Gallery -->
                @if(count($vehicle->images) > 1)
                    <div class="p-4 bg-slate-50 border-t border-slate-200">
                        <div class="flex gap-3 overflow-x-auto">
                            @foreach($vehicle->images as $image)
                                <img src="{{ asset('storage/' . $image) }}" 
                                     alt="{{ $vehicle->brand }} {{ $vehicle->model }}" 
                                     onclick="document.getElementById('mainImage').src = this.src"
                                     class="w-20 h-20 object-cover rounded-lg border-2 border-slate-200 hover:border-blue-500 cursor-pointer transition">
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div class="h-96 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 shimmer-effect opacity-30"></div>
                    <div class="text-center relative z-10">
                        <svg class="vehicle-icon-animated mx-auto mb-6" width="160" height="160" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Car Body -->
                            <path d="M20 45 L28 30 L40 25 L70 25 L80 35 L85 45 Z" fill="url(#carGradientLarge)" stroke="#3b82f6" stroke-width="2"/>
                            <rect x="15" y="45" width="70" height="15" rx="3" fill="url(#carGradientLarge)" stroke="#3b82f6" stroke-width="2"/>
                            
                            <!-- Windows -->
                            <path d="M32 30 L42 30 L42 40 L30 40 Z" fill="#60a5fa" opacity="0.6"/>
                            <path d="M50 30 L65 30 L72 38 L72 40 L50 40 Z" fill="#60a5fa" opacity="0.6"/>
                            
                            <!-- Door Handle -->
                            <rect x="55" y="50" width="8" height="2" rx="1" fill="#64748b" opacity="0.7"/>
                            
                            <!-- Headlight -->
                            <circle class="headlight-glow" cx="82" cy="50" r="3" fill="#10b981"/>
                            
                            <!-- Wheels with animation -->
                            <g class="wheel-animated">
                                <circle cx="30" cy="60" r="8" fill="#1e293b" stroke="#64748b" stroke-width="2"/>
                                <circle cx="30" cy="60" r="5" fill="#94a3b8"/>
                                <line x1="30" y1="55" x2="30" y2="65" stroke="#1e293b" stroke-width="1.5"/>
                                <line x1="25" y1="60" x2="35" y2="60" stroke="#1e293b" stroke-width="1.5"/>
                            </g>
                            <g class="wheel-animated">
                                <circle cx="70" cy="60" r="8" fill="#1e293b" stroke="#64748b" stroke-width="2"/>
                                <circle cx="70" cy="60" r="5" fill="#94a3b8"/>
                                <line x1="70" y1="55" x2="70" y2="65" stroke="#1e293b" stroke-width="1.5"/>
                                <line x1="65" y1="60" x2="75" y2="60" stroke="#1e293b" stroke-width="1.5"/>
                            </g>
                            
                            <!-- Gradients -->
                            <defs>
                                <linearGradient id="carGradientLarge" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                                    <stop offset="50%" style="stop-color:#8b5cf6;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#ec4899;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <p class="text-slate-500 font-semibold text-lg">No images available</p>
                        <p class="text-slate-400 text-sm mt-2">Upload images to showcase this vehicle</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Vehicle Details -->
        <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6">
            <h2 class="text-2xl font-bold text-slate-800 mb-6">Vehicle Information</h2>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-slate-500">Brand</label>
                    <p class="text-lg font-semibold text-slate-800 mt-1">{{ $vehicle->brand }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-500">Model</label>
                    <p class="text-lg font-semibold text-slate-800 mt-1">{{ $vehicle->model }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-500">Year</label>
                    <p class="text-lg font-semibold text-slate-800 mt-1">{{ $vehicle->year }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-500">Mileage</label>
                    <p class="text-lg font-semibold text-slate-800 mt-1">{{ number_format($vehicle->mileage) }} km</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-500">Price</label>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">Nu. {{ number_format($vehicle->price, 2) }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-500">Status</label>
                    <p class="mt-1">
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold
                            {{ $vehicle->status === 'available' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $vehicle->status === 'reserved' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $vehicle->status === 'sold' ? 'bg-slate-100 text-slate-700' : '' }}">
                            {{ ucfirst($vehicle->status) }}
                        </span>
                    </p>
                </div>
            </div>

            @if($vehicle->description)
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <label class="text-sm font-medium text-slate-500">Description</label>
                    <p class="text-slate-700 mt-2 leading-relaxed">{{ $vehicle->description }}</p>
                </div>
            @endif
        </div>

        <!-- Inquiries -->
        @if(auth()->user()->hasRole(['admin', 'agent']) && $vehicle->inquiries && $vehicle->inquiries->count() > 0)
            <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6">
                <h2 class="text-xl font-bold text-slate-800 mb-4">Inquiries ({{ $vehicle->inquiries->count() }})</h2>
                <div class="space-y-3">
                    @foreach($vehicle->inquiries as $inquiry)
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $inquiry->buyer->name }}</p>
                                    <p class="text-sm text-slate-600">{{ $inquiry->buyer->email }}</p>
                                </div>
                                <span class="text-xs text-slate-500">{{ $inquiry->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-slate-700">{{ $inquiry->message }}</p>
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
        @if(auth()->user()->hasRole(['admin', 'agent']) && $vehicle->broker)
            <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Listed By</h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-slate-500">Agent</label>
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
        @if(auth()->user()->hasRole(['admin', 'agent']) && $vehicle->transaction)
            <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Transaction Details</h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-slate-500">Buyer</label>
                        <p class="text-slate-800 font-semibold mt-1">{{ $vehicle->transaction->buyer->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Sale Price</label>
                        <p class="text-lg font-bold text-emerald-600 mt-1">Nu. {{ number_format($vehicle->transaction->sale_price, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Commission</label>
                        <p class="text-slate-800 font-semibold mt-1">Nu. {{ number_format($vehicle->transaction->broker_commission, 2) }}</p>
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

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Actions</h2>
            <div class="space-y-3">
                @php
                    $userSeller = auth()->user()->hasRole(['admin', 'agent']) ? null : \App\Models\Seller::where('email', auth()->user()->email)->first();
                    $canEdit = auth()->user()->hasRole(['admin', 'agent']) || ($userSeller && $vehicle->seller_id === $userSeller->id);
                @endphp
                
                @if($canEdit)
                    <a href="{{ auth()->user()->hasRole(['admin', 'agent']) ? route('vehicles.edit', $vehicle) : route('my-vehicles.edit', $vehicle) }}" 
                       class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Vehicle
                    </a>
                    <form method="POST" action="{{ auth()->user()->hasRole(['admin', 'agent']) ? route('vehicles.destroy', $vehicle) : route('my-vehicles.destroy', $vehicle) }}" 
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
@endsection
