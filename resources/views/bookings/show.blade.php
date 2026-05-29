@extends('layouts.app')

@section('title', 'Booking Details')
@section('subtitle', 'View booking information')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Booking Header -->
    <div class="mb-8 bg-white border-2 border-gray-200 rounded-2xl p-6 shadow-lg">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b-2 border-gray-200">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="font-bold text-gray-900 text-lg">Booking Details</h3>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Vehicle Information -->
            <div>
                <h4 class="font-bold text-gray-900 text-lg mb-4">Vehicle Information</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-start gap-4">
                        @if($booking->vehicle->images && count($booking->vehicle->images) > 0)
                            <img src="{{ asset('storage/' . $booking->vehicle->images[0]) }}" 
                                 alt="{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}" 
                                 class="w-20 h-20 rounded-lg object-cover">
                        @else
                            <div class="w-20 h-20 rounded-lg bg-gray-200 flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h5 class="font-bold text-gray-900">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</h5>
                            <p class="text-sm text-gray-600">{{ $booking->vehicle->year }}</p>
                            <p class="text-lg font-bold text-green-600">Nu. {{ number_format($booking->total_amount) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Booking Information -->
            <div>
                <h4 class="font-bold text-gray-900 text-lg mb-4">Booking Information</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $booking->status_badge }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date:</span>
                        <span class="font-medium">{{ optional($booking->booking_date)->format('F d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Time:</span>
                        <span class="font-medium">{{ optional($booking->booking_time)->format('g:i A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duration:</span>
                        <span class="font-medium">{{ $booking->duration_hours }} hours</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-bold text-green-600">Nu. {{ number_format($booking->total_amount) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Messages -->
        @if($booking->buyer_message || $booking->seller_message)
            <div class="border-t-2 border-gray-200 pt-6 mt-6">
                <h4 class="font-bold text-gray-900 text-lg mb-4">Messages</h4>
                <div class="space-y-4">
                    @if($booking->buyer_message)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4-4 0 011-8 0zM4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-blue-900">{{ $booking->buyer->name }}</h5>
                                    <p class="text-sm text-blue-700">Buyer</p>
                                </div>
                            </div>
                            <p class="text-gray-700">{{ $booking->buyer_message }}</p>
                        </div>
                    @endif
                    
                    @if($booking->seller_message)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4-4 0 011-8 0zM4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-green-900">{{ $booking->seller->name }}</h5>
                                    <p class="text-sm text-green-700">Seller</p>
                                </div>
                            </div>
                            <p class="text-gray-700">{{ $booking->seller_message }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
