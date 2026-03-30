<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OriginPriceMultiplier extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'origin_id',
        'multiplier',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'multiplier' => 'decimal:3',
        ];
    }

    /**
     * Get the origin that this price multiplier belongs to.
     */
    public function origin(): BelongsTo
    {
        return $this->belongsTo(Origin::class);
    }
}
