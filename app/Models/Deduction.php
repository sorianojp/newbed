<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'hidden', 'deducted',
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

    public function deductable(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->deducted ? 'Deducted from non-taxable gross' : 'Not deducted from non-taxable gross'
        );
    }
}
