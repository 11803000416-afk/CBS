@extends('layouts.app')

@section('title', 'Simple Vehicles')

@section('content')
<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold mb-4">Simple Vehicles Test</h1>
    <p class="text-gray-600 mb-8">Total Vehicles: {{ $vehicles->count() }}</p>
    
    @if($vehicles->count() > 0)
        <div class="space-y-4">
            @foreach($vehicles as $vehicle)
                <div class="bg-white border rounded-lg p-4">
                    <h3 class="font-bold">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                    <p class="text-gray-600">{{ $vehicle->year }} - Nu. {{ number_format($vehicle->price) }}</p>
                    <p class="text-sm text-gray-500">{{ $vehicle->fuel_type ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">Seller: {{ $vehicle->seller?->name ?? 'Unknown' }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No vehicles found.</p>
    @endif
</div>
@endsection
