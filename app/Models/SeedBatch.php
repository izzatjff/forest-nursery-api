<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeedBatch extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'batch_code',
        'species_id',
        'origin_id',
        'source_type',
        'supplier_name',
        'collection_date',
        'initial_quantity',
        'remaining_quantity',
        'unit',
        'storage_location',
        'viability_percentage',
        'notes',
        'qr_code_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'collection_date' => 'date',
            'initial_quantity' => 'decimal:2',
            'remaining_quantity' => 'decimal:2',
            'viability_percentage' => 'decimal:2',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (SeedBatch $seedBatch) {
            if (empty($seedBatch->batch_code)) {
                $seedBatch->batch_code = 'SB-'.date('Y').'-'.str_pad(SeedBatch::withTrashed()->count() + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the species that this seed batch belongs to.
     */
    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }

    /**
     * Get the origin that this seed batch belongs to.
     */
    public function origin(): BelongsTo
    {
        return $this->belongsTo(Origin::class);
    }

    /**
     * Get the plants grown from this seed batch.
     */
    public function plants(): HasMany
    {
        return $this->hasMany(Plant::class);
    }

    /**
     * Get the sale items associated with this seed batch.
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the procurement record associated with this seed batch.
     */
    public function procurement(): HasOne
    {
        return $this->hasOne(Procurement::class);
    }

    /**
     * Get all stock movements for this seed batch.
     */
    public function stockMovements(): MorphMany
    {
        return $this->morphMany(StockMovement::class, 'movable');
    }
}
