<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'position_id',
        'department_id',
        'tenureship_id',
        'base_salary',
        'start_date',
        'end_date',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function tenureship(): BelongsTo
    {
        return $this->belongsTo(Tenureship::class);
    }
}
