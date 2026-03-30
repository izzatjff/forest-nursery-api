<?php

namespace Database\Seeders;

use App\Models\HeightPriceBracket;
use App\Models\PricingRule;
use App\Models\Species;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    public function run(): void
    {
        // Universal height brackets (apply to all species)
        $brackets = [
            ['species_id' => null, 'min_height_cm' => 0, 'max_height_cm' => 15, 'multiplier' => 1.000],
            ['species_id' => null, 'min_height_cm' => 15, 'max_height_cm' => 50, 'multiplier' => 1.500],
            ['species_id' => null, 'min_height_cm' => 50, 'max_height_cm' => 100, 'multiplier' => 2.500],
            ['species_id' => null, 'min_height_cm' => 100, 'max_height_cm' => 200, 'multiplier' => 4.000],
            ['species_id' => null, 'min_height_cm' => 200, 'max_height_cm' => null, 'multiplier' => 6.000],
        ];

        foreach ($brackets as $b) {
            HeightPriceBracket::create($b);
        }

        // Species-specific overrides for premium species
        $sandalwood = Species::where('scientific_name', 'Santalum album')->first();
        if ($sandalwood) {
            HeightPriceBracket::create(['species_id' => $sandalwood->id, 'min_height_cm' => 0, 'max_height_cm' => 15, 'multiplier' => 1.500]);
            HeightPriceBracket::create(['species_id' => $sandalwood->id, 'min_height_cm' => 15, 'max_height_cm' => 50, 'multiplier' => 3.000]);
            HeightPriceBracket::create(['species_id' => $sandalwood->id, 'min_height_cm' => 50, 'max_height_cm' => null, 'multiplier' => 8.000]);
        }

        // Example pricing rules
        PricingRule::create([
            'name' => 'Rainy Season Promotion',
            'rule_type' => 'seasonal',
            'entity_type' => 'both',
            'criteria' => ['season' => 'rainy', 'note' => 'Encourages planting during optimal season'],
            'multiplier' => 0.900,
            'flat_adjustment' => 0,
            'priority' => 10,
            'is_active' => false,
            'starts_at' => null,
            'ends_at' => null,
        ]);

        PricingRule::create([
            'name' => 'Bulk Purchase Discount (50+)',
            'rule_type' => 'bulk_discount',
            'entity_type' => 'seed',
            'criteria' => ['min_quantity' => 50, 'note' => 'Applied when buying 50+ seeds'],
            'multiplier' => 0.850,
            'flat_adjustment' => 0,
            'priority' => 20,
            'is_active' => true,
            'starts_at' => null,
            'ends_at' => null,
        ]);
    }
}
