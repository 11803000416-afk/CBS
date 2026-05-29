@extends('layouts.app')

@section('title', 'Seller Requests')
@section('subtitle', 'Review and manage seller privilege requests from buyers')

@section('content')
<!-- Success Alert for Seller Request Approval -->
@if(session('success'))
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-xl border-2 border-green-200 shadow-2xl p-6 max-w-md w-full transform transition-all duration-500 ease-out animate-pulse pointer-events-auto">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 mb-2 text-lg">✅ Success!</h3>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ session('success') }}</p>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600 text-sm font-medium transition-colors">
                    Dismiss
                </button>
            </div>
        </div>
    </div>
@endif
<div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 sm:px-8 py-5 border-b-4 border-purple-800">
        <h3 class="font-bold text-white text-lg flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Seller Requests
        </h3>
    </div>
    
    <div class="p-6 sm:p-8">
        <!-- Filter Tabs -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('admin.seller-requests.index') }}" 
               class="px-4 py-2 rounded-lg font-medium text-sm transition {{ !request('status') ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All Requests
            </a>
                <a href="{{ route('admin.seller-requests.index', ['status' => 'pending']) }}" 
                    class="px-4 py-2 rounded-lg font-medium text-sm transition {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                     Pending ({{ $pendingSellerRequestsCount ?? 0 }})
            </a>
            <a href="{{ route('admin.seller-requests.index', ['status' => 'approved']) }}" 
               class="px-4 py-2 rounded-lg font-medium text-sm transition {{ request('status') === 'approved' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Approved
            </a>
            <a href="{{ route('admin.seller-requests.index', ['status' => 'rejected']) }}" 
               class="px-4 py-2 rounded-lg font-medium text-sm transition {{ request('status') === 'rejected' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Rejected
            </a>
        </div>

        <!-- Requests Table -->
        @if($requests->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($requests as $sellerRequest)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                                <span class="text-white font-bold text-sm">{{ strtoupper(substr($sellerRequest->user->name, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $sellerRequest->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $sellerRequest->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($sellerRequest->vehicle)
                                        <div class="text-sm text-gray-900">{{ $sellerRequest->vehicle->brand }} {{ $sellerRequest->vehicle->model }}</div>
                                        <div class="text-sm text-gray-500">{{ $sellerRequest->vehicle->year }} • Nu. {{ number_format($sellerRequest->vehicle->price) }}</div>
                                    @else
                                        <span class="text-sm text-gray-400">No vehicle</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $sellerRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $sellerRequest->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $sellerRequest->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($sellerRequest->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $sellerRequest->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.seller-requests.show', $sellerRequest) }}" 
                                       class="text-purple-600 hover:text-purple-900 mr-3">View</a>
                                    
                                    @if($sellerRequest->status === 'pending')
                                        <form method="POST" action="{{ route('admin.seller-requests.approve', $sellerRequest) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3" 
                                                    onclick="return confirm('Approve this seller request?')">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.seller-requests.reject', $sellerRequest) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                                    onclick="return confirm('Reject this seller request?')">
                                                Reject
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-700 font-bold text-lg mb-2">No seller requests found</p>
                <p class="text-gray-500 text-sm">Seller requests will appear here when buyers list their first vehicle.</p>
            </div>
        @endif
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $requests->links() }}
</div>
@endsection
