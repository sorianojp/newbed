<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContributionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'contribution', 'period',
    ];
}
