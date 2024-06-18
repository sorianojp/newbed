<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id_no', 'lastname', 'firstname', 'middlename', 'name_ext', 'mobile_no', 'personal_email', 'company_email'
    ];
    public function personalData()
    {
        return $this->hasOne(EmployeePersonalData::class);
    }
    public function employmentRecords()
    {
        return $this->hasMany(EmploymentRecord::class);
    }
    public function educationalAttainments()
    {
        return $this->hasMany(EducationalAttainment::class);
    }
    public function civilServices()
    {
        return $this->hasMany(CivilService::class);
    }
    public function seminarTrainings()
    {
        return $this->hasMany(SeminarTraining::class);
    }
    public function distinctionRecognitions()
    {
        return $this->hasMany(DistinctionRecognition::class);
    }
    public function groupAffiliations()
    {
        return $this->hasMany(GroupAffiliation::class);
    }

}
