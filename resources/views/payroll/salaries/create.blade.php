@extends('layouts.app')

@section('title', 'Add Salary Record')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add Salary Record</h1>
            <p class="mt-2 text-gray-600">Add or update salary structure for an employee</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('payroll.salaries.store') }}" class="bg-white rounded-lg shadow p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Employee -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee *</label>
                    <select name="employee_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('employee_id') border-red-500 @enderror">
                        <option value="">Select Employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->full_name }} ({{ $emp->employee_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
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

                <!-- Effective From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Effective From *</label>
                    <input type="date" name="effective_from" required value="{{ old('effective_from', now()->startOfMonth()->toDateString()) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('effective_from') border-red-500 @enderror">
                    @error('effective_from')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Allowances Section -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 mt-4">Allowances</h3>
                </div>

                <!-- HRA -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">HRA</label>
                    <input type="number" name="hra" value="{{ old('hra', 0) }}" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </div>

                <!-- Dearness Allowance -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dearness Allowance</label>
                    <input type="number" name="dearness_allowance" value="{{ old('dearness_allowance', 0) }}" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </div>

                <!-- Conveyance Allowance -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Conveyance Allowance</label>
                    <input type="number" name="conveyance_allowance" value="{{ old('conveyance_allowance', 0) }}" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </div>

                <!-- Medical Allowance -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Medical Allowance</label>
                    <input type="number" name="medical_allowance" value="{{ old('medical_allowance', 0) }}" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </div>

                <!-- Other Allowances -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Other Allowances</label>
                    <input type="number" name="other_allowances" value="{{ old('other_allowances', 0) }}" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </div>

                <!-- Deductions Section -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 mt-4">Deductions</h3>
                </div>

                <!-- PF Contribution -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">PF Contribution</label>
                    <input type="number" name="pf_contribution" value="{{ old('pf_contribution', 0) }}" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </div>

                <!-- Income Tax -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Income Tax</label>
                    <input type="number" name="income_tax" value="{{ old('income_tax', 0) }}" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </div>

                <!-- Other Deductions -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Other Deductions</label>
                    <input type="number" name="other_deductions" value="{{ old('other_deductions', 0) }}" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('payroll.salaries') }}" class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    Add Salary Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
