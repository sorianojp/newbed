<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SSSBracket extends Model
{
    use HasFactory;

    protected $table = 'sss_brackets';

    protected $fillable = [
        'sss_id',
        'start_range',
        'end_range',
        'msc',
        'ec',
        'er',
        'ee',
    ];

    public function salaryRange(): Attribute
    {
        $start = 'Php ' . number_format($this->start_range, 2);
        $end = $this->end_range ? ' - Php ' . number_format($this->end_range, 2) : ' and above';
        return Attribute::make(
            get: fn() => "{$start} {$end}",
        );
    }

    public function total(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->ec + $this->er + $this->ee,
        );
    }
}
