@extends('layouts.app')

@section('title', 'Mark Attendance')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mark Attendance</h1>
            <p class="mt-2 text-gray-600">Record employee attendance and leave</p>
        </div>

        <!-- Month Filter -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('payroll.attendance') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                    <select name="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate(2024, $i, 1)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @for($i = date('Y'); $i >= date('Y') - 2; $i--)
                            <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
                    <select name="employee_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Employees</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="md:col-span-3 lg:col-span-1 bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition">
                    Filter
                </button>
            </form>
        </div>

        @if($attendance->count() > 0)
            <!-- Attendance Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($attendance as $record)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $record->employee->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $record->employee->employee_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $record->attendance_date->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($record->status === 'Present') bg-green-100 text-green-800
                                            @elseif($record->status === 'Absent') bg-red-100 text-red-800
                                            @elseif($record->status === 'Leave') bg-blue-100 text-blue-800
                                            @elseif($record->status === 'Holiday') bg-purple-100 text-purple-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ $record->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $record->check_in_time ? \Carbon\Carbon::parseFromFormat('H:i', $record->check_in_time)->format('h:i A') : '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $record->check_out_time ? \Carbon\Carbon::parseFromFormat('H:i', $record->check_out_time)->format('h:i A') : '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $record->hours_worked ?? '-' }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($attendance->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $attendance->links() }}
                    </div>
                @endif
            </div>

            <!-- Mark Attendance Form -->
            <div class="bg-white rounded-lg shadow p-6 mt-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Mark Attendance</h2>
                
                <form method="POST" action="{{ route('payroll.attendance.mark') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Employee *</label>
                        <select name="employee_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                        <input type="date" name="attendance_date" required value="{{ now()->toDateString() }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Status</option>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Leave">Leave</option>
                            <option value="Paid Leave">Paid Leave</option>
                            <option value="Half Day">Half Day</option>
                            <option value="Holiday">Holiday</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check In Time</label>
                        <input type="time" name="check_in_time"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Check Out Time</label>
                        <input type="time" name="check_out_time"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                        <input type="text" name="remarks"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Any remarks (optional)">
                    </div>

                    <div class="md:col-span-3 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                            Mark Attendance
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-500 text-lg">No attendance records found for {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
