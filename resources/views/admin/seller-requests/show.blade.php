@extends('layouts.app')

@section('title', 'Seller Request Details')
@section('subtitle', 'Review seller privilege request details')

@section('content')
<div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 sm:px-8 py-5 border-b-4 border-purple-800">
        <h3 class="font-bold text-white text-lg flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Seller Request #{{ $sellerRequest->id }}
        </h3>
    </div>
    
    <div class="p-6 sm:p-8">
        <!-- Request Status Badge -->
        <div class="mb-6">
            <span class="inline-flex px-4 py-2 text-sm font-bold rounded-full
                {{ $sellerRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $sellerRequest->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                {{ $sellerRequest->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                {{ ucfirst($sellerRequest->status) }}
            </span>
            <span class="ml-3 text-sm text-gray-500">
                Requested on {{ $sellerRequest->created_at->format('M d, Y \a\t H:i') }}
            </span>
            @if($sellerRequest->reviewed_at)
                <span class="ml-3 text-sm text-gray-500">
                    Reviewed on {{ $sellerRequest->reviewed_at->format('M d, Y \a\t H:i') }}
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- User Information -->
            <div class="space-y-6">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        User Information
                    </h4>
                    
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-600">Name:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $sellerRequest->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-600">Email:</span>
                            <span class="text-sm text-gray-900">{{ $sellerRequest->user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-600">Phone:</span>
                            <span class="text-sm text-gray-900">{{ $sellerRequest->user->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-600">Current Role:</span>
                            <span class="text-sm font-semibold text-purple-600">{{ ucfirst($sellerRequest->user->role) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-600">Member Since:</span>
                            <span class="text-sm text-gray-900">{{ $sellerRequest->user->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Request Message -->
                @if($sellerRequest->user_message)
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            User Message
                        </h4>
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <p class="text-sm text-gray-700">{{ $sellerRequest->user_message }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Vehicle Information -->
            <div class="space-y-6">
                @if($sellerRequest->vehicle)
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1h-1m2-11H4m1 0h8M9 7h1"/>
                            </svg>
                            Listed Vehicle
                        </h4>
                        
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Vehicle:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $sellerRequest->vehicle->brand }} {{ $sellerRequest->vehicle->model }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Year:</span>
                                <span class="text-sm text-gray-900">{{ $sellerRequest->vehicle->year }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Price:</span>
                                <span class="text-sm font-semibold text-green-600">Nu. {{ number_format($sellerRequest->vehicle->price) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Mileage:</span>
                                <span class="text-sm text-gray-900">{{ number_format($sellerRequest->vehicle->mileage) }} km</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Status:</span>
                                <span class="text-sm font-semibold text-blue-600">{{ ucfirst($sellerRequest->vehicle->status) }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Admin Notes -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Admin Notes
                    </h4>
                    
                    @if($sellerRequest->admin_notes)
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                            <p class="text-sm text-gray-700">{{ $sellerRequest->admin_notes }}</p>
                            @if($sellerRequest->reviewer)
                                <p class="text-xs text-gray-500 mt-2">
                                    Added by {{ $sellerRequest->reviewer->name }} on {{ $sellerRequest->reviewed_at->format('M d, Y') }}
                                </p>
                            @endif
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-sm text-gray-400 italic">No admin notes added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($sellerRequest->status === 'pending')
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Approve Form -->
                    <form method="POST" action="{{ route('admin.seller-requests.approve', $sellerRequest) }}" class="flex-1">
                        @csrf
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <h5 class="font-semibold text-green-800 mb-3">Approve Request</h5>
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes (Optional)</label>
                                <textarea name="admin_notes" rows="3" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                          placeholder="Add notes about this approval...">{{ $sellerRequest->admin_notes }}</textarea>
                            </div>
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-4 py-2.5 rounded-lg transition-all font-bold flex items-center justify-center gap-2 shadow-lg hover:shadow-xl"
                                    onclick="return confirm('Are you sure you want to approve this seller request? This will upgrade the user to seller role.')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Approve Request
                            </button>
                        </div>
                    </form>

                    <!-- Reject Form -->
                    <form method="POST" action="{{ route('admin.seller-requests.reject', $sellerRequest) }}" class="flex-1">
                        @csrf
                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                            <h5 class="font-semibold text-red-800 mb-3">Reject Request</h5>
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                                <textarea name="admin_notes" rows="3" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                          placeholder="Please provide reason for rejection..." required>{{ $sellerRequest->admin_notes }}</textarea>
                            </div>
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-4 py-2.5 rounded-lg transition-all font-bold flex items-center justify-center gap-2 shadow-lg hover:shadow-xl"
                                    onclick="return confirm('Are you sure you want to reject this seller request?')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reject Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
