<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'designation',
        'department',
        'date_of_joining',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'zip_code',
        'bank_account_number',
        'bank_name',
        'ifsc_code',
        'status',
        'base_salary',
    ];

    protected $casts = [
        'date_of_joining' => 'date',
        'date_of_birth' => 'date',
        'base_salary' => 'decimal:2',
    ];

    /**
     * Get the user associated with the employee
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the salary records for the employee
     */
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    /**
     * Get the payroll records for the employee
     */
    public function payrollRecords()
    {
        return $this->hasMany(PayrollRecord::class);
    }

    /**
     * Get the attendance records for the employee
     */
    public function attendanceRecords()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the current active salary
     */
    public function currentSalary()
    {
        return $this->salaries()
            ->where('effective_from', '<=', now())
            ->where(function ($query) {
                $query->whereNull('effective_to')
                      ->orWhere('effective_to', '>=', now());
            })
            ->latest('effective_from')
            ->first();
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
