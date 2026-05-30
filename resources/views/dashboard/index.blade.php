@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome to your Car Broker System')

@section('content')
<!-- Premium Header Section - BHUTANESE COLOR SCHEME -->
<div class="dashboard-hero bg-gradient-to-r from-emerald-600 via-amber-500 to-red-600 text-white sm:px-8 shimmer will-animate">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.16),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(255,255,255,0.10),_transparent_28%)]"></div>
    <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-3xl">
            <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-green-50 backdrop-blur">
                CBS Overview
            </div>
            <h1 class="mb-3 text-3xl font-bold leading-tight sm:text-4xl lg:text-5xl">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="max-w-2xl text-sm text-green-100 sm:text-base lg:text-lg">
                @if(auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN))
                    System Administrator - Full system overview and analytics
                @elseif(auth()->user()->hasRole(\App\Models\User::ROLE_BROKER))
                    Car Broker - Manage your listings and transactions
                @else
                    Seller - Monitor your vehicle listings and inquiries
                @endif
            </p>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:min-w-[360px]">
                <div class="rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur will-animate animation-fadeIn">
                <p class="text-xs font-semibold uppercase tracking-wide text-green-100">Status</p>
                <p class="mt-1 text-lg font-bold">Active</p>
                <p class="text-xs text-green-100">Secure session live</p>
            </div>
                <div class="rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur will-animate animation-fadeIn">
                <p class="text-xs font-semibold uppercase tracking-wide text-green-100">Role</p>
                <p class="mt-1 text-lg font-bold capitalize">{{ auth()->user()->role }}</p>
                <p class="text-xs text-green-100">Personalized access</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid - BHUTANESE COLOR SCHEME -->
<div class="grid grid-cols-1 gap-5 md:grid-cols-2 {{ auth()->user()->hasRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_BROKER]) ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} lg:gap-6 mb-8">
    <!-- Stat Card 1 - Total Vehicles (GREEN) -->
    <div class="stat-card-modern dashboard-card-hover will-animate animation-fadeIn rounded-2xl p-6 shadow-lg border border-emerald-200/50 backdrop-blur-md bg-gradient-to-br from-emerald-50 via-emerald-50 to-emerald-100">
        <div class="relative z-10">
            <div>
                <p class="text-emerald-700 text-sm font-semibold mb-2 uppercase tracking-wide">Total Vehicles</p>
                <p class="text-5xl font-bold text-emerald-900 mb-2">{{ $stats['vehicles'] ?? 0 }}</p>
                <p class="text-emerald-600 text-xs font-medium">Across all listings</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 - Available Vehicles (LIME GREEN) -->
    <div class="stat-card-modern dashboard-card-hover will-animate animation-fadeIn rounded-2xl p-6 shadow-lg border border-lime-200/50 backdrop-blur-md bg-gradient-to-br from-lime-50 via-lime-50 to-lime-100">
        <div class="relative z-10">
            <div>
                <p class="text-lime-700 text-sm font-semibold mb-2 uppercase tracking-wide">Available</p>
                <p class="text-5xl font-bold text-lime-900 mb-2">{{ $stats['available_vehicles'] ?? 0 }}</p>
                <p class="text-lime-600 text-xs font-medium">Ready for sale</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-lime-400 to-lime-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 - Sold Vehicles (ORANGE = Nu Currency) -->
    <div class="stat-card-modern dashboard-card-hover will-animate animation-fadeIn rounded-2xl p-6 shadow-lg border border-amber-200/50 backdrop-blur-md bg-gradient-to-br from-amber-50 via-amber-50 to-amber-100">
        <div class="relative z-10">
            <div>
                <p class="text-amber-700 text-sm font-semibold mb-2 uppercase tracking-wide">Sold (Nu.)</p>
                <p class="text-5xl font-bold text-amber-900 mb-2">{{ $stats['sold_vehicles'] ?? 0 }}</p>
                <p class="text-amber-600 text-xs font-medium">Completed sales</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                        <span class="text-xl font-black tracking-tight text-white">NU</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 4 - Total Buyers (Admin/Broker only - RED) -->
    @if(auth()->user()->hasRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_BROKER]))
    <div class="stat-card-modern dashboard-card-hover will-animate animation-fadeIn rounded-2xl p-6 shadow-lg border border-red-200/50 backdrop-blur-md bg-gradient-to-br from-red-50 via-red-50 to-red-100">
        <div class="relative z-10">
            <div>
                <p class="text-red-700 text-sm font-semibold mb-2 uppercase tracking-wide">Buyers</p>
                <p class="text-5xl font-bold text-red-900 mb-2">{{ $stats['buyers'] ?? 0 }}</p>
                <p class="text-red-600 text-xs font-medium">Active buyers</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"/>
                </svg>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Quick Actions -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @if(auth()->user()->hasRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_BROKER]))
            <a href="{{ route('vehicles.create') }}" class="dashboard-card dashboard-card-hover group min-h-[148px] text-center hover:bg-blue-50">
                <div class="flex flex-col items-center justify-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">Add Vehicle</span>
                    <span class="text-xs text-gray-500">Create a new listing</span>
                </div>
            </a>
        @else
            <a href="{{ route('my-vehicles.create') }}" class="dashboard-card dashboard-card-hover group min-h-[148px] text-center hover:bg-blue-50">
                <div class="flex flex-col items-center justify-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">List My Car</span>
                    <span class="text-xs text-gray-500">Start a private listing</span>
                </div>
            </a>
        @endif
        
        <a href="{{ route('inquiries.index') }}" class="dashboard-card dashboard-card-hover group min-h-[148px] text-center hover:bg-amber-50">
            <div class="flex flex-col items-center justify-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-900">Inquiries</span>
                <span class="text-xs text-gray-500">Respond to messages</span>
            </div>
        </a>
        
        @if(auth()->user()->hasRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_BROKER]))
            <a href="{{ route('transactions.index') }}" class="dashboard-card dashboard-card-hover group min-h-[148px] text-center hover:bg-emerald-50">
                <div class="flex flex-col items-center justify-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">Transactions</span>
                    <span class="text-xs text-gray-500">Track sales activity</span>
                </div>
            </a>
            
            <a href="{{ route('reports.index') }}" class="dashboard-card dashboard-card-hover group min-h-[148px] text-center hover:bg-purple-50">
                <div class="flex flex-col items-center justify-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">Reports</span>
                    <span class="text-xs text-gray-500">View analytics</span>
                </div>
            </a>
        @endif
    </div>
