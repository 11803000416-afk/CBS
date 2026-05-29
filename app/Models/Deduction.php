<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deduction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payroll_record_id',
        'deduction_type',
        'amount',
        'reason',
        'reference_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the payroll record associated with the deduction
     */
    public function payrollRecord()
    {
        return $this->belongsTo(PayrollRecord::class);
    }
}
