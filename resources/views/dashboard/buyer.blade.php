@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Buyer Dashboard')

@section('content')
<!-- Premium Header Section -->
<div class="mb-8 bg-gradient-to-r from-blue-600 via-blue-600 to-cyan-600 rounded-3xl p-8 text-white shadow-2xl">
    <h1 class="text-4xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
    <p class="text-blue-100 text-lg">You are logged in as a buyer</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-8">
    <!-- Stat Card 1 - My Bookings -->
    <div class="stat-card-modern card-blue dashboard-card-hover group">
        <div class="relative z-10 flex items-start justify-between">
            <div>
                <p class="text-blue-700 text-sm font-semibold mb-2 uppercase tracking-wide">My Bookings</p>
                <p class="text-5xl font-bold text-blue-900 mb-2">{{ $stats['bookings'] ?? 0 }}</p>
                <p class="text-blue-600 text-xs font-medium">Active bookings</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 - Available Vehicles -->
    <div class="stat-card-modern card-emerald dashboard-card-hover group">
        <div class="relative z-10 flex items-start justify-between">
            <div>
                <p class="text-emerald-700 text-sm font-semibold mb-2 uppercase tracking-wide">Available</p>
                <p class="text-5xl font-bold text-emerald-900 mb-2">{{ $availableVehiclesCount ?? 0 }}</p>
                <p class="text-emerald-600 text-xs font-medium">Vehicles for sale</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 - Purchases -->
    <div class="stat-card-modern card-cyan dashboard-card-hover group">
        <div class="relative z-10 flex items-start justify-between">
            <div>
                <p class="text-cyan-700 text-sm font-semibold mb-2 uppercase tracking-wide">Purchases</p>
                <p class="text-5xl font-bold text-cyan-900 mb-2">{{ $stats['transactions'] ?? 0 }}</p>
                <p class="text-cyan-600 text-xs font-medium">Completed purchases</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
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
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('vehicles.browse') }}" class="dashboard-card dashboard-card-hover text-center hover:bg-blue-50 group">
            <div class="flex flex-col items-center justify-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-900">Browse Vehicles</span>
            </div>
        </a>

        <a href="{{ route('bookings.index') }}" class="dashboard-card dashboard-card-hover text-center hover:bg-purple-50 group">
            <div class="flex flex-col items-center justify-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-900">My Bookings</span>
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
</div>

<!-- Browse by Brand -->
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Browse by Brand</h2>
            <p class="text-gray-600 text-sm">Select a brand to explore available vehicles</p>
        </div>
        <a href="{{ route('vehicles.browse') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">View All →</a>
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
            <a href="{{ route('vehicles.browse', ['brand' => $brand]) }}" class="group bg-white rounded-2xl border-2 border-gray-200 p-5 text-center hover:border-blue-500 hover:shadow-lg transition-all duration-300">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center group-hover:scale-110 transition-transform border border-blue-200">
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13l1.5-4.5A2 2 0 016.4 7h11.2a2 2 0 011.9 1.5L21 13m-18 0h18m-18 0v4a1 1 0 001 1h1m16-5v4a1 1 0 01-1 1h-1m-14 0a2 2 0 104 0m10 0a2 2 0 104 0M6 13h12" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">{{ $brand }}</p>
            </a>
        @endforeach
    </div>
</div>

<!-- Available Vehicles Section -->
<div class="mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Available Vehicles</h2>
        <a href="{{ route('vehicles.browse') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">View All →</a>
    </div>

    @if($approvedVehicles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($approvedVehicles as $vehicle)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-xl hover:border-blue-300 transition-all overflow-hidden group card-hover">
                    <div class="relative h-64 bg-gray-200 overflow-hidden">
                        @if($vehicle->images && count($vehicle->images) > 0)
                            <img src="{{ asset('storage/' . $vehicle->images[0]) }}" 
                                 alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                 loading="lazy">
                        @else

                            <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                                <span class="text-gray-600 font-semibold">No Image</span>
                            </div>
                        @endif

                        <div class="absolute top-4 right-4 bg-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg font-bold">
                            5.0
                        </div>

                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                        <p class="text-gray-600 text-sm mb-3">{{ $vehicle->year }} • {{ number_format($vehicle->mileage ?? 0) }} km</p>

                        <div class="flex items-center justify-between mb-4 pb-4 border-b">

                            <div>
                                <p class="text-blue-600 text-2xl font-bold">Nu. {{ number_format($vehicle->price) }}</p>
                            </div>
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                ✓ {{ ucfirst($vehicle->status) }}
                            </span>
                        </div>


                        <div class="flex gap-2">
                            <a href="{{ route('vehicles.show', $vehicle) }}" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-center block">
                                View Details
                            </a>
                            <a href="{{ route('bookings.create', $vehicle) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition">
                                Book
                            </a>

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
            <h3 class="text-xl font-bold text-gray-900 mb-2">No vehicles available</h3>
            <p class="text-gray-600 mb-6">Check back soon for more listings</p>
            <a href="{{ route('vehicles.browse') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                Browse All Vehicles
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
