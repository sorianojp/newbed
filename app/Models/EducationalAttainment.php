<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalAttainment extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'level',
        'course',
        'school',
        'address',
        'year_started',
        'year_ended',
        'year_graduated',
        'units',
        'honor'
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
