<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAdditional extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_schedule_id',
        'employee_id',
        'additional_id',
        'amount',
        'remark',
    ];

    protected $with = ['additional'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollSchedule(): BelongsTo
    {
        return $this->belongsTo(PayrollSchedule::class);
    }

    public function additional(): BelongsTo
    {
        return $this->belongsTo(Additional::class);
    }

    public function scopeSchedule(Builder $query, $type): void
    {
        $query->where('payroll_schedule_id', $type);
    }
}
