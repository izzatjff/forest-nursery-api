<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    /**
     * Indicates that the model does not have an updated_at timestamp.
     * This model serves as an immutable audit log.
     *
     * @var string|null
     */
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'movable_type',
        'movable_id',
        'movement_type',
        'quantity',
        'reference_type',
        'reference_id',
        'notes',
        'performed_by',
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
        ];
    }

    /**
     * Get the movable entity (seed batch or plant) that this movement relates to.
     */
    public function movable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the reference entity associated with this movement.
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who performed this stock movement.
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
