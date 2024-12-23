<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeachingSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'day_of_week',
        'start_time',
        'end_time',
        'start_date',
        'end_date',
        'weight',
    ];

    protected $appends = [
        'effective_date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function teachingAttendances(): HasMany
    {
        return $this->hasMany(TeachingAttendance::class);
    }

    public function effectiveDate(): Attribute
    {
        $start = Carbon::parse($this->start_date)->format('m/d/Y');
        $end = Carbon::parse($this->end_date)->format('m/d/Y');

        return Attribute::make(
            get: fn() => "{$start} to {$end}",
        );
    }
}
