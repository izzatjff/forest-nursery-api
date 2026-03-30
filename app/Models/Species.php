<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Species extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'scientific_name',
        'description',
        'base_price',
        'low_stock_threshold',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'low_stock_threshold' => 'integer',
        ];
    }

    /**
     * Get the seed batches for the species.
     *
     * @return HasMany<SeedBatch, $this>
     */
    public function seedBatches(): HasMany
    {
        return $this->hasMany(SeedBatch::class);
    }

    /**
     * Get the plants for the species.
     *
     * @return HasMany<Plant, $this>
     */
    public function plants(): HasMany
    {
        return $this->hasMany(Plant::class);
    }

    /**
     * Get the height price brackets for the species.
     *
     * @return HasMany<HeightPriceBracket, $this>
     */
    public function heightPriceBrackets(): HasMany
    {
        return $this->hasMany(HeightPriceBracket::class);
    }
}
