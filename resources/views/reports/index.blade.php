@extends('layouts.app')

@section('title', 'Reports')
@section('subtitle', 'Business analytics and performance metrics')

@section('content')
<!-- Enhanced Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <p class="text-blue-100 text-sm font-medium mb-1">Total Vehicles</p>
        <p class="text-4xl font-bold">{{ $summary['total_vehicles'] }}</p>
    </div>
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg">
        <p class="text-emerald-100 text-sm font-medium mb-1">Vehicles Sold</p>
        <p class="text-4xl font-bold">{{ $summary['vehicles_sold'] }}</p>
    </div>
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <p class="text-purple-100 text-sm font-medium mb-1">Total Sales</p>
        <p class="text-3xl font-bold">Nu. {{ number_format($summary['total_sales'], 2) }}</p>
    </div>
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
        <p class="text-orange-100 text-sm font-medium mb-1">Total Commission</p>
        <p class="text-3xl font-bold">Nu. {{ number_format($summary['total_commission'], 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Monthly Sales
            </h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Month</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($monthlySales as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $item->month }}</td>
                        <td class="px-4 py-3 font-semibold text-emerald-600">Nu. {{ number_format($item->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="px-4 py-8 text-center text-slate-400">No monthly data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-200">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Broker Commission
            </h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Broker</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Commission</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($brokerCommission as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $item->broker_name }}</td>
                        <td class="px-4 py-3 font-semibold text-blue-600">Nu. {{ number_format($item->commission_total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="px-4 py-8 text-center text-slate-400">No commission data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
