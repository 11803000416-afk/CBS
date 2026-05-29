@extends('layouts.app')

@section('title', 'Payroll Report')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Payroll Report</h1>
                <p class="mt-2 text-gray-600">{{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('payroll.export', ['month' => $month, 'year' => $year]) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                    📥 Export CSV
                </a>
                <a href="{{ route('payroll.payroll') }}" class="px-4 py-2 text-blue-600 border border-blue-600 hover:bg-blue-50 font-medium rounded-lg transition">
                    Back
                </a>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Total Employees</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $payrolls->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Total Gross Salary</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($payrolls->sum('gross_salary'), 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Total Deductions</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($payrolls->sum('total_deductions'), 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-gray-600 text-sm">Total Net Salary</p>
                <p class="text-2xl font-bold text-green-600 mt-1">₹{{ number_format($payrolls->sum('net_salary'), 2) }}</p>
            </div>
        </div>

        <!-- Payroll Details Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Payroll Breakdown</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Designation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Present</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Gross Salary</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Deductions</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Net Salary</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($payrolls as $payroll)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $payroll->employee->full_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $payroll->employee->employee_id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $payroll->employee->designation }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $payroll->days_present }}/{{ $payroll->working_days }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-medium text-gray-900">₹{{ number_format($payroll->gross_salary, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-gray-900">₹{{ number_format($payroll->total_deductions, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-bold text-green-600">₹{{ number_format($payroll->net_salary, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($payroll->status === 'Pending') bg-yellow-100 text-yellow-800
                                        @elseif($payroll->status === 'Approved') bg-blue-100 text-blue-800
                                        @elseif($payroll->status === 'Paid') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $payroll->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach

                        <!-- Totals Row -->
                        <tr class="font-bold bg-gray-50 border-t-2 border-gray-300">
                            <td colspan="4" class="px-6 py-4 text-gray-900">TOTAL</td>
                            <td class="px-6 py-4 text-right text-gray-900">₹{{ number_format($payrolls->sum('gross_salary'), 2) }}</td>
                            <td class="px-6 py-4 text-right text-gray-900">₹{{ number_format($payrolls->sum('total_deductions'), 2) }}</td>
                            <td class="px-6 py-4 text-right text-green-600">₹{{ number_format($payrolls->sum('net_salary'), 2) }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Print Section -->
        <div class="mt-8 text-center">
            <button onclick="window.print()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                🖨️ Print Report
            </button>
        </div>
    </div>
</div>

<style media="print">
    .no-print { display: none; }
    body { background: white; }
    .bg-gray-50 { background: white; }
</style>
@endsection
