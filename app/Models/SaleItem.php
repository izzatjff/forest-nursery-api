<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'item_type',
        'seed_batch_id',
        'plant_id',
        'quantity',
        'unit_price',
        'calculated_price',
        'subtotal',
        'price_breakdown',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'calculated_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'price_breakdown' => 'array',
        ];
    }

    /**
     * Get the sale that owns this item.
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the seed batch associated with this sale item.
     */
    public function seedBatch(): BelongsTo
    {
        return $this->belongsTo(SeedBatch::class);
    }

    /**
     * Get the plant associated with this sale item.
     */
    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
