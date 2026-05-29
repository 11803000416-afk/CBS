@extends('layouts.app')

@section('title', 'My Vehicle Listings')
@section('subtitle', 'Manage your cars for sale or exchange')

@section('content')
<!-- Header Section -->
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <p class="text-lg font-semibold text-gray-900">
            <span class="text-blue-600 font-bold text-2xl">{{ $vehicles->total() }}</span>
            <span class="text-gray-600">active listings</span>
        </p>
    </div>
    <a href="{{ route('my-vehicles.create') }}" class="w-full sm:w-auto bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 font-bold transform hover:-translate-y-0.5">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        List New Car
    </a>
</div>

<!-- Vehicle Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    @forelse($vehicles as $vehicle)
        <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow overflow-hidden group">
            <!-- Image Container with Badge -->
            <div class="relative h-64 bg-gray-200 overflow-hidden">
                @if($vehicle->images && count($vehicle->images) > 0)
                    <img src="{{ asset('storage/' . $vehicle->images[0]) }}" 
                         alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif

                <!-- Status Badge -->
                <div class="absolute top-3 right-3">
                    <span class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">
                        {{ ucfirst($vehicle->status) }}
                    </span>
                </div>

                <!-- Photo Count Badge -->
                @if($vehicle->images && count($vehicle->images) > 0)
                    <div class="absolute top-3 left-3 bg-gray-800 bg-opacity-70 text-white px-2 py-1 rounded-lg text-xs font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                        </svg>
                        {{ count($vehicle->images) }}
                    </div>
                @endif
            </div>

            <!-- Vehicle Details -->
            <div class="p-5">
                <!-- Brand and Model -->
                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $vehicle->brand }} <span class="text-blue-600">{{ $vehicle->model }}</span></h3>
                
                <!-- Year -->
                <p class="text-gray-600 text-sm mb-4">Year: {{ $vehicle->year }}</p>

                <!-- Details Row 1 -->
                <div class="flex gap-4 mb-4 text-xs text-gray-600 border-t border-b py-3">
                    <div>
                        <p class="font-semibold text-gray-700 mb-1">MILEAGE</p>
                        <p class="text-gray-900 font-bold">{{ number_format($vehicle->mileage ?? 0) }} km</p>
                    </div>
                    <div class="flex-1 text-right">
                        <p class="font-semibold text-gray-700 mb-1">LISTED</p>
                        <p class="text-gray-900 font-bold">{{ $vehicle->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <!-- VIN/Code -->
                @if($vehicle->vin)
                    <div class="mb-4 bg-gray-100 p-3 rounded-lg border border-gray-300">
                        <p class="text-xs text-gray-600 font-semibold mb-1">VIN CODE</p>
                        <p class="text-sm font-mono text-gray-900 break-all">{{ $vehicle->vin }}</p>
                    </div>
                @endif

                <!-- Price -->
                <div class="mb-4 bg-green-50 p-4 rounded-xl border border-green-200">
                    <p class="text-xs font-semibold text-green-700 mb-1 uppercase">ASKING PRICE</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $vehicle->currency ?? 'Nu.' }} {{ number_format($vehicle->price ?? 0, 2) }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <a href="{{ route('vehicles.show', $vehicle) }}" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View
                    </a>
                    <a href="{{ route('my-vehicles.edit', $vehicle) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('my-vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Are you sure?')" class="inline">
                        @csrf @method('DELETE')
                        <button class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-semibold rounded-lg transition flex items-center gap-2">
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
            <div class="bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 rounded-xl shadow-md border border-blue-200 p-12 text-center relative overflow-hidden">
                <div class="absolute inset-0 shimmer-effect opacity-20"></div>
                <svg class="vehicle-icon-animated mx-auto mb-6 relative z-10" width="120" height="120" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 45 L28 30 L40 25 L70 25 L80 35 L85 45 Z" fill="url(#carGrad2)" stroke="#3b82f6" stroke-width="2"/>
                    <rect x="15" y="45" width="70" height="15" rx="3" fill="url(#carGrad2)" stroke="#3b82f6" stroke-width="2"/>
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
                        <linearGradient id="carGrad2" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                            <stop offset="50%" style="stop-color:#8b5cf6;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#ec4899;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                </svg>
                <p class="text-slate-600 font-semibold text-xl mb-2 relative z-10">No listings yet</p>
                <p class="text-slate-500 text-sm mb-6 relative z-10">Start selling or exchanging your car</p>
                <a href="{{ route('my-vehicles.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg shadow-md hover:shadow-xl transition-all font-medium relative z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    List Your First Car
                </a>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $vehicles->links() }}</div>
@endsection
