@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Buyer Dashboard')

@section('content')
<!-- Premium Header Section -->
<div class="mb-8 rounded-3xl bg-gradient-to-r from-blue-600 via-blue-600 to-cyan-600 p-6 text-white shadow-2xl sm:p-8">
    <h1 class="text-4xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
    <p class="text-blue-100 text-lg">You are logged in as a buyer</p>
</div>

<!-- Stats Grid -->
<div class="dashboard-stats-grid lg:grid-cols-3">
    <!-- Stat Card 1 - My Bookings -->
    <div class="stat-card-modern card-blue dashboard-card-hover group">
        <div class="relative z-10">
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
        <div class="relative z-10">
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
        <div class="relative z-10">
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
    <div class="dashboard-quick-grid md:grid-cols-4">
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

        // Brand logo mapping (external browser links). Update URLs as needed.
        $brandLogos = [
            'Maruti Suzuki' => 'https://upload.wikimedia.org/wikipedia/commons/6/6b/Maruti_Suzuki_logo.svg',
            'Tata' => 'https://upload.wikimedia.org/wikipedia/commons/6/6e/Tata_Motors_logo.svg',
            'Mahindra' => 'https://upload.wikimedia.org/wikipedia/commons/6/6e/Mahindra_Logo.svg',
            'Hyundai' => 'https://upload.wikimedia.org/wikipedia/commons/1/1b/Hyundai_logo.svg',
            'Toyota' => 'https://upload.wikimedia.org/wikipedia/commons/9/9d/Toyota_logo.svg',
            'Kia' => 'https://upload.wikimedia.org/wikipedia/commons/6/6e/Kia_logo.svg',
            'BMW' => 'https://upload.wikimedia.org/wikipedia/commons/4/44/BMW.svg',
            'Skoda' => 'https://upload.wikimedia.org/wikipedia/commons/7/79/Skoda_Auto_logo.svg',
            'Honda' => 'https://upload.wikimedia.org/wikipedia/commons/7/7f/Honda-logo.svg',
            'MG' => 'https://upload.wikimedia.org/wikipedia/commons/4/4a/MG_logo.svg',
            'Volkswagen' => 'https://upload.wikimedia.org/wikipedia/commons/7/75/Volkswagen_logo.svg',
            'Renault' => 'https://upload.wikimedia.org/wikipedia/commons/9/90/Renault_2021_logo.svg',
            'Mercedes-Benz' => 'https://upload.wikimedia.org/wikipedia/commons/9/90/Mercedes-Benz_logo.svg',
            'Land Rover' => 'https://upload.wikimedia.org/wikipedia/commons/8/8b/Land_Rover_logo.svg',
            'Nissan' => 'https://upload.wikimedia.org/wikipedia/commons/4/4e/Nissan_logo.svg',
            'BYD' => 'https://upload.wikimedia.org/wikipedia/commons/7/78/BYD_logo.svg',
            'Citroen' => 'https://upload.wikimedia.org/wikipedia/commons/4/4b/Citro%C3%ABn_logo.svg',
            'VinFast' => 'https://upload.wikimedia.org/wikipedia/commons/9/92/VinFast_logo.svg',
            'Jeep' => 'https://upload.wikimedia.org/wikipedia/commons/0/00/Jeep_logo.svg',
            'Audi' => 'https://upload.wikimedia.org/wikipedia/commons/6/6f/Audi_logo.svg',
            'Porsche' => 'https://upload.wikimedia.org/wikipedia/commons/1/15/Porsche_Logo.svg',
            'Volvo' => 'https://upload.wikimedia.org/wikipedia/commons/8/85/Volvo_Iron_Mark.svg',
            'Lexus' => 'https://upload.wikimedia.org/wikipedia/commons/6/6c/Lexus_division_emblem.svg',
            'Fiat' => 'https://upload.wikimedia.org/wikipedia/commons/1/1a/FIAT_logo.svg',
            'Lamborghini' => 'https://upload.wikimedia.org/wikipedia/commons/2/24/Lamborghini_Logo.svg',
            'Mini' => 'https://upload.wikimedia.org/wikipedia/commons/9/92/MINI_logo.svg',
            'Force Motors' => 'https://upload.wikimedia.org/wikipedia/commons/3/35/Force_Motors_logo.svg',
            'Jaguar' => 'https://upload.wikimedia.org/wikipedia/commons/2/2f/Jaguar_logo.svg',
            'Ferrari' => 'https://upload.wikimedia.org/wikipedia/commons/3/32/Ferrari_Logo.svg',
            'JSW' => 'https://upload.wikimedia.org/wikipedia/commons/8/84/JSW_logo.svg',
        ];
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($brands as $brand)
            @php
                $slug = preg_replace('/[^a-z0-9]+/','-', strtolower($brand));
                $localPath = public_path('images/brands/' . $slug . '.svg');
                $logo = file_exists($localPath)
                    ? asset('images/brands/' . $slug . '.svg')
                    : ($brandLogos[$brand] ?? '/images/placeholder.jpg');
            @endphp
            <a href="{{ route('vehicles.browse', ['brand' => $brand]) }}" class="group bg-white rounded-2xl border-2 border-gray-200 p-5 text-center hover:border-blue-500 hover:shadow-lg transition-all duration-300">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-white flex items-center justify-center group-hover:scale-110 transition-transform border border-blue-100 overflow-hidden">
                    <img src="{{ $logo }}" alt="{{ $brand }} logo" class="w-full h-full object-contain p-1" loading="lazy">
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