</div>

<!-- Professional Analytics Section -->
@if(auth()->user()->hasRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_BROKER]))
<div class="analytics-header">
    <h2>📊 System Analytics</h2>
    <p>Real-time insights and performance metrics</p>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Monthly Sales Chart -->
    <div class="chart-container will-animate animation-fadeIn">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span style="color: #7EC850;">📈</span> Monthly Sales Trend (Nu.)
        </h3>
        <canvas id="salesChart" height="300"></canvas>
        <div class="chart-legend">
            <div class="chart-legend-item">
                <div class="chart-legend-color" style="background-color: #7EC850;"></div>
                <span>Sales Revenue</span>
            </div>
            <div class="chart-legend-item">
                <div class="chart-legend-color" style="background-color: #FF9E1B;"></div>
                <span>Commission</span>
            </div>
        </div>
    </div>

    <!-- Vehicle Status Chart -->
    <div class="chart-container will-animate animation-fadeIn">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span style="color: #D4E837;">🎯</span> Vehicle Status Distribution
        </h3>
        <canvas id="statusChart" height="300"></canvas>
        <div class="chart-legend">
            <div class="chart-legend-item">
                <div class="chart-legend-color" style="background-color: #7EC850;"></div>
                <span>Available</span>
            </div>
            <div class="chart-legend-item">
                <div class="chart-legend-color" style="background-color: #D4E837;"></div>
                <span>Pending Approval</span>
            </div>
            <div class="chart-legend-item">
                <div class="chart-legend-color" style="background-color: #FF9E1B;"></div>
                <span>Sold</span>
            </div>
            <div class="chart-legend-item">
                <div class="chart-legend-color" style="background-color: #FF4757;"></div>
                <span>Inactive</span>
            </div>
        </div>
    </div>
</div>

