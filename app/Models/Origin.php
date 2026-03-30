<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Origin extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'region_code',
        'country',
        'description',
    ];

    /**
     * Get the seed batches for the origin.
     */
    public function seedBatches(): HasMany
    {
        return $this->hasMany(SeedBatch::class);
    }

    /**
     * Get the plants for the origin.
     */
    public function plants(): HasMany
    {
        return $this->hasMany(Plant::class);
    }

    /**
     * Get the price multiplier for the origin.
     */
    public function priceMultiplier(): HasOne
    {
        return $this->hasOne(OriginPriceMultiplier::class);
    }
}
