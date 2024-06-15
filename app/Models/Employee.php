<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'lastname', 'firstname', 'middlename', 'name_ext', 'mobile_no', 'personal_email', 'company_email', 'position_id', 'department_id', 'tenureship_id', 'base_salary', 'start_date', 'end_date'
    ];
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
    public function personalData()
    {
        return $this->hasOne(EmployeePersonalData::class);
    }
}
