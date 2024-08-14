<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_type_id',
        'salary_start_date',
        'salary_end_date',
        'cutoff_start_date',
        'cutoff_end_date',
        'pay_date',
        'period',
        'month',
        'year',
        'status',
    ];

    protected $appends = [
        'schedule_name',
        'range',
        'cutoff',
    ];

    public function scheduleName(): Attribute
    {
        $title = $this->payrollType->name;
        return Attribute::make(
            get: fn() => "{$title} - {$this->cutoff} ({$this->period})",
        );
    }

    public function range(): Attribute
    {
        $start = Carbon::parse($this->salary_start_date)->format('m/d/Y');
        $end = Carbon::parse($this->salary_end_date)->format('m/d/Y');

        return Attribute::make(
            get: fn() => "{$start} to {$end}",
        );
    }

    public function cutoff(): Attribute
    {
        $start = Carbon::parse($this->cutoff_start_date)->format('m/d/Y');
        $end = Carbon::parse($this->cutoff_end_date)->format('m/d/Y');

        return Attribute::make(
            get: fn() => "{$start} to {$end}",
        );
    }

    public function monthYear(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->month} {$this->year}",
        );
    }

    public function payrollType(): BelongsTo
    {
        return $this->belongsTo(PayrollType::class);
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(PaySlip::class);
    }
}
