<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'working_days',
        'days_present',
        'days_absent',
        'paid_leave',
        'unpaid_leave',
        'gross_salary',
        'total_deductions',
        'net_salary',
        'bonus',
        'remarks',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'gross_salary' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'bonus' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Get the employee associated with the payroll record
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the deductions for this payroll record
     */
    public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }

    /**
     * Get attendance percentage
     */
    public function getAttendancePercentageAttribute()
    {
        if ($this->working_days === 0) {
            return 0;
        }
        return round(($this->days_present / $this->working_days) * 100, 2);
    }
}
