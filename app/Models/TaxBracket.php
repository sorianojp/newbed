<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxBracket extends Model
{
    use HasFactory;

    protected $fillable = [
        'tax_id',
        'period',
        'bracket',
        'start_range',
        'end_range',
        'fixed_amount',
        'excess_percentage',
    ];

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function salaryRange(): Attribute
    {
        $start = 'Php ' . number_format($this->start_range, 2);
        $end = $this->end_range ? ' - Php ' . number_format($this->end_range, 2) : ' and above';
        return Attribute::make(
            get: fn() => "{$start} {$end}",
        );
    }
}
