<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_period',
        'computation_basis',
        'employee_id',
        'payroll_type_id',
        'monthly_basic_salary',
        'cut_off_days_per_month',
        'hourly_rate',
        'teaching_rate',
        'tax',
        'sss',
        'pag_ibig',
        'phil_health',
        'holiday_pay',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollType(): BelongsTo
    {
        return $this->belongsTo(PayrollType::class);
    }
}
