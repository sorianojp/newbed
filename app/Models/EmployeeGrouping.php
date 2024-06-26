<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeGrouping extends Model
{
    use HasFactory;
    protected $table = 'employee_groupings';
    protected $fillable = ['employee_id', 'grouping_id'];
}
