<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'overtime_type_id',
        'date',
        'start_time',
        'end_time',
        'reason',
    ];

    public function overtimeType(): BelongsTo
    {
        return $this->belongsTo(OvertimeType::class);
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class);
    }
}
