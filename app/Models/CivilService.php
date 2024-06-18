<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CivilService extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'title',
        'rating',
        'exam_date',
        'exam_place',
        'license_no',
        'validity_date'
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
