<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaySlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_schedule_id',
        'employee_id',
        'basic_rate',
        'teaching_rate',
        'total_hours',
        'working_hours',
        'teaching_hours',
        'total_teaching_hours',
        'absent_days',
        'absent_amount',
        'tardiness_hours',
        'tardiness_amount',
        'overtime_hours',
        'overtime_amount',
        'holiday_hours',
        'holiday_amount',
        'gross_salary',
        'included_additionals',
        'not_included_additionals',
        'taxable_deductions',
        'not_taxable_deductions',
        'wtax',
        'sss',
        'philhealth',
        'pagibig',
        'net_salary',
    ];

    protected $casts = [
        'included_additionals' => 'json',
        'not_included_additionals' => 'json',
        'taxable_deductions' => 'json',
        'not_taxable_deductions' => 'json',
    ];

    public function payrollSchedule()
    {
        return $this->belongsTo(PayrollSchedule::class);
    }
}
