@extends('layouts.app')

@section('title', 'Broker License Requests')
@section('subtitle', 'Review dealer license approvals for broker access')

@section('content')
<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-gradient-to-r from-cyan-600 to-blue-700 px-6 py-4">
            <h2 class="text-lg font-bold text-white">Broker Dealer License Requests</h2>
        </div>

        <div class="p-6">
            <div class="mb-5 flex flex-wrap gap-2" role="tablist" aria-label="Filter broker request statuses">
                <a href="{{ route('admin.broker-licenses.index') }}" class="rounded-lg px-4 py-2 text-sm font-semibold {{ !request('status') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">All</a>
                <a href="{{ route('admin.broker-licenses.index', ['status' => 'pending']) }}" class="rounded-lg px-4 py-2 text-sm font-semibold {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-slate-100 text-slate-700' }}">Pending ({{ $pendingCount }})</a>
                <a href="{{ route('admin.broker-licenses.index', ['status' => 'approved']) }}" class="rounded-lg px-4 py-2 text-sm font-semibold {{ request('status') === 'approved' ? 'bg-green-600 text-white' : 'bg-slate-100 text-slate-700' }}">Approved</a>
                <a href="{{ route('admin.broker-licenses.index', ['status' => 'rejected']) }}" class="rounded-lg px-4 py-2 text-sm font-semibold {{ request('status') === 'rejected' ? 'bg-red-600 text-white' : 'bg-slate-100 text-slate-700' }}">Rejected</a>
            </div>

            @if($requests->count() === 0)
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-8 text-center">
                    <p class="text-base font-semibold text-slate-700">No broker license requests found.</p>
                    <p class="mt-1 text-sm text-slate-500">Broker submissions will appear here after login + license submission.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200" aria-label="Broker license requests table">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-600">Broker</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-600">License Number</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-600">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-600">Submitted</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-slate-600">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($requests as $broker)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm text-slate-800">
                                        <p class="font-semibold">{{ $broker->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $broker->email }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $broker->dealer_license_number ?: 'Not provided' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="badge {{ $broker->dealer_license_status === 'approved' ? 'badge-success' : ($broker->dealer_license_status === 'pending' ? 'badge-warning' : ($broker->dealer_license_status === 'rejected' ? 'badge-danger' : 'badge-primary')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $broker->dealer_license_status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-600">{{ optional($broker->dealer_license_submitted_at)->format('M d, Y H:i') ?: 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('admin.broker-licenses.show', $broker) }}" class="font-semibold text-blue-600 hover:text-blue-800">Review</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-5">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
