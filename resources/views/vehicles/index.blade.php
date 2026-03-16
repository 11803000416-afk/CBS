@extends('layouts.app')

@section('title', 'Vehicles')
@section('subtitle', 'Manage vehicle inventory and listings')

@section('content')
<!-- Search & Filter Section -->
<div class="glass-card rounded-xl p-6 mb-6">
    <div class="flex items-center gap-2 mb-4">
        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <h3 class="font-semibold text-white">Search & Filter</h3>
    </div>
    <form method="GET" action="{{ route('vehicles.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-7 gap-4">
        <input name="brand" value="{{ request('brand') }}" placeholder="Brand" class="bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        <input name="model" value="{{ request('model') }}" placeholder="Model" class="bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        <input name="year" value="{{ request('year') }}" placeholder="Year" type="number" class="bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        <input name="min_price" value="{{ request('min_price') }}" placeholder="Min Price" type="number" step="0.01" class="bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        <input name="max_price" value="{{ request('max_price') }}" placeholder="Max Price" type="number" step="0.01" class="bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        <select name="status" class="bg-white/10 border border-white/20 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            <option value="" class="bg-gray-800">All Status</option>
            @foreach(['available', 'reserved', 'sold'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status) class="bg-gray-800">{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-all flex items-center justify-center gap-2 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Search
        </button>
    </form>
</div>

<div class="flex justify-between items-center mb-6">
    <div>
        <p class="text-sm text-gray-400">Total: <span class="font-semibold text-white">{{ $vehicles->total() }}</span> vehicles</p>
    </div>
    <a href="{{ route('vehicles.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg transition-all flex items-center gap-2 font-medium shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Vehicle
    </a>
</div>

<!-- Vehicle Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($vehicles as $vehicle)
        <div class="glass-card rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300">
            <!-- Vehicle Image -->
            <div class="relative h-48 bg-gradient-to-br from-gray-800 to-gray-900 overflow-hidden">
                @if($vehicle->images && count($vehicle->images) > 0)
                    <img src="{{ asset('storage/' . $vehicle->images[0]) }}" 
                         alt="{{ $vehicle->brand }} {{ $vehicle->model }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-purple-50">
                        <svg class="vehicle-icon-animated" width="80" height="80" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Car Body -->
                            <path d="M20 45 L28 30 L40 25 L70 25 L80 35 L85 45 Z" fill="url(#carGradient)" stroke="#3b82f6" stroke-width="2"/>
                            <rect x="15" y="45" width="70" height="15" rx="3" fill="url(#carGradient)" stroke="#3b82f6" stroke-width="2"/>
                            
                            <!-- Windows -->
                            <path d="M32 30 L42 30 L42 40 L30 40 Z" fill="#60a5fa" opacity="0.6"/>
                            <path d="M50 30 L65 30 L72 38 L72 40 L50 40 Z" fill="#60a5fa" opacity="0.6"/>
                            
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
                                <linearGradient id="carGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                @endif
                <!-- Status Badge -->
                <div class="absolute top-3 right-3">
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold shadow-lg backdrop-blur-sm
                        {{ $vehicle->status === 'available' ? 'bg-emerald-500/90 text-white' : '' }}
                        {{ $vehicle->status === 'reserved' ? 'bg-blue-500/90 text-white' : '' }}
                        {{ $vehicle->status === 'sold' ? 'bg-slate-500/90 text-white' : '' }}">
                        {{ ucfirst($vehicle->status) }}
                    </span>
                </div>
            </div>

            <!-- Card Content -->
            <div class="p-5">
                <!-- Vehicle Name -->
                <h3 class="text-xl font-bold text-white mb-2">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                
                <!-- Vehicle Details -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Year</span>
                        <span class="font-semibold text-gray-200">{{ $vehicle->year }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Mileage</span>
                        <span class="font-semibold text-gray-200">{{ number_format($vehicle->mileage) }} km</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Seller</span>
                        <span class="font-semibold text-gray-200">{{ $vehicle->seller->name ?? '-' }}</span>
                    </div>
                </div>

                <!-- Description Preview -->
                @if($vehicle->description)
                    <p class="text-sm text-gray-300 mb-4 line-clamp-2">{{ $vehicle->description }}</p>
                @endif

                <!-- Price -->
                <div class="border-t border-white/10 pt-4 mb-4">
                    <div class="flex items-baseline gap-1">
                        <span class="text-2xl font-bold text-green-400">Nu. {{ number_format($vehicle->price, 2) }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <a href="{{ route('vehicles.show', $vehicle) }}" 
                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-all flex items-center justify-center gap-2 font-medium text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View
                    </a>
                    <a href="{{ route('vehicles.edit', $vehicle) }}" 
                       class="bg-white/10 hover:bg-white/20 text-white px-3 py-2.5 rounded-lg transition flex items-center justify-center border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Are you sure you want to delete this vehicle?')" class="inline">
                        @csrf @method('DELETE')
                        <button class="bg-red-500/20 hover:bg-red-500/30 text-red-400 px-3 py-2.5 rounded-lg transition flex items-center justify-center border border-red-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="glass-card rounded-xl p-12 text-center relative overflow-hidden">
                <svg class="vehicle-icon-animated mx-auto mb-6 relative z-10" width="120" height="120" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 45 L28 30 L40 25 L70 25 L80 35 L85 45 Z" fill="url(#carGrad5)" stroke="#3b82f6" stroke-width="2"/>
                    <rect x="15" y="45" width="70" height="15" rx="3" fill="url(#carGrad5)" stroke="#3b82f6" stroke-width="2"/>
                    <path d="M32 30 L42 30 L42 40 L30 40 Z" fill="#60a5fa" opacity="0.6"/>
                    <path d="M50 30 L65 30 L72 38 L72 40 L50 40 Z" fill="#60a5fa" opacity="0.6"/>
                    <circle class="headlight-glow" cx="82" cy="50" r="3" fill="#10b981"/>
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
                    <defs>
                        <linearGradient id="carGrad5" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                            <stop offset="50%" style="stop-color:#8b5cf6;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#ec4899;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                </svg>
                <p class="text-white font-semibold text-xl mb-2 relative z-10">No vehicles found</p>
                <p class="text-gray-400 text-sm relative z-10">Try adjusting your filters or add a new vehicle</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $vehicles->links() }}</div>
@endsection
