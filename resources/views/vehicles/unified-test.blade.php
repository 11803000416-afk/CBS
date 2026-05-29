@extends('layouts.app')

@section('title', 'Vehicles Test')

@section('content')
<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold mb-4">Unified Vehicles Test</h1>
    <p class="text-gray-600 mb-8">Total Vehicles: {{ $vehicles->count() }}</p>
    
    @if($vehicles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($vehicles as $vehicle)
                <div class="bg-white border rounded-lg p-4">
                    <h3 class="font-bold">{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                    <p class="text-gray-600">{{ $vehicle->year }} - Nu. {{ number_format($vehicle->price) }}</p>
                    <p class="text-sm text-gray-500">{{ $vehicle->fuel_type ?? 'N/A' }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No vehicles found.</p>
    @endif
</div>
@endsection
