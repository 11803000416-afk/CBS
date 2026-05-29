@extends('layouts.app')

@section('title', 'Manage Employees')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Employees</h1>
                <p class="mt-2 text-gray-600">Manage your company employees</p>
            </div>
            <a href="{{ route('payroll.employees.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                + Add Employee
            </a>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('payroll.employees') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" placeholder="Search by name, ID, or email" value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <select name="department" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Departments</option>
                        <option value="Sales" {{ request('department') === 'Sales' ? 'selected' : '' }}>Sales</option>
                        <option value="HR" {{ request('department') === 'HR' ? 'selected' : '' }}>HR</option>
                        <option value="IT" {{ request('department') === 'IT' ? 'selected' : '' }}>IT</option>
                        <option value="Finance" {{ request('department') === 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Operations" {{ request('department') === 'Operations' ? 'selected' : '' }}>Operations</option>
                    </select>
                </div>
                <div>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="On Leave" {{ request('status') === 'On Leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full bg-Gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition">
                        Search
                    </button>
                </div>
            </form>
        </div>

        @if($employees->count() > 0)
            <!-- Employees Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Designation</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($employees as $employee)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $employee->employee_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $employee->full_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $employee->department }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $employee->designation }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $employee->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($employee->status === 'Active') bg-green-100 text-green-800
                                            @elseif($employee->status === 'On Leave') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $employee->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('payroll.employees.edit', $employee) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        <a href="{{ route('payroll.salaries', ['employee_id' => $employee->id]) }}" class="text-green-600 hover:text-green-900">Salary</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($employees->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $employees->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-500 text-lg">No employees found</p>
            </div>
        @endif
    </div>
</div>
@endsection
