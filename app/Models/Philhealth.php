<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Philhealth extends Model
{
    use HasFactory;

    protected $fillable = [
        'bracket',
        'start_range',
        'end_range',
        'base',
        'premium',
        'employee_share',
        'employer_share',
        'percentage',
    ];

    public function range(): Attribute
    {
        $start = number_format($this->start_range, 2);
        $end = number_format($this->end_range, 2);
        return Attribute::make(
            get: fn() => "$start - $end",
        );
    }

    public function percent(): Attribute
    {
        $percent = $this->percentage ? $this->percentage . "%" : "N/A";
        return Attribute::make(
            get: fn() => "$percent",
        );
    }
}
