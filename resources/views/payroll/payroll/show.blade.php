@extends('layouts.app')

@section('title', 'Payroll Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Payroll Details</h1>
                <p class="mt-2 text-gray-600">{{ \Carbon\Carbon::createFromDate($payroll->year, $payroll->month, 1)->format('F Y') }}</p>
            </div>
            <a href="{{ route('payroll.payroll', ['month' => $payroll->month, 'year' => $payroll->year]) }}" class="text-blue-600 hover:text-blue-900">
                ← Back
            </a>
        </div>

        <!-- Employee Info -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Employee Name</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $payroll->employee->full_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Employee ID</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $payroll->employee->employee_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Designation</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $payroll->employee->designation }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Department</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $payroll->employee->department }}</p>
                </div>
            </div>
        </div>

        <!-- Attendance -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Attendance</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Present</p>
                    <p class="text-2xl font-bold text-green-600">{{ $payroll->days_present }}</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Absent</p>
                    <p class="text-2xl font-bold text-red-600">{{ $payroll->days_absent }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Paid Leave</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $payroll->paid_leave }}</p>
                </div>
                <div class="bg-orange-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">Unpaid Leave</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $payroll->unpaid_leave }}</p>
                </div>
            </div>
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Attendance %</p>
                <p class="text-2xl font-bold text-gray-900">{{ $payroll->attendance_percentage }}%</p>
            </div>
        </div>

        <!-- Salary Breakdown -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Salary Breakdown</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Earnings -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Earnings</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Gross Salary</span>
                            <span class="font-medium">₹{{ number_format($payroll->gross_salary, 2) }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <div class="flex justify-between font-semibold">
                                <span>Total Earnings</span>
                                <span class="text-green-600">₹{{ number_format($payroll->gross_salary, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deductions -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Deductions</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Deductions</span>
                            <span class="font-medium">₹{{ number_format($payroll->total_deductions, 2) }}</span>
                        </div>
                        @foreach($payroll->deductions as $deduction)
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>{{ $deduction->deduction_type }}</span>
                                <span>₹{{ number_format($deduction->amount, 2) }}</span>
                            </div>
                        @endforeach
                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <div class="flex justify-between font-semibold">
                                <span>Total Deductions</span>
                                <span class="text-red-600">₹{{ number_format($payroll->total_deductions, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Net Salary -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-900">Net Salary</span>
                    <span class="text-3xl font-bold text-green-600">₹{{ number_format($payroll->net_salary, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Status and Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Status</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($payroll->status === 'Pending') bg-yellow-100 text-yellow-800
                        @elseif($payroll->status === 'Approved') bg-blue-100 text-blue-800
                        @elseif($payroll->status === 'Paid') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $payroll->status }}
                    </span>
                </div>
                <div>
                    @if($payroll->status === 'Pending')
                        <form action="{{ route('payroll.payroll.approve', $payroll) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                Approve Payroll
                            </button>
                        </form>
                    @elseif($payroll->status === 'Approved')
                        <form action="{{ route('payroll.payroll.process-payment', $payroll) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                                Process Payment
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if($payroll->payment_date)
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">Payment Date: <span class="font-semibold">{{ $payroll->payment_date->format('M d, Y') }}</span></p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