<!-- Activity Timeline Section -->
<div class="mb-8">
    <h3 class="text-2xl font-bold text-gray-900 mb-5 flex items-center gap-2">
        <span style="color: #FF4757;">⏱️</span> Recent Activity
    </h3>
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
        <div class="timeline">
            <div class="timeline-item success">
                <div class="timeline-content">
                    <div class="timeline-title">Vehicle Listing Approved</div>
                    <div class="timeline-description">
                        <span class="text-green-600 font-semibold">2024 BMW X5</span> has been approved and is now publicly visible.
                    </div>
                    <div class="timeline-time">Today at 2:45 PM</div>
                </div>
            </div>

            <div class="timeline-item info">
                <div class="timeline-content">
                    <div class="timeline-title">New Inquiry Received</div>
                    <div class="timeline-description">
                        <span class="text-blue-600 font-semibold">John Dorji</span> has inquired about <span class="text-blue-600 font-semibold">2023 Toyota Corolla</span>.
                    </div>
                    <div class="timeline-time">Today at 1:15 PM</div>
                </div>
            </div>

            <div class="timeline-item warning">
                <div class="timeline-content">
                    <div class="timeline-title">Commission Transaction</div>
                    <div class="timeline-description">
                        <span class="text-amber-600 font-semibold">Nu. 15,000</span> commission earned from vehicle sale #T-2024-1205.
                    </div>
                    <div class="timeline-time">Yesterday at 4:30 PM</div>
                </div>
            </div>

            <div class="timeline-item danger">
                <div class="timeline-content">
                    <div class="timeline-title">Pending Review</div>
                    <div class="timeline-description">
                        <span class="text-red-600 font-semibold">2024 Mercedes C-Class</span> requires admin approval. 3 images uploaded.
                    </div>
                    <div class="timeline-time">2 days ago</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script for Charts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart')?.getContext('2d');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Sales Revenue (Nu.)',
                        data: [45000, 52000, 61000, 58000, 73000, 82000, 76000, 89000, 95000, 104000, 115000, 125000],
                        borderColor: '#7EC850',
                        backgroundColor: 'rgba(126, 200, 80, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#7EC850',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Commission (Nu.)',
                        data: [4500, 5200, 6100, 5800, 7300, 8200, 7600, 8900, 9500, 10400, 11500, 12500],
                        borderColor: '#FF9E1B',
                        backgroundColor: 'rgba(255, 158, 27, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#FF9E1B',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(200, 200, 200, 0.1)'
                        },
                        title: {
                            display: true,
                            text: 'Amount (Nu.)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(200, 200, 200, 0.1)'
                        }
                    }
                }
            }
        });
    }

    // Status Chart
    const statusCtx = document.getElementById('statusChart')?.getContext('2d');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Available', 'Pending Approval', 'Sold', 'Inactive'],
                datasets: [{
                    data: [35, 12, 28, 5],
                    backgroundColor: [
                        '#7EC850',
                        '#D4E837',
                        '#FF9E1B',
                        '#FF4757'
                    ],
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
});
</script>
@endif

