<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistinctionRecognition extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'title',
        'place',
        'date',
        'agency_org',
        'remark'
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
