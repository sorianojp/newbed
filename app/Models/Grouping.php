<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grouping extends Model
{
    use HasFactory;

    protected $fillable = ['payroll_type_id', 'name', 'description'];

    public function payrollType()
    {
        return $this->belongsTo(PayrollType::class);
    }
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_groupings');
    }
}
