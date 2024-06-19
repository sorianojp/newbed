<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeminarTraining extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'title',
        'venue',
        'start_date',
        'end_date',
        'hours',
        'ld_type',
        'conducted_sponsored',
        'service_return',
        'remark',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