<!-- Rest of dashboard content -->
    <div class="lg:col-span-2">
        <x-premium-card title="Welcome, {{ auth()->user()->name }}!" class="h-full">
            <p class="text-slate-600 mb-6">You are logged in as a <span class="text-cyan-700 font-semibold capitalize">{{ auth()->user()->role }}</span></p>

            <div class="space-y-4">
                <div class="summary-tile">
                    <p class="text-sm font-semibold text-slate-700 mb-1">System Status</p>
                    <div class="flex items-center gap-2">
                        <div class="h-2.5 w-2.5 rounded-full bg-emerald-500 shadow-[0_0_0_4px_rgba(16,185,129,0.15)]"></div>
                        <p class="text-sm text-slate-600">All systems operational</p>
                    </div>
                </div>

                <div class="summary-tile">
                    <p class="text-sm font-semibold text-slate-700 mb-1">Last Login</p>
                    <p class="text-sm text-slate-600">Today at this time</p>
                </div>
            </div>
        </x-premium-card>
    </div>
    
    <!-- Available Vehicles Section -->
    <div class="lg:col-span-3">
        <x-premium-card title="Available Vehicles" class="h-full">
            <div class="space-y-4">
                @if(($approvedVehicles ?? collect())->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach(($approvedVehicles ?? collect()) as $vehicle)
                            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-xl transition-all hover:border-cyan-200 group shadow-sm">
                                <!-- Vehicle Card Container -->
                                <div class="relative">
                                    <!-- Image Section -->
                                    <div class="h-48 sm:h-52 lg:h-56 bg-gray-100 relative overflow-hidden cursor-pointer" 
                                         onclick="openLightbox({{ $vehicle->id }}, 0)">
                                        @if($vehicle->images && count($vehicle->images) > 0)
                                            <!-- Main Image -->
                                            <img id="main-image-{{ $vehicle->id }}" 
                                                 src="{{ asset('storage/' . $vehicle->images[0]) }}" 
                                                 alt="{{ $vehicle->brand }} {{ $vehicle->model }}" 
                                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                                 loading="lazy"
                                                 decoding="async">
                                            
                                            <!-- Image Counter & Navigation -->
                                            @if(count($vehicle->images) > 1)
                                                <div class="absolute top-3 right-3 bg-black bg-opacity-60 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                    {{ count($vehicle->images) }} photos
                                                </div>
                                                <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex gap-2">
                                                    @foreach($vehicle->images as $index => $image)
                                                        <button onclick="changeDashboardImage({{ $vehicle->id }}, {{ $index }}); event.stopPropagation();" 
                                                                    class="w-2 h-2 rounded-full border-2 border-white {{ $index === 0 ? 'bg-blue-600' : 'bg-gray-400' }} hover:bg-blue-700 transition-all shadow-md">
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            <!-- View Full Size Button -->
                                            <div class="absolute top-3 left-3 bg-black bg-opacity-60 text-white px-2 py-1 rounded-full text-xs font-medium hover:bg-opacity-80 transition">
                                                <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 2h12M4 16v4M4 16h12"/>
                                                </svg>
                                                Full Size
                                            </div>
                                        </div>
                                    @else
                                        <div class="h-48 sm:h-52 lg:h-56 bg-gradient-to-br from-cyan-50 to-slate-100 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Vehicle Details -->
                                    <div class="p-4 sm:p-5">
                                        <!-- Title and Status -->
                                        <div class="flex items-start justify-between mb-3 gap-3">
                                            <div class="flex-1">
                                                <h4 class="font-bold text-slate-900 text-base sm:text-lg">{{ $vehicle->brand }} <span class="text-cyan-600">{{ $vehicle->model }}</span></h4>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                        {{ $vehicle->status === 'available' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                                        {{ $vehicle->status === 'reserved' ? 'bg-cyan-100 text-cyan-700' : '' }}
                                                        {{ $vehicle->status === 'sold' ? 'bg-slate-100 text-slate-700' : '' }}">
                                                        {{ ucfirst($vehicle->status) }}
                                                    </span>
                                                    @if($vehicle->sellerRequest && $vehicle->sellerRequest->status === 'approved')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 ml-2">
                                                            ✅ Approved
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Price and Year -->
                                            <div class="text-right text-sm text-slate-600">
                                                <span class="block">{{ $vehicle->year }}</span>
                                                <span class="mt-1 inline-flex items-center rounded-full bg-slate-900 px-3 py-1.5 font-bold text-white">Nu. {{ number_format($vehicle->price) }}</span>
                                            </div>
                                            
                                            <!-- Approved Seller Badge -->
                                            @if($vehicle->sellerRequest && $vehicle->sellerRequest->status === 'approved')
                                                <div class="mt-3 p-2 bg-emerald-50 rounded-lg border border-emerald-200">
                                                    <p class="text-xs text-emerald-700 font-semibold">✅ Approved Seller</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 text-center">
                        <a href="{{ route('vehicles.index') }}" 
                           class="inline-flex items-center gap-2 text-cyan-600 hover:text-cyan-800 font-semibold text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            View All Vehicles
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500 font-semibold text-lg mb-2">No vehicles available</p>
                        <p class="text-gray-400 text-sm">Approved vehicles will appear here</p>
                    </div>
                @endif
            </div>
        </x-card>
    </div>
    
    <!-- Summary Stats -->
    <div>
        <x-premium-card title="Summary" class="h-full">
            <div class="space-y-4">
                <div class="summary-tile text-center">
                    <p class="text-slate-600 text-sm mb-1">Total Vehicles</p>
                    <p class="summary-tile-value text-cyan-600">{{ $stats['vehicles'] ?? 0 }}</p>
                </div>
                <div class="summary-tile text-center">
                    <p class="text-slate-600 text-sm mb-1">Pending Inquiries</p>
                    <p class="summary-tile-value text-slate-700">{{ $stats['pending_inquiries'] ?? 0 }}</p>
                </div>
                <div class="summary-tile text-center">
                    <p class="text-slate-600 text-sm mb-1">Recent Transactions</p>
                    <p class="summary-tile-value text-emerald-600">{{ $stats['transactions'] ?? 0 }}</p>
                </div>
            </div>
        </x-premium-card>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 z-50 hidden bg-black bg-opacity-90 flex items-center justify-center p-4">
    <div class="relative max-w-6xl max-h-[90vh] w-full">
        <!-- Close Button -->
        <button onclick="closeLightbox()" 
                class="absolute top-4 right-4 z-10 bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-800 rounded-full p-2 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        
        <!-- Image Container -->
        <div class="relative bg-white rounded-lg shadow-2xl overflow-hidden">
            <!-- Navigation Buttons -->
            <button onclick="navigateLightbox('prev')" 
                    class="absolute left-4 top-1/2 z-10 bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-800 rounded-full p-3 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7 7m0 0l-7 7m7 7h18"/>
                </svg>
            </button>
            
            <button onclick="navigateLightbox('next')" 
                    class="absolute right-4 top-1/2 z-10 bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-800 rounded-full p-3 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            
            <!-- Image -->
            <img id="lightbox-img" 
                 src="" 
                 alt="" 
                 class="max-w-full max-h-[70vh] object-contain">
            
            <!-- Caption and Counter -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-black p-4 text-white">
                <div class="flex items-center justify-between">
                    <p id="lightbox-caption" class="text-sm font-medium"></p>
                    <p id="lightbox-counter" class="text-xs text-gray-300"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Store vehicle images data for carousel functionality
const vehicleImages = @json($approvedVehicles->map(function($vehicle) {
    return [
        'id' => $vehicle->id,
        'images' => $vehicle->images ?? []
    ];
}));

function changeDashboardImage(vehicleId, imageIndex) {
    const vehicle = vehicleImages.find(v => v.id === vehicleId);
    if (!vehicle || !vehicle.images || vehicle.images.length === 0) return;
    
    const mainImage = document.getElementById(`main-image-${vehicleId}`);
    const buttons = document.querySelectorAll(`[onclick^="changeDashboardImage(${vehicleId},"]`);
    
    // Update main image
    mainImage.src = `/storage/${vehicle.images[imageIndex]}`;
    
    // Update button states
    buttons.forEach((button, index) => {
        if (index === imageIndex) {
            button.classList.remove('bg-gray-400');
            button.classList.add('bg-blue-600');
        } else {
            button.classList.remove('bg-blue-600');
            button.classList.add('bg-gray-400');
        }
    });
}

// Auto-rotate images every 3 seconds
document.addEventListener('DOMContentLoaded', function() {
    vehicleImages.forEach(vehicle => {
        if (vehicle.images.length > 1) {
            setInterval(() => {
                const currentImageIndex = getCurrentImageIndex(vehicle.id);
                const nextIndex = (currentImageIndex + 1) % vehicle.images.length;
                changeDashboardImage(vehicle.id, nextIndex);
            }, 3000); // Change every 3 seconds
        }
    });
});

function getCurrentImageIndex(vehicleId) {
    const mainImage = document.getElementById(`main-image-${vehicleId}`);
    const vehicle = vehicleImages.find(v => v.id === vehicleId);
    if (!vehicle || !mainImage) return 0;
    
    const currentSrc = mainImage.src;
    return vehicle.images.findIndex(img => currentSrc.includes(img));
}

function openLightbox(vehicleId, imageIndex) {
    const vehicle = vehicleImages.find(v => v.id === vehicleId);
    if (!vehicle || !vehicle.images || vehicle.images.length === 0) return;
    
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxCaption = document.getElementById('lightbox-caption');
    const lightboxCounter = document.getElementById('lightbox-counter');
    
    // Set image
    lightboxImg.src = `/storage/${vehicle.images[imageIndex]}`;
    lightboxCaption.textContent = `${vehicle.brand} ${vehicle.model} - Image ${imageIndex + 1} of ${vehicle.images.length}`;
    lightboxCounter.textContent = `${imageIndex + 1} / ${vehicle.images.length}`;
    
    // Show lightbox
    lightbox.classList.remove('hidden');
    lightbox.classList.add('flex');
    
    // Store current vehicle and image index
    window.currentLightboxVehicle = vehicleId;
    window.currentLightboxImageIndex = imageIndex;
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.add('hidden');
    lightbox.classList.remove('flex');
    window.currentLightboxVehicle = null;
    window.currentLightboxImageIndex = null;
}

function navigateLightbox(direction) {
    if (window.currentLightboxVehicle === null) return;
    
    const vehicle = vehicleImages.find(v => v.id === window.currentLightboxVehicle);
    if (!vehicle || !vehicle.images || vehicle.images.length === 0) return;
    
    let newIndex = window.currentLightboxImageIndex;
    
    if (direction === 'next') {
        newIndex = (newIndex + 1) % vehicle.images.length;
    } else if (direction === 'prev') {
        newIndex = (newIndex - 1 + vehicle.images.length) % vehicle.images.length;
    }
    
    openLightbox(window.currentLightboxVehicle, newIndex);
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (window.currentLightboxVehicle === null) return;
    
    if (e.key === 'Escape') {
        closeLightbox();
    } else if (e.key === 'ArrowRight') {
        navigateLightbox('next');
    } else if (e.key === 'ArrowLeft') {
        navigateLightbox('prev');
    }
});
</script>
@endsection
