<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Additional extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'hidden', 'included',
    ];

    public function code(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => strtoupper($value),
        );
    }

    public function name(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => ucwords($value),
        );
    }

    public function inclusion(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->included ? 'Included in computation of contributions' : 'Not included in computation of contributions'
        );
    }
}
