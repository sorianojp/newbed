<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'lastname', 'firstname', 'middlename', 'name_ext', 'birthdate', 'mobile_no', 'personal_email'
    ];
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function tenureship(): BelongsTo
    {
        return $this->belongsTo(Tenureship::class);
    }
}
