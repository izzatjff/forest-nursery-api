<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PricingRule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'rule_type',
        'entity_type',
        'criteria',
        'multiplier',
        'flat_adjustment',
        'priority',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'criteria' => 'array',
            'multiplier' => 'decimal:3',
            'flat_adjustment' => 'decimal:2',
            'priority' => 'integer',
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    /**
     * Scope a query to only include currently active pricing rules.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            });
    }

    /**
     * Scope a query to only include rules for a given entity type (or 'both').
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeForEntity($query, string $entityType)
    {
        return $query->where(function ($q) use ($entityType) {
            $q->where('entity_type', $entityType)
                ->orWhere('entity_type', 'both')
                ->orWhere('entity_type', 'all');
        });
    }
}
