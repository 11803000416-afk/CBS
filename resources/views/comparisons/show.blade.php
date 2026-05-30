@extends('layouts.app')

@section('title', 'Compare Vehicles - CBS')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Vehicle Comparison</h1>
            <p class="text-lg text-gray-600">Compare and analyze vehicles side by side</p>
        </div>

        <!-- Comparison Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden overflow-x-auto">
            <table class="w-full">
                <!-- Spec Name Column -->
                <thead class="bg-gradient-to-r from-slate-900 to-slate-800 text-white sticky left-0 z-10">
                    <tr>
                        <th class="px-6 py-4 text-left font-bold min-w-[200px] bg-gradient-to-r from-slate-900 to-slate-800">Specification</th>
                    </tr>
                </thead>
                <tbody>
                    @php $specs = ['brand', 'model', 'year', 'price', 'mileage', 'transmission', 'fuel', 'color'] @endphp
                    @foreach($specs as $spec)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold text-gray-900 bg-gray-50 min-w-[200px]">
                                @switch($spec)
                                    @case('brand')
                                        Brand
                                        @break
                                    @case('model')
                                        Model
                                        @break
                                    @case('year')
                                        Year
                                        @break
                                    @case('price')
                                        Price
                                        @break
                                    @case('mileage')
                                        Mileage
                                        @break
                                    @case('transmission')
                                        Transmission
                                        @break
                                    @case('fuel')
                                        Fuel Type
                                        @break
                                    @case('color')
                                        Color
                                        @break
                                @endswitch
                            </td>
                            @foreach($vehicles as $vehicle)
                                <td class="px-6 py-4 text-center border-l">
                                    <span class="font-medium text-gray-900">
                                        @switch($spec)
                                            @case('price')
                                                {{ number_format($vehicle['price']) }}
                                                @break
                                            @case('mileage')
                                                {{ number_format($vehicle['mileage']) }} km
                                                @break
                                            @default
                                                {{ $vehicle[$spec] ?? 'N/A' }}
                                        @endswitch
                                    </span>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Visual Card Comparison -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($vehicles as $vehicle)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                    <!-- Image -->
                    <div class="h-48 bg-gray-200 relative">
                        @if(isset($vehicle['image']))
                            <img src="{{ asset('storage/' . $vehicle['image']) }}" alt="{{ $vehicle['brand'] }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $vehicle['brand'] }} {{ $vehicle['model'] }}</h3>
                        
                        <!-- Price Highlight -->
                        <div class="mb-4 p-4 bg-gradient-to-r from-cyan-50 to-blue-50 rounded-lg">
                            <p class="text-sm text-gray-600">Price</p>
                            <p class="text-2xl font-bold text-cyan-600">{{ number_format($vehicle['price']) }}</p>
                        </div>

                        <!-- Quick Stats -->
                        <div class="space-y-2 text-sm mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Year:</span>
                                <span class="font-medium text-gray-900">{{ $vehicle['year'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mileage:</span>
                                <span class="font-medium text-gray-900">{{ number_format($vehicle['mileage']) }} km</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transmission:</span>
                                <span class="font-medium text-gray-900">{{ $vehicle['transmission'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Fuel:</span>
                                <span class="font-medium text-gray-900">{{ $vehicle['fuel'] ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Action -->
                        <a href="/vehicles/{{ $vehicle['id'] }}" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition text-center">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Back Button -->
        <div class="mt-12 text-center">
            <a href="{{ route('comparisons.index') }}" class="inline-block px-8 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition">
                Back to Comparisons
            </a>
        </div>
    </div>
</div>
@endsection
