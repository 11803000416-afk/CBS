@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Employee</h1>
            <p class="mt-2 text-gray-600">Update employee information</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('payroll.employees.update', $employee) }}" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Employee ID (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee ID</label>
                    <input type="text" value="{{ $employee->employee_id }}" disabled
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                </div>

                <!-- First Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" name="first_name" required value="{{ old('first_name', $employee->first_name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror">
                    @error('first_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" name="last_name" required value="{{ old('last_name', $employee->last_name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror">
                    @error('last_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" required value="{{ old('email', $employee->email) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="tel" name="phone" required value="{{ old('phone', $employee->phone) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date of Birth -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth) }}"
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
                        <option value="Male" {{ old('gender', $employee->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $employee->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $employee->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Designation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Designation *</label>
                    <input type="text" name="designation" required value="{{ old('designation', $employee->designation) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('designation') border-red-500 @enderror">
                    @error('designation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                    <select name="department" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('department') border-red-500 @enderror">
                        <option value="">Select Department</option>
                        <option value="Sales" {{ old('department', $employee->department) === 'Sales' ? 'selected' : '' }}>Sales</option>
                        <option value="HR" {{ old('department', $employee->department) === 'HR' ? 'selected' : '' }}>HR</option>
                        <option value="IT" {{ old('department', $employee->department) === 'IT' ? 'selected' : '' }}>IT</option>
                        <option value="Finance" {{ old('department', $employee->department) === 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Operations" {{ old('department', $employee->department) === 'Operations' ? 'selected' : '' }}>Operations</option>
                    </select>
                    @error('department')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="">Select Status</option>
                        <option value="Active" {{ old('status', $employee->status) === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $employee->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="On Leave" {{ old('status', $employee->status) === 'On Leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <input type="text" name="address" value="{{ old('address', $employee->address) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- City -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" name="city" value="{{ old('city', $employee->city) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- State -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                    <input type="text" name="state" value="{{ old('state', $employee->state) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Zip Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
                    <input type="text" name="zip_code" value="{{ old('zip_code', $employee->zip_code) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Bank Account Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Account Number</label>
                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $employee->bank_account_number) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Bank Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $employee->bank_name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- IFSC Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">IFSC Code</label>
                    <input type="text" name="ifsc_code" value="{{ old('ifsc_code', $employee->ifsc_code) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('payroll.employees') }}" class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    Update Employee
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
