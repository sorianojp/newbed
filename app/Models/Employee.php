<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id_no', 'lastname', 'firstname', 'middlename', 'name_ext', 'mobile_no', 'personal_email', 'company_email',
    ];

    protected $appends = [
        'full_name', 'full_name_only',
    ];

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->lastname}, {$this->firstname} {$this->middlename} - {$this->employee_id_no}"
        );
    }

    public function fullNameOnly(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->lastname}, {$this->firstname} {$this->middlename}"
        );
    }

    public function overtimes(): BelongsToMany
    {
        return $this->belongsToMany(Overtime::class);
    }

    public function employeeSetting(): HasOne
    {
        return $this->hasOne(EmployeeSetting::class);
    }

    public function additionals(): HasMany
    {
        return $this->hasMany(EmployeeAdditional::class);
    }

    public function deductions(): HasMany
    {
        return $this->hasMany(EmployeeDeduction::class);
    }

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
    public function jobSkills()
    {
        return $this->hasMany(JobSkill::class);
    }
    public function childrenDatas()
    {
        return $this->hasMany(ChildrenData::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function noDailyTimeRecord()
    {
        return $this->hasMany(NoDailyTimeRecord::class);
    }
    public function groupings()
    {
        return $this->belongsToMany(Grouping::class, 'employee_groupings');
    }
}
