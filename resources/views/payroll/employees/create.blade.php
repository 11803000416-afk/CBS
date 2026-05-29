@extends('layouts.app')

@section('title', 'Add Employee')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add New Employee</h1>
            <p class="mt-2 text-gray-600">Fill in the employee details below</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('payroll.employees.store') }}" class="bg-white rounded-lg shadow p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Employee ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee ID *</label>
                    <input type="text" name="employee_id" required value="{{ old('employee_id') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('employee_id') border-red-500 @enderror"
                        placeholder="EMP001">
                    @error('employee_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- First Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" name="first_name" required value="{{ old('first_name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror"
                        placeholder="John">
                    @error('first_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" name="last_name" required value="{{ old('last_name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror"
                        placeholder="Doe">
                    @error('last_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        placeholder="john@example.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="tel" name="phone" required value="{{ old('phone') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                        placeholder="9876543210">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date of Birth -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_of_birth') border-red-500 @enderror">
                    @error('date_of_birth')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <select name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Designation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Designation *</label>
                    <input type="text" name="designation" required value="{{ old('designation') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('designation') border-red-500 @enderror"
                        placeholder="Manager">
                    @error('designation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                    <select name="department" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('department') border-red-500 @enderror">
                        <option value="">Select Department</option>
                        <option value="Sales" {{ old('department') === 'Sales' ? 'selected' : '' }}>Sales</option>
                        <option value="HR" {{ old('department') === 'HR' ? 'selected' : '' }}>HR</option>
                        <option value="IT" {{ old('department') === 'IT' ? 'selected' : '' }}>IT</option>
                        <option value="Finance" {{ old('department') === 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Operations" {{ old('department') === 'Operations' ? 'selected' : '' }}>Operations</option>
                    </select>
                    @error('department')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date of Joining -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Joining *</label>
                    <input type="date" name="date_of_joining" required value="{{ old('date_of_joining') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_of_joining') border-red-500 @enderror">
                    @error('date_of_joining')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Base Salary -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Base Salary *</label>
                    <input type="number" name="base_salary" required value="{{ old('base_salary') }}" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('base_salary') border-red-500 @enderror"
                        placeholder="0">
                    @error('base_salary')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="123 Main Street">
                </div>

                <!-- City -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="New York">
                </div>

                <!-- State -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                    <input type="text" name="state" value="{{ old('state') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="NY">
                </div>

                <!-- Zip Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
                    <input type="text" name="zip_code" value="{{ old('zip_code') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="10001">
                </div>

                <!-- Bank Account Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Account Number</label>
                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="1234567890">
                </div>

                <!-- Bank Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="ABC Bank">
                </div>

                <!-- IFSC Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">IFSC Code</label>
                    <input type="text" name="ifsc_code" value="{{ old('ifsc_code') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="ABCD0001234">
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('payroll.employees') }}" class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    Add Employee
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
