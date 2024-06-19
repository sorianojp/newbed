<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id_no', 'lastname', 'firstname', 'middlename', 'name_ext', 'mobile_no', 'personal_email', 'company_email',
    ];

    protected $appends = [
        'full_name',
    ];

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->lastname}, {$this->firstname} {$this->middlename} - {$this->employee_id_no}"
        );
    }

    public function personalData()
    {
        return $this->hasOne(EmployeePersonalData::class);
    }
    public function employmentRecords()
    {
        return $this->hasMany(EmploymentRecord::class);
    }
}
