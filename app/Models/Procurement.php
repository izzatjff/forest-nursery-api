<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Procurement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'procurement_number',
        'supplier_name',
        'supplier_contact',
        'species_id',
        'origin_id',
        'source_type',
        'quantity',
        'unit',
        'cost_per_unit',
        'total_cost',
        'received_date',
        'seed_batch_id',
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
            'quantity' => 'decimal:2',
            'cost_per_unit' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'received_date' => 'date',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Procurement $procurement) {
            if (! $procurement->procurement_number) {
                $procurement->procurement_number = 'PO-'.date('Y').'-'.str_pad(Procurement::count() + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the species associated with this procurement.
     *
     * @return BelongsTo<Species, $this>
     */
    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }

    /**
     * Get the origin associated with this procurement.
     *
     * @return BelongsTo<Origin, $this>
     */
    public function origin(): BelongsTo
    {
        return $this->belongsTo(Origin::class);
    }

    /**
     * Get the seed batch associated with this procurement.
     *
     * @return BelongsTo<SeedBatch, $this>
     */
    public function seedBatch(): BelongsTo
    {
        return $this->belongsTo(SeedBatch::class);
    }
}
