<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeightPriceBracket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'species_id',
        'min_height_cm',
        'max_height_cm',
        'multiplier',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'min_height_cm' => 'decimal:2',
            'max_height_cm' => 'decimal:2',
            'multiplier' => 'decimal:3',
        ];
    }

    /**
     * Get the species that this height price bracket belongs to.
     */
    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }
}
