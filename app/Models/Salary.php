<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'base_salary',
        'hra',
        'dearness_allowance',
        'conveyance_allowance',
        'medical_allowance',
        'other_allowances',
        'pf_contribution',
        'income_tax',
        'other_deductions',
        'effective_from',
        'effective_to',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
        'base_salary' => 'decimal:2',
        'hra' => 'decimal:2',
        'dearness_allowance' => 'decimal:2',
        'conveyance_allowance' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'other_allowances' => 'decimal:2',
        'pf_contribution' => 'decimal:2',
        'income_tax' => 'decimal:2',
        'other_deductions' => 'decimal:2',
    ];

    /**
     * Get the employee associated with the salary
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Calculate gross salary
     */
    public function getGrossSalaryAttribute()
    {
        return $this->base_salary + $this->hra + $this->dearness_allowance + 
               $this->conveyance_allowance + $this->medical_allowance + $this->other_allowances;
    }

    /**
     * Calculate total deductions
     */
    public function getTotalDeductionsAttribute()
    {
        return $this->pf_contribution + $this->income_tax + $this->other_deductions;
    }

    /**
     * Calculate net salary
     */
    public function getNetSalaryAttribute()
    {
        return $this->getGrossSalaryAttribute() - $this->getTotalDeductionsAttribute();
    }
}
