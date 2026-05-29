@extends('layouts.app')

@section('title', 'Browse All Vehicles')
@section('subtitle', 'Find Your Perfect Car')

@section('content')

<div class="mb-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Filters Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24 space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🔍 Filters</h3>
                </div>

                <!-- Brand Filter -->
                <div class="border-b pb-4">
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Brand</label>
                    <div class="space-y-2">
                        @php
                            $brands = [
                                'Maruti Suzuki', 'Hyundai', 'Mahindra', 'Tata', 'Honda', 'Toyota',
                                'Ford', 'Volkswagen', 'Kia', 'Skoda', 'Chevrolet', 'Renault'
                            ];
                        @endphp
                        @foreach($brands as $brand)
                            <label class="flex items-center gap-2 cursor-pointer hover:text-blue-600 transition">
                                <input type="checkbox" class="w-4 h-4 text-blue-600 rounded" value="{{ $brand }}">
                                <span class="text-sm text-gray-700">{{ $brand }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Filter -->
                <div class="border-b pb-4">
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Price Range</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="price" class="w-4 h-4 text-blue-600" value="all" checked>
                            <span class="text-sm text-gray-700">All Prices</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="price" class="w-4 h-4 text-blue-600" value="0-500">
                            <span class="text-sm text-gray-700">Nu. 0 - 500K</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="price" class="w-4 h-4 text-blue-600" value="500-1000">
                            <span class="text-sm text-gray-700">Nu. 500K - 1M</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="price" class="w-4 h-4 text-blue-600" value="1000-2000">
                            <span class="text-sm text-gray-700">Nu. 1M - 2M</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="price" class="w-4 h-4 text-blue-600" value="2000+">
                            <span class="text-sm text-gray-700">Nu. 2M+</span>
                        </label>
                    </div>
                </div>

                <!-- Year Filter -->
                <div class="border-b pb-4">
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Year</label>
                    <select class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none text-sm">
                        <option value="">Any Year</option>
                        @for($i = date('Y'); $i >= date('Y') - 20; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Fuel Type -->
                <div class="border-b pb-4">
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Fuel Type</label>
                    <div class="space-y-2">
                        @foreach(['Petrol', 'Diesel', 'Hybrid', 'Electric'] as $fuel)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 text-blue-600 rounded">
                                <span class="text-sm text-gray-700">{{ $fuel }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Reset Filters -->
                <button class="w-full px-4 py-2 border-2 border-gray-300 hover:border-blue-600 text-gray-900 font-semibold rounded-lg transition">
                    Reset Filters
                </button>
            </div>
        </div>

        <!-- Vehicles Grid -->
        <div class="lg:col-span-3">
            <!-- Sort Options -->
            <div class="mb-6 flex items-center justify-between bg-white rounded-xl p-4 shadow">
                <span class="font-semibold text-gray-900">Showing 24 vehicles</span>
                <select class="px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none">
                    <option>Sort by: Newest</option>
                    <option>Sort by: Price (Low to High)</option>
                    <option>Sort by: Price (High to Low)</option>
                    <option>Sort by: Most Popular</option>
                </select>
            </div>

            <!-- Vehicles Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($vehicles as $vehicle)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all group overflow-hidden">
                        <!-- Image -->
                        <div class="relative h-64 bg-gray-300 overflow-hidden">
                            @if($vehicle->images && count($vehicle->images) > 0)
                                <img src="{{ asset('storage/' . $vehicle->images[0]) }}" 
                                     alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                                    <span class="text-gray-600 font-semibold">No Image</span>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                ✓ Available
                            </div>

                            <!-- Image Count -->
                            @if($vehicle->images && count($vehicle->images) > 1)
                                <div class="absolute top-4 right-4 bg-black/70 text-white px-3 py-1 rounded-lg text-xs font-semibold">
                                    📷 {{ count($vehicle->images) }} Photos
                                </div>
                            @endif

                            <!-- Video Badge -->
                            @if($vehicle->videos && count($vehicle->videos) > 0)
                                <div class="absolute bottom-4 left-4 bg-blue-600 text-white px-3 py-1 rounded-lg text-xs font-semibold">
                                    🎬 Video Available
                                </div>
                            @endif
                        </div>

                        <!-- Vehicle Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                            
                            <!-- Specs -->
                            <div class="grid grid-cols-3 gap-2 mb-4 pb-4 border-b text-sm text-gray-600">
                                <div class="text-center">
                                    <div class="font-semibold">{{ $vehicle->year }}</div>
                                    <div class="text-xs">Year</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold">{{ $vehicle->mileage ?? 0 }} km</div>
                                    <div class="text-xs">Mileage</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold">{{ $vehicle->fuel_type ?? 'Petrol' }}</div>
                                    <div class="text-xs">Fuel</div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="mb-4">
                                <p class="text-2xl font-bold text-blue-600">Nu. {{ number_format($vehicle->price) }}</p>
                                <p class="text-xs text-gray-500">Asking Price</p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <a href="{{ route('vehicles.show', $vehicle->id) }}" 
                                   class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-center">
                                    View Details
                                </a>
                                @auth
                                    <button onclick="bookTestDrive({{ $vehicle->id }})" 
                                            class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition">
                                        📅 Book
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 bg-white rounded-2xl shadow-lg p-12 text-center">
                        <p class="text-gray-600 text-lg">No vehicles found matching your criteria</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($vehicles->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $vehicles->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function bookTestDrive(vehicleId) {
    window.location.href = `/bookings/create/${vehicleId}`;
}
</script>

@endsection
