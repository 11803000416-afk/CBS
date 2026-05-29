@extends('layouts.app')

@section('title', 'Buyers')
@section('subtitle', 'Manage buyer information and contacts')

@section('content')
<style>
    .buyer-card {
        animation: slideUp 0.6s ease-out;
        transition: all 0.3s ease;
    }

    .buyer-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(2, 132, 199, 0.2);
    }

    .stat-badge {
        animation: scaleIn 0.5s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

<!-- Header Section -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Left: Stats -->
        <div class="flex gap-4">
            <div class="stat-badge p-4 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-xl border border-blue-500/30 backdrop-blur-xl">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-500/30 rounded-lg">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Total Buyers</p>
                        <p class="text-2xl font-bold text-white">{{ $buyers->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Add Button -->
        <a href="{{ route('buyers.create') }}" class="flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white px-6 py-3 rounded-lg transition-all font-semibold shadow-lg hover:shadow-blue-500/50 hover:shadow-xl transform hover:scale-105 active:scale-95 whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Buyer
        </a>
    </div>
</div>

<!-- Brand Selection Section -->
<div class="bg-white rounded-3xl shadow-xl p-8 mb-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-1">Filter by Brand</h2>
        <p class="text-gray-600">Browse buyers interested in specific brands</p>
    </div>

    <!-- Brands Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @php
            $brands = [
                ['name' => 'Maruti Suzuki', 'icon' => '🚗'],
                ['name' => 'Tata', 'icon' => '🚙'],
                ['name' => 'Mahindra', 'icon' => '🚕'],
                ['name' => 'Hyundai', 'icon' => '⚙️'],
                ['name' => 'Toyota', 'icon' => '🏎️'],
                ['name' => 'Kia', 'icon' => '🚓'],
                ['name' => 'BMW', 'icon' => '🛞'],
                ['name' => 'Skoda', 'icon' => '🚗'],
                ['name' => 'Honda', 'icon' => '🚙'],
                ['name' => 'MG', 'icon' => '🚕'],
                ['name' => 'Volkswagen', 'icon' => '⚙️'],
                ['name' => 'Renault', 'icon' => '🏎️'],
                ['name' => 'Mercedes-Benz', 'icon' => '🛞'],
                ['name' => 'Land Rover', 'icon' => '🚗'],
                ['name' => 'Nissan', 'icon' => '🚙'],
                ['name' => 'BYD', 'icon' => '🚕'],
                ['name' => 'Citroen', 'icon' => '⚙️'],
                ['name' => 'VinFast', 'icon' => '🏎️'],
                ['name' => 'Jeep', 'icon' => '🛞'],
                ['name' => 'Audi', 'icon' => '🚗'],
                ['name' => 'Porsche', 'icon' => '🚙'],
                ['name' => 'Volvo', 'icon' => '🚕'],
                ['name' => 'Lexus', 'icon' => '⚙️'],
                ['name' => 'Fiat', 'icon' => '🏎️'],
                ['name' => 'Lamborghini', 'icon' => '🛞'],
                ['name' => 'Mini', 'icon' => '🚗'],
                ['name' => 'Force Motors', 'icon' => '🚙'],
                ['name' => 'Jaguar', 'icon' => '🚕'],
                ['name' => 'Ferrari', 'icon' => '⚙️'],
                ['name' => 'JSW', 'icon' => '🏎️'],
            ];
        @endphp

        @foreach($brands as $brand)
            <div class="group bg-white border-2 border-gray-200 rounded-2xl p-6 text-center hover:border-blue-600 hover:shadow-lg transition-all duration-300">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-blue-50 flex items-center justify-center group-hover:scale-110 transition-transform border border-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13l1.5-4.5A2 2 0 016.4 7h11.2a2 2 0 011.9 1.5L21 13m-18 0h18m-18 0v4a1 1 0 001 1h1m16-5v4a1 1 0 01-1 1h-1m-14 0a2 2 0 104 0m10 0a2 2 0 104 0M6 13h12" />
                    </svg>
                </div>
                <p class="font-bold text-gray-800 text-sm group-hover:text-blue-600 transition-colors">{{ $brand['name'] }}</p>
            </div>
        @endforeach
    </div>
</div>

<!-- Buyers Grid/Table -->
@if($buyers->count() > 0)
    <!-- Desktop Table View -->
    <div class="hidden md:block">
        <div class="glass-card rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-600/20 to-cyan-600/20 border-b border-white/10">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-200">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Name
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-200">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948.684l1.498 7.492a1 1 0 00.502.756l4.618 2.311a1 1 0 001.097-.126l2.82-2.82a1 1 0 011.412 0l2.82 2.82a1 1 0 001.126 1.097l2.311 4.618a1 1 0 00.756.502l7.492 1.498a1 1 0 00.684.948v3.28a2 2 0 01-2 2h-1C9.716 20 3 13.284 3 5z"/>
                                    </svg>
                                    Phone
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-200">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Email
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-200">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Status
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-200">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach($buyers as $buyer)
                            <tr class="hover:bg-white/5 transition-colors duration-200 border-b border-white/5">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center font-semibold text-white text-sm">
                                            {{ strtoupper(substr($buyer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white">{{ $buyer->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-300">{{ $buyer->phone }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ $buyer->email ?: '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($buyer->status === 'active')
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-300 border border-green-500/30">
                                            <span class="w-2 h-2 rounded-full bg-green-400"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-gray-500/20 text-gray-300 border border-gray-500/30">
                                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('buyers.edit', $buyer) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-500/20 text-blue-300 hover:bg-blue-500/30 transition-all font-medium text-sm border border-blue-500/30 hover:border-blue-500/50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('buyers.destroy', $buyer) }}" onsubmit="return confirm('Are you sure you want to delete this buyer?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-red-500/20 text-red-300 hover:bg-red-500/30 transition-all font-medium text-sm border border-red-500/30 hover:border-red-500/50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden grid gap-4">
        @foreach($buyers as $buyer)
            <div class="buyer-card glass-card p-6 rounded-xl border border-white/10">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center font-semibold text-white">
                            {{ strtoupper(substr($buyer->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ $buyer->name }}</p>
                            @if($buyer->status === 'active')
                                <span class="inline-flex items-center gap-1 text-xs text-green-300">
                                    <span class="w-2 h-2 rounded-full bg-green-400"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs text-gray-300">
                                    <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-2 mb-4 text-sm">
                    <p class="flex items-center gap-2 text-gray-300">
                        <svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948.684l1.498 7.492a1 1 0 00.502.756l4.618 2.311a1 1 0 001.097-.126l2.82-2.82a1 1 0 011.412 0l2.82 2.82a1 1 0 001.126 1.097l2.311 4.618a1 1 0 00.756.502l7.492 1.498a1 1 0 00.684.948v3.28a2 2 0 01-2 2h-1C9.716 20 3 13.284 3 5z"/>
                        </svg>
                        {{ $buyer->phone }}
                    </p>
                    @if($buyer->email)
                        <p class="flex items-center gap-2 text-gray-300 break-all">
                            <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $buyer->email }}
                        </p>
                    @endif
                </div>

                <div class="flex gap-2 pt-4 border-t border-white/10">
                    <a href="{{ route('buyers.edit', $buyer) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-blue-500/20 text-blue-300 hover:bg-blue-500/30 transition-all font-medium text-sm border border-blue-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('buyers.destroy', $buyer) }}" onsubmit="return confirm('Are you sure?')" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-red-500/20 text-red-300 hover:bg-red-500/30 transition-all font-medium text-sm border border-red-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $buyers->links() }}
    </div>

@else
    <!-- Empty State -->
    <div class="glass-card rounded-2xl p-12 border border-white/10 text-center">
        <div class="p-4 bg-blue-500/20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"/>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">No Buyers Found</h3>
        <p class="text-gray-400 mb-6">Start by adding your first buyer to the system.</p>
        <a href="{{ route('buyers.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white px-6 py-3 rounded-lg transition-all font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add First Buyer
        </a>
    </div>
@endif
@endsection
