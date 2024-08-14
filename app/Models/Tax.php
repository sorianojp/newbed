<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'effective_date',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];

    public function taxBrackets(): HasMany
    {
        return $this->hasMany(TaxBracket::class)->orderBy('period')->orderBy('bracket');
    }
}
