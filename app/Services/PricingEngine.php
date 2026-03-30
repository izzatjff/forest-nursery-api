<?php

namespace App\Services;

use App\Models\HeightPriceBracket;
use App\Models\OriginPriceMultiplier;
use App\Models\Plant;
use App\Models\PricingRule;
use App\Models\SeedBatch;
use App\Models\Species;

class PricingEngine
{
    /**
     * Calculate the selling price for a seed batch (per unit).
     *
     * Steps:
     * 1. Get species base_price
     * 2. Apply origin multiplier (from origin_price_multipliers table)
     * 3. Apply any active PricingRule records for entity_type 'seed' or 'both'
     * 4. Return final price and breakdown array
     *
     * @return array{unit_price: float, calculated_price: float, breakdown: array}
     */
    public function calculateSeedPrice(SeedBatch $batch): array
    {
        $species = $batch->species;
        $basePrice = (float) $species->base_price;
        $breakdown = [
            ['rule' => 'Base Price ('.$species->name.')', 'value' => $basePrice],
        ];

        $price = $basePrice;

        // Step 2: Origin multiplier
        $originMultiplier = OriginPriceMultiplier::where('origin_id', $batch->origin_id)->first();
        if ($originMultiplier && (float) $originMultiplier->multiplier !== 1.0) {
            $price *= (float) $originMultiplier->multiplier;
            $breakdown[] = [
                'rule' => 'Origin Premium ('.$batch->origin->name.')',
                'multiplier' => (float) $originMultiplier->multiplier,
                'value' => $price,
            ];
        }

        // Step 3: Apply active pricing rules
        $rules = PricingRule::active()
            ->forEntity('seed')
            ->orderBy('priority')
            ->get();

        foreach ($rules as $rule) {
            $price = $this->applyRule($price, $rule);
            $breakdown[] = [
                'rule' => $rule->name,
                'type' => $rule->rule_type,
                'multiplier' => (float) $rule->multiplier,
                'flat_adjustment' => (float) $rule->flat_adjustment,
                'value' => $price,
            ];
        }

        return [
            'unit_price' => round($basePrice, 2),
            'calculated_price' => round($price, 2),
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Calculate the selling price for a plant.
     *
     * Steps:
     * 1. Get species base_price
     * 2. Apply origin multiplier
     * 3. Apply height bracket multiplier (from height_price_brackets)
     * 4. Apply any active PricingRule records for entity_type 'plant' or 'both'
     * 5. Return final price and breakdown array
     *
     * @return array{unit_price: float, calculated_price: float, breakdown: array}
     */
    public function calculatePlantPrice(Plant $plant): array
    {
        $species = $plant->species;
        $basePrice = (float) $species->base_price;
        $breakdown = [
            ['rule' => 'Base Price ('.$species->name.')', 'value' => $basePrice],
        ];

        $price = $basePrice;

        // Step 2: Origin multiplier
        $originMultiplier = OriginPriceMultiplier::where('origin_id', $plant->origin_id)->first();
        if ($originMultiplier && (float) $originMultiplier->multiplier !== 1.0) {
            $price *= (float) $originMultiplier->multiplier;
            $breakdown[] = [
                'rule' => 'Origin Premium ('.$plant->origin->name.')',
                'multiplier' => (float) $originMultiplier->multiplier,
                'value' => $price,
            ];
        }

        // Step 3: Height bracket
        $heightBracket = HeightPriceBracket::where(function ($q) use ($plant) {
            $q->where('species_id', $plant->species_id)
                ->orWhereNull('species_id');
        })
            ->where('min_height_cm', '<=', $plant->height_cm)
            ->where(function ($q) use ($plant) {
                $q->where('max_height_cm', '>=', $plant->height_cm)
                    ->orWhereNull('max_height_cm');
            })
            ->orderByRaw('species_id IS NULL') // prefer species-specific over generic
            ->first();

        if ($heightBracket && (float) $heightBracket->multiplier !== 1.0) {
            $price *= (float) $heightBracket->multiplier;
            $breakdown[] = [
                'rule' => 'Height Bracket ('.$heightBracket->min_height_cm.'-'.($heightBracket->max_height_cm ?? '∞').' cm)',
                'multiplier' => (float) $heightBracket->multiplier,
                'value' => $price,
            ];
        }

        // Step 4: Apply active pricing rules
        $rules = PricingRule::active()
            ->forEntity('plant')
            ->orderBy('priority')
            ->get();

        foreach ($rules as $rule) {
            $price = $this->applyRule($price, $rule);
            $breakdown[] = [
                'rule' => $rule->name,
                'type' => $rule->rule_type,
                'multiplier' => (float) $rule->multiplier,
                'flat_adjustment' => (float) $rule->flat_adjustment,
                'value' => $price,
            ];
        }

        return [
            'unit_price' => round($basePrice, 2),
            'calculated_price' => round(max(0, $price), 2),
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Apply a single pricing rule (multiplier + flat adjustment).
     */
    protected function applyRule(float $currentPrice, PricingRule $rule): float
    {
        $price = $currentPrice * (float) $rule->multiplier;
        $price += (float) $rule->flat_adjustment;

        return max(0, $price);
    }
}
