@extends('layouts.app')

@section('title', 'Payroll Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Payroll Management</h1>
            <p class="mt-2 text-gray-600">Manage employees, salaries, and payroll processing</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Employees -->
            <div class="summary-tile">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Total Employees</p>
                        <p class="summary-tile-value mt-2">{{ $stats['total_employees'] }}</p>
                    </div>
                    <div class="bg-cyan-100 p-3 rounded-2xl shadow-sm">
                        <svg class="w-6 h-6 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 6a3 3 0 11-6 0 3 3 0 016 0zM9 12a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Payroll Processed -->
            <div class="summary-tile">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Payroll Processed</p>
                        <p class="summary-tile-value text-emerald-600 mt-2">{{ $stats['payroll_processed'] }}</p>
                    </div>
                    <div class="bg-emerald-100 p-3 rounded-2xl shadow-sm">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Payroll -->
            <div class="summary-tile">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Pending</p>
                        <p class="summary-tile-value text-amber-600 mt-2">{{ $stats['pending_payroll'] }}</p>
                    </div>
                    <div class="bg-amber-100 p-3 rounded-2xl shadow-sm">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Payroll Amount -->
            <div class="summary-tile">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Total Amount</p>
                        <p class="summary-tile-value text-slate-700 mt-2">Nu.{{ number_format($stats['total_payroll_amount'], 2) }}</p>
                    </div>
                    <div class="bg-slate-100 p-3 rounded-2xl shadow-sm">
                        <svg class="w-6 h-6 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.16 5.314l4.897-1.596A1 1 0 0114.407 5.2l-1.537 4.615h4.105a1 1 0 01.823 1.659l-6.363 7.38a1 1 0 01-1.606-1.351l1.518-4.246H5.592a1 1 0 01-.823-1.659l6.363-7.38a1 1 0 011.606 1.351L7.68 8.569H3.575a1 1 0 01-.823-1.659l6.363-7.38a1 1 0 011.606 1.351L8.16 5.314z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('payroll.employees') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white font-medium py-3 px-4 rounded-2xl transition shadow-sm">
                👥 Manage Employees
            </a>
            <a href="{{ route('payroll.salaries') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 px-4 rounded-2xl transition shadow-sm">
                💰 Salary Structure
            </a>
            <a href="{{ route('payroll.payroll') }}" class="bg-slate-700 hover:bg-slate-800 text-white font-medium py-3 px-4 rounded-2xl transition shadow-sm">
                📊 Process Payroll
            </a>
            <a href="{{ route('payroll.attendance') }}" class="bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-4 rounded-2xl transition shadow-sm">
                📋 Mark Attendance
            </a>
        </div>

        <!-- Recent Payrolls -->
        <div class="premium-card overflow-hidden">
            <div class="premium-card-header">
                <h2 class="text-xl font-bold text-slate-900">Recent Payrolls</h2>
            </div>

            @if($recentPayrolls->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Month</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Gross Salary</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Net Salary</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($recentPayrolls as $payroll)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-slate-900">{{ $payroll->employee->full_name }}</div>
                                        <div class="text-sm text-slate-500">{{ $payroll->employee->employee_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-700">{{ \Carbon\Carbon::createFromDate($payroll->year, $payroll->month, 1)->format('M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-slate-900">Nu.{{ number_format($payroll->gross_salary, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-emerald-600">Nu.{{ number_format($payroll->net_salary, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($payroll->status === 'Pending') bg-amber-100 text-amber-800
                                            @elseif($payroll->status === 'Approved') bg-cyan-100 text-cyan-800
                                            @elseif($payroll->status === 'Paid') bg-emerald-100 text-emerald-800
                                            @else bg-rose-100 text-rose-800
                                            @endif">
                                            {{ $payroll->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('payroll.payroll.show', $payroll) }}" class="text-cyan-600 hover:text-cyan-700 font-semibold">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <p class="text-gray-500">No payroll records found</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
