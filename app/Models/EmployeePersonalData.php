<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePersonalData extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'tin',
        'sss',
        'philhealth',
        'pag_ibig',
        'gsis',
        'crn',
        'religion',
        'nationality',
        'pob',
        'dob',
        'gender',
        'civil_status',
        'weight',
        'height',
        'blood_type',
        'residential_address',
        'permanent_address',
        'spouse_full_name',
        'spouse_occupation',
        'spouse_mobile_no',
        'spouse_occupation_employer',
        'spouse_occupation_business_address',
        'father_full_name',
        'father_mobile_no',
        'father_occupation',
        'father_occupation_employer',
        'father_occupation_business_address',
        'mother_full_name',
        'mother_mobile_no',
        'mother_occupation',
        'mother_occupation_employer',
        'mother_occupation_business_address',
        'emergency_contact_full_name',
        'emergency_contact_address',
        'emergency_contact_mobile_no'
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
