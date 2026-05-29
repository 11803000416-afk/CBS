@extends('layouts.app')

@section('title', 'Salary Management')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Salary Management</h1>
                <p class="mt-2 text-gray-600">Manage employee salary structures</p>
            </div>
            <a href="{{ route('payroll.salaries.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                + Add Salary Record
            </a>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('payroll.salaries') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <select name="employee_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Employees</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->full_name }} ({{ $emp->employee_id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        @if($salaries->count() > 0)
            <!-- Salaries Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Base Salary</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">HRA</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gross Salary</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deductions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net Salary</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Effective From</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($salaries as $salary)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $salary->employee->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $salary->employee->employee_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">₹{{ number_format($salary->base_salary, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">₹{{ number_format($salary->hra, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">₹{{ number_format($salary->gross_salary, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">₹{{ number_format($salary->total_deductions, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-green-600">₹{{ number_format($salary->net_salary, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $salary->effective_from->format('M d, Y') }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($salaries->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $salaries->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-500 text-lg">No salary records found</p>
            </div>
        @endif
    </div>
</div>
@endsection
