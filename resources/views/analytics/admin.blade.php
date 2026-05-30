@extends('layouts.app')

@section('title', 'Analytics Dashboard - CBS')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-12">
            <div class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-indigo-50 to-purple-50 px-4 py-2 mb-4">
                <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zm12-2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6a1 1 0 011-1h2z"></path>
                </svg>
                <span class="text-sm font-semibold text-indigo-700">System Analytics</span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Business Analytics</h1>
            <p class="text-lg text-gray-600">Real-time system performance and sales insights</p>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Sales -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium opacity-90">Total Sales</h3>
                    <svg class="w-8 h-8 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.16 2.75a.75.75 0 00-1.32 0l-3.5 9.983A.75.75 0 003.75 13h12.5a.75.75 0 00.66-1.034L8.16 2.75z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold">{{ $stats['total_sales'] ?? 0 }}</p>
                <p class="text-sm opacity-75 mt-2">Last 30 days</p>
            </div>

            <!-- Total Revenue -->
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium opacity-90">Total Revenue</h3>
                    <svg class="w-8 h-8 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.5 5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6 9.125C6 8.504 6.672 8 7.5 8s1.5.504 1.5 1.125V12h3V9.125C12 8.504 12.672 8 13.5 8s1.5.504 1.5 1.125V12h.5a1.5 1.5 0 001.5-1.5V9a1.5 1.5 0 00-1.5-1.5h-7A1.5 1.5 0 005 9v1.5a1.5 1.5 0 001.5 1.5H6V9.125z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold">{{ number_format((int)($stats['total_revenue'] ?? 0)) }}</p>
                <p class="text-sm opacity-75 mt-2">Estimated revenue</p>
            </div>

            <!-- Total Inquiries -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium opacity-90">Total Inquiries</h3>
                    <svg class="w-8 h-8 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.5 3A1.5 1.5 0 001 4.5v.006c0 .564.224 1.077.586 1.45a2.5 2.5 0 002.794 2.794 1.5 1.5 0 112.988 0 2.5 2.5 0 002.794-2.794 1.5 1.5 0 111.586-1.45 1.5 1.5 0 01-1.586 1.45 2.5 2.5 0 00-2.794 2.794 1.5 1.5 0 11-2.988 0 2.5 2.5 0 00-2.794-2.794C2.724 5.577 2.5 5.064 2.5 4.5v-.006A1.5 1.5 0 012.5 3z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold">{{ $stats['total_inquiries'] ?? 0 }}</p>
                <p class="text-sm opacity-75 mt-2">Customer inquiries</p>
            </div>

            <!-- Total Views -->
            <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium opacity-90">Total Views</h3>
                    <svg class="w-8 h-8 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"></path>
                        <path d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold">{{ number_format($stats['total_views'] ?? 0) }}</p>
                <p class="text-sm opacity-75 mt-2">Vehicle views</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Activity Summary -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Activity Summary (Last 30 Days)</h3>
                <div class="space-y-4">
                    @foreach($activitySummary as $activity)
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <div class="flex justify-between mb-2">
                                    <span class="font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $activity->action) }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $activity->count }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-2 rounded-full" style="width: {{ min(($activity->count / 100) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Export Options -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Export Reports</h3>
                <div class="space-y-3">
                    <a href="{{ route('analytics.export.pdf') }}" class="w-full flex items-center gap-3 p-4 bg-gradient-to-r from-red-50 to-red-100 hover:from-red-100 hover:to-red-200 rounded-lg transition border border-red-200">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 16.5a1 1 0 11-2 0 1 1 0 012 0zM15 7a1 1 0 11-2 0 1 1 0 012 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-red-900">Export as PDF</p>
                            <p class="text-sm text-red-700">Download formatted report</p>
                        </div>
                    </a>

                    <a href="{{ route('analytics.export.excel') }}" class="w-full flex items-center gap-3 p-4 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-lg transition border border-green-200">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-green-900">Export as Excel</p>
                            <p class="text-sm text-green-700">Download spreadsheet</p>
                        </div>
                    </a>

                    <a href="{{ route('analytics.activity-logs') }}" class="w-full flex items-center gap-3 p-4 bg-gradient-to-r from-indigo-50 to-indigo-100 hover:from-indigo-100 hover:to-indigo-200 rounded-lg transition border border-indigo-200">
                        <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-indigo-900">Activity Logs</p>
                            <p class="text-sm text-indigo-700">View detailed activity</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Top Vehicles -->
        @if($topVehicles->count() > 0)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Top Performing Vehicles</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Vehicle</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-700">Inquiries</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topVehicles as $vehicle)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gray-200 rounded flex-shrink-0"></div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                                                <p class="text-sm text-gray-500">{{ $vehicle->year }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="font-bold text-gray-900">{{ $vehicle->inquiries_count }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">Sold</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
