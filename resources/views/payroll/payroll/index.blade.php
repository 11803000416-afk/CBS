@extends('layouts.app')

@section('title', 'Payroll Processing')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Payroll Processing</h1>
            <p class="mt-2 text-gray-600">Manage and process employee payroll</p>
        </div>

        <!-- Month Selection & Generate Button -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="POST" action="{{ route('payroll.payroll.generate') }}" class="flex flex-col md:flex-row gap-4 items-end">
                @csrf
                
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                    <select name="month" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate(2024, $i, 1)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <select name="year" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                    Generate Payroll
                </button>
            </form>
        </div>

        @if($payrolls->count() > 0)
            <!-- Payroll Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Present</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gross Salary</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deductions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net Salary</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($payrolls as $payroll)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $payroll->employee->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $payroll->employee->employee_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $payroll->days_present }}/{{ $payroll->working_days }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">₹{{ number_format($payroll->gross_salary, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">₹{{ number_format($payroll->total_deductions, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-green-600">₹{{ number_format($payroll->net_salary, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($payroll->status === 'Pending') bg-yellow-100 text-yellow-800
                                            @elseif($payroll->status === 'Approved') bg-blue-100 text-blue-800
                                            @elseif($payroll->status === 'Paid') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $payroll->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('payroll.payroll.show', $payroll) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                        @if($payroll->status === 'Pending')
                                            <form action="{{ route('payroll.payroll.approve', $payroll) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                            </form>
                                        @elseif($payroll->status === 'Approved')
                                            <form action="{{ route('payroll.payroll.process-payment', $payroll) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-purple-600 hover:text-purple-900">Pay</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($payrolls->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $payrolls->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-500 text-lg">No payroll records for {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</p>
                <p class="text-gray-400 text-sm mt-2">Generate payroll to create records for all active employees</p>
            </div>
        @endif
    </div>
</div>
@endsection
