@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Seller Dashboard')

@section('content')
<!-- Premium Header Section -->
<div class="mb-8 bg-gradient-to-r from-purple-600 via-purple-600 to-pink-600 rounded-3xl p-8 text-white shadow-2xl">
    <h1 class="text-4xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
    <p class="text-purple-100 text-lg">You are logged in as a {{ auth()->user()->hasRole(\App\Models\User::ROLE_BROKER) ? 'broker' : 'seller' }}</p>
</div>

@if(auth()->user()->hasRole(\App\Models\User::ROLE_BROKER) && !auth()->user()->isBrokerLicenseApproved())
    <div class="mb-8 rounded-2xl border-2 border-amber-300 bg-amber-50 p-6" role="alert" aria-live="polite">
        <h2 class="text-lg font-bold text-amber-900">Broker Approval Pending</h2>
        <p class="mt-2 text-sm text-amber-800">
            Your dealer license is currently <strong>{{ ucfirst(str_replace('_', ' ', auth()->user()->dealer_license_status)) }}</strong>. You can browse vehicles and manage inquiries with buyers/sellers, but vehicle listing and transaction deal actions will unlock after admin approval.
        </p>
        <a href="{{ route('broker.license.show') }}" class="mt-4 inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">
            Review Dealer License Status
        </a>
    </div>
@endif

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-8">
    <!-- Stat Card 1 - Total Vehicles -->
    <div class="stat-card-modern card-blue dashboard-card-hover">
        <div class="relative z-10 flex items-start justify-between">
            <div>
                <p class="text-blue-700 text-sm font-semibold mb-2 uppercase tracking-wide">Total Vehicles</p>
                <p class="text-5xl font-bold text-blue-900 mb-2">{{ $stats['vehicles'] ?? 0 }}</p>
                <p class="text-blue-600 text-xs font-medium">Across all listings</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 - Available Vehicles -->
    <div class="stat-card-modern card-emerald dashboard-card-hover">
        <div class="relative z-10 flex items-start justify-between">
            <div>
                <p class="text-emerald-700 text-sm font-semibold mb-2 uppercase tracking-wide">Available</p>
                <p class="text-5xl font-bold text-emerald-900 mb-2">{{ $stats['available_vehicles'] ?? 0 }}</p>
                <p class="text-emerald-600 text-xs font-medium">Ready for sale</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 - Sold Vehicles -->
    <div class="stat-card-modern card-purple dashboard-card-hover">
        <div class="relative z-10 flex items-start justify-between">
            <div>
                <p class="text-purple-700 text-sm font-semibold mb-2 uppercase tracking-wide">Sold</p>
                <p class="text-5xl font-bold text-purple-900 mb-2">{{ $stats['sold_vehicles'] ?? 0 }}</p>
                <p class="text-purple-600 text-xs font-medium">Completed sales</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <a href="{{ route('my-vehicles.create') }}" class="dashboard-card dashboard-card-hover text-center hover:bg-blue-50 group">
            <div class="flex flex-col items-center justify-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-900">List My Car</span>
            </div>
        </a>

        <a href="{{ route('inquiries.index') }}" class="dashboard-card dashboard-card-hover text-center hover:bg-amber-50 group">
            <div class="flex flex-col items-center justify-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-900">Inquiries</span>
            </div>
        </a>

        <a href="{{ route('transactions.index') }}" class="dashboard-card dashboard-card-hover text-center hover:bg-emerald-50 group">
            <div class="flex flex-col items-center justify-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-900">Transactions</span>
            </div>
        </a>
    </div>

    @if(auth()->user()->hasRole(\App\Models\User::ROLE_BROKER) && !auth()->user()->isBrokerLicenseApproved())
        <p class="mt-3 text-xs text-slate-600">
            Tip: Listing and transaction actions redirect to the dealer license approval page until admin approval is completed.
        </p>
    @endif
</div>

<!-- Browse by Brand -->
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Browse by Brand</h2>
            <p class="text-gray-600 text-sm">Review popular brands in the marketplace</p>
        </div>
        <a href="{{ route('vehicles.browse') }}" class="text-purple-600 hover:text-purple-700 font-semibold text-sm">View All →</a>
    </div>

    @php
        $brands = [
            'Maruti Suzuki', 'Tata', 'Mahindra', 'Hyundai', 'Toyota', 'Kia',
            'BMW', 'Skoda', 'Honda', 'MG', 'Volkswagen', 'Renault',
            'Mercedes-Benz', 'Land Rover', 'Nissan', 'BYD', 'Citroen', 'VinFast',
            'Jeep', 'Audi', 'Porsche', 'Volvo', 'Lexus', 'Fiat',
            'Lamborghini', 'Mini', 'Force Motors', 'Jaguar', 'Ferrari', 'JSW'
        ];
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($brands as $brand)
            <a href="{{ route('vehicles.browse', ['brand' => $brand]) }}" class="group bg-white rounded-2xl border-2 border-gray-200 p-5 text-center hover:border-purple-500 hover:shadow-lg transition-all duration-300">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center group-hover:scale-110 transition-transform border border-purple-200">
                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13l1.5-4.5A2 2 0 016.4 7h11.2a2 2 0 011.9 1.5L21 13m-18 0h18m-18 0v4a1 1 0 001 1h1m16-5v4a1 1 0 01-1 1h-1m-14 0a2 2 0 104 0m10 0a2 2 0 104 0M6 13h12" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-800 group-hover:text-purple-600 transition-colors">{{ $brand }}</p>
            </a>
        @endforeach
    </div>
</div>

<!-- Available Vehicles Section -->
<div class="mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Available Vehicles</h2>
        <a href="{{ route('my-vehicles.index') }}" class="text-purple-600 hover:text-purple-700 font-semibold text-sm">View All →</a>
    </div>

    @if($recentListings->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recentListings as $vehicle)
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
                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $vehicle->brand }} <span class="text-purple-600">{{ $vehicle->model }}</span></h3>
                        
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
                            <a href="{{ route('my-vehicles.edit', $vehicle) }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('my-vehicles.destroy', $vehicle) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No vehicles listed yet</h3>
            <p class="text-gray-600 mb-6">Start by listing your first vehicle</p>
            <a href="{{ route('my-vehicles.create') }}" class="inline-block px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition">
                List Your First Car
            </a>
        </div>
    @endif
</div>

<!-- System Status -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-green-500"></div>
            System Status
        </h3>
        <p class="text-gray-600">All systems operational</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Last Login</h3>
        <p class="text-gray-600">Today at this time</p>
    </div>
</div>
@endsection
