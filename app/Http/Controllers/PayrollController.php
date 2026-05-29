<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use App\Models\PayrollRecord;
use App\Models\Attendance;
use App\Models\Deduction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class PayrollController extends Controller
{
    /**
     * Show payroll dashboard
     */
    public function dashboard(): View
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $stats = [
            'total_employees' => Employee::where('status', 'Active')->count(),
            'payroll_processed' => PayrollRecord::where('month', $currentMonth)
                ->where('year', $currentYear)
                ->where('status', '!=', 'Pending')
                ->count(),
            'pending_payroll' => PayrollRecord::where('status', 'Pending')->count(),
            'total_payroll_amount' => PayrollRecord::where('month', $currentMonth)
                ->where('year', $currentYear)
                ->sum('net_salary'),
        ];

        $recentPayrolls = PayrollRecord::with('employee')
            ->latest()
            ->take(10)
            ->get();

        return view('payroll.dashboard', compact('stats', 'recentPayrolls', 'currentMonth', 'currentYear'));
    }

    /**
     * List employees
     */
    public function employees(Request $request): View
    {
        $query = Employee::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('employee_id', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        if ($request->has('department')) {
            $query->where('department', $request->input('department'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $employees = $query->paginate(15);

        return view('payroll.employees.index', compact('employees'));
    }

    /**
     * Show create employee form
     */
    public function createEmployee(): View
    {
        return view('payroll.employees.create');
    }

    /**
     * Store new employee
     */
    public function storeEmployee(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|unique:employees',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|string',
            'designation' => 'required|string',
            'department' => 'required|string',
            'date_of_joining' => 'required|date',
            'base_salary' => 'required|numeric|min:0',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'bank_account_number' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'ifsc_code' => 'nullable|string',
        ]);

        $employee = Employee::create($validated);

        // Create initial salary record
        Salary::create([
            'employee_id' => $employee->id,
            'base_salary' => $validated['base_salary'],
            'effective_from' => now()->startOfMonth(),
        ]);

        return redirect()->route('payroll.employees')
            ->with('success', 'Employee added successfully');
    }

    /**
     * Edit employee
     */
    public function editEmployee(Employee $employee): View
    {
        return view('payroll.employees.edit', compact('employee'));
    }

    /**
     * Update employee
     */
    public function updateEmployee(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'required|string',
            'designation' => 'required|string',
            'department' => 'required|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'bank_account_number' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'ifsc_code' => 'nullable|string',
            'status' => 'required|in:Active,Inactive,On Leave',
        ]);

        $employee->update($validated);

        return redirect()->route('payroll.employees')
            ->with('success', 'Employee updated successfully');
    }

    /**
     * Show salary management
     */
    public function salaries(Request $request): View
    {
        $query = Salary::with('employee');

        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->input('employee_id'));
        }

        $salaries = $query->paginate(15);
        $employees = Employee::all();

        return view('payroll.salaries.index', compact('salaries', 'employees'));
    }

    /**
     * Create salary record
     */
    public function createSalary(): View
    {
        $employees = Employee::where('status', 'Active')->get();
        return view('payroll.salaries.create', compact('employees'));
    }

    /**
     * Store salary record
     */
    public function storeSalary(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'base_salary' => 'required|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'dearness_allowance' => 'nullable|numeric|min:0',
            'conveyance_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'pf_contribution' => 'nullable|numeric|min:0',
            'income_tax' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'effective_from' => 'required|date',
        ]);

        Salary::create($validated);

        return redirect()->route('payroll.salaries')
            ->with('success', 'Salary record created successfully');
    }

    /**
     * Show payroll processing
     */
    public function payroll(Request $request): View
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $payrolls = PayrollRecord::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->paginate(15);

        return view('payroll.payroll.index', compact('payrolls', 'month', 'year'));
    }

    /**
     * Generate payroll for month
     */
    public function generatePayroll(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
        ]);

        $employees = Employee::where('status', 'Active')->get();
        $month = $validated['month'];
        $year = $validated['year'];

        foreach ($employees as $employee) {
            // Check if payroll already exists
            $existing = PayrollRecord::where('employee_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();

            if ($existing) {
                continue;
            }

            // Get attendance for the month
            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereMonth('attendance_date', $month)
                ->whereYear('attendance_date', $year)
                ->get();

            $daysPresent = $attendance->where('status', 'Present')->count();
            $daysAbsent = $attendance->where('status', 'Absent')->count();
            $paidLeave = $attendance->where('status', 'Leave')->count() + $attendance->where('status', 'Paid Leave')->count();
            $unpaidLeave = $attendance->where('status', 'Unpaid Leave')->count();

            // Calculate working days (assuming 26 days per month)
            $workingDays = 26;

            // Get current salary
            $salary = $employee->currentSalary() ?? $employee->salaries()->first();
            
            if (!$salary) {
                continue;
            }

            // Calculate gross salary (for days worked)
            $perDaySalary = $salary->base_salary / $workingDays;
            $grossSalary = ($daysPresent * $perDaySalary) + 
                          ($salary->hra + $salary->dearness_allowance + 
                           $salary->conveyance_allowance + $salary->medical_allowance + 
                           $salary->other_allowances);

            // Calculate deductions
            $totalDeductions = $salary->pf_contribution + $salary->income_tax + $salary->other_deductions;

            // Calculate net salary
            $netSalary = $grossSalary - $totalDeductions;

            // Create payroll record
            PayrollRecord::create([
                'employee_id' => $employee->id,
                'month' => $month,
                'year' => $year,
                'working_days' => $workingDays,
                'days_present' => $daysPresent,
                'days_absent' => $daysAbsent,
                'paid_leave' => $paidLeave,
                'unpaid_leave' => $unpaidLeave,
                'gross_salary' => $grossSalary,
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary,
                'status' => 'Pending',
            ]);
        }

        return redirect()->route('payroll.payroll', ['month' => $month, 'year' => $year])
            ->with('success', 'Payroll generated successfully');
    }

    /**
     * Approve payroll
     */
    public function approvePayroll(PayrollRecord $payroll): RedirectResponse
    {
        $payroll->update(['status' => 'Approved']);

        return back()->with('success', 'Payroll approved successfully');
    }

    /**
     * Process payment
     */
    public function processPayment(PayrollRecord $payroll): RedirectResponse
    {
        $payroll->update([
            'status' => 'Paid',
            'payment_date' => now(),
        ]);

        return back()->with('success', 'Payment processed successfully');
    }

    /**
     * Show payroll details
     */
    public function showPayroll(PayrollRecord $payroll): View
    {
        $payroll->load('employee', 'deductions');
        return view('payroll.payroll.show', compact('payroll'));
    }

    /**
     * Show attendance
     */
    public function attendance(Request $request): View
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $employeeId = $request->input('employee_id');

        $query = Attendance::with('employee')
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year);

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $attendance = $query->paginate(30);
        $employees = Employee::all();

        return view('payroll.attendance.index', compact('attendance', 'employees', 'month', 'year'));
    }

    /**
     * Mark attendance
     */
    public function markAttendance(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:Present,Absent,Leave,Half Day,Holiday',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'remarks' => 'nullable|string',
        ]);

        Attendance::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'attendance_date' => $validated['attendance_date'],
            ],
            $validated
        );

        return back()->with('success', 'Attendance marked successfully');
    }

    /**
     * Generate payroll report
     */
    public function generateReport(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $payrolls = PayrollRecord::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->where('status', '!=', 'Rejected')
            ->get();

        return view('payroll.reports.payroll-report', compact('payrolls', 'month', 'year'));
    }

    /**
     * Export payroll to CSV
     */
    public function exportPayroll(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $payrolls = PayrollRecord::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        $filename = "payroll_" . Carbon::createFromDate($year, $month, 1)->format('Y_M') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $csv = fopen('php://memory', 'w');
        fputcsv($csv, ['Employee ID', 'Name', 'Designation', 'Gross Salary', 'Deductions', 'Net Salary', 'Status']);

        foreach ($payrolls as $payroll) {
            fputcsv($csv, [
                $payroll->employee->employee_id,
                $payroll->employee->full_name,
                $payroll->employee->designation,
                $payroll->gross_salary,
                $payroll->total_deductions,
                $payroll->net_salary,
                $payroll->status,
            ]);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        return response($content, 200, $headers);
    }
}
