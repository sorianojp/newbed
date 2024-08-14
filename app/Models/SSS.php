<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SSS extends Model
{
    use HasFactory;

    protected $table = 'sss';

    protected $fillable = [
        'name', 'effective_date',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];

    public function sssBrackets(): HasMany
    {
        return $this->hasMany(SSSBracket::class, 'sss_id');
    }
}
