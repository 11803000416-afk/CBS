<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'status',
        'check_in_time',
        'check_out_time',
        'hours_worked',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
        'hours_worked' => 'decimal:2',
    ];

    /**
     * Get the employee associated with the attendance
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
