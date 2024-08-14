<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagibig extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'effective_date', 'percentage',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];
}
