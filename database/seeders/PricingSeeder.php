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
        // ─── Universal Height-Price Brackets ──────────────────────────────
        // These apply to all species unless overridden by species-specific brackets.
        // Taller plants cost more because they've been nurtured longer.
        $universalBrackets = [
            ['species_id' => null, 'min_height_cm' => 0,   'max_height_cm' => 15,   'multiplier' => 1.000],  // Seedling
            ['species_id' => null, 'min_height_cm' => 15,  'max_height_cm' => 30,   'multiplier' => 1.250],  // Young seedling
            ['species_id' => null, 'min_height_cm' => 30,  'max_height_cm' => 60,   'multiplier' => 1.750],  // Established seedling
            ['species_id' => null, 'min_height_cm' => 60,  'max_height_cm' => 100,  'multiplier' => 2.500],  // Sapling
            ['species_id' => null, 'min_height_cm' => 100, 'max_height_cm' => 200,  'multiplier' => 4.000],  // Young tree
            ['species_id' => null, 'min_height_cm' => 200, 'max_height_cm' => null,  'multiplier' => 6.000],  // Mature sapling
        ];

        foreach ($universalBrackets as $bracket) {
            HeightPriceBracket::create($bracket);
        }

        // ─── Belian / Borneo Ironwood Overrides ───────────────────────────
        // Belian (Eusideroxylon zwageri) is critically endangered and extremely
        // slow-growing. Larger specimens command a steep premium because they
        // represent years of careful cultivation.
        $belian = Species::where('scientific_name', 'Eusideroxylon zwageri')->first();

        if ($belian) {
            $belianBrackets = [
                ['species_id' => $belian->id, 'min_height_cm' => 0,   'max_height_cm' => 15,  'multiplier' => 1.000],
                ['species_id' => $belian->id, 'min_height_cm' => 15,  'max_height_cm' => 30,  'multiplier' => 1.500],
                ['species_id' => $belian->id, 'min_height_cm' => 30,  'max_height_cm' => 60,  'multiplier' => 2.500],
                ['species_id' => $belian->id, 'min_height_cm' => 60,  'max_height_cm' => 100, 'multiplier' => 4.000],
                ['species_id' => $belian->id, 'min_height_cm' => 100, 'max_height_cm' => null, 'multiplier' => 8.000],
            ];

            foreach ($belianBrackets as $bracket) {
                HeightPriceBracket::create($bracket);
            }
        }

        // ─── Engkabang Jantong Overrides ──────────────────────────────────
        // Engkabang (Shorea macrophylla) is a valuable dipterocarp. Larger
        // specimens are harder to source and maintain, so they carry a
        // moderate premium above the universal brackets.
        $engkabang = Species::where('scientific_name', 'Shorea macrophylla')->first();

        if ($engkabang) {
            $engkabangBrackets = [
                ['species_id' => $engkabang->id, 'min_height_cm' => 0,   'max_height_cm' => 30,  'multiplier' => 1.000],
                ['species_id' => $engkabang->id, 'min_height_cm' => 30,  'max_height_cm' => 60,  'multiplier' => 2.000],
                ['species_id' => $engkabang->id, 'min_height_cm' => 60,  'max_height_cm' => 100, 'multiplier' => 3.000],
                ['species_id' => $engkabang->id, 'min_height_cm' => 100, 'max_height_cm' => null, 'multiplier' => 5.000],
            ];

            foreach ($engkabangBrackets as $bracket) {
                HeightPriceBracket::create($bracket);
            }
        }

        // ─── Pricing Rules ────────────────────────────────────────────────

        // Government reforestation programme — discounted plants for approved projects
        PricingRule::create([
            'name' => 'Government Reforestation Programme',
            'rule_type' => 'programme_discount',
            'entity_type' => 'plant',
            'criteria' => ['programme' => 'reforestation', 'note' => 'Discount for government-approved reforestation projects under JHN Sarawak'],
            'multiplier' => 0.700,
            'flat_adjustment' => 0,
            'priority' => 10,
            'is_active' => true,
            'starts_at' => '2025-01-01 00:00:00',
            'ends_at' => '2025-12-31 23:59:59',
        ]);

        // Bulk seed purchase discount — buying 100+ seeds
        PricingRule::create([
            'name' => 'Bulk Seed Discount (100+)',
            'rule_type' => 'bulk_discount',
            'entity_type' => 'seed',
            'criteria' => ['min_quantity' => 100, 'note' => 'Applied when purchasing 100 or more seeds in a single transaction'],
            'multiplier' => 0.850,
            'flat_adjustment' => 0,
            'priority' => 20,
            'is_active' => true,
            'starts_at' => null,
            'ends_at' => null,
        ]);

        // Monsoon season planting promo — encourage planting Oct–Feb
        PricingRule::create([
            'name' => 'Monsoon Season Planting Promo',
            'rule_type' => 'seasonal',
            'entity_type' => 'both',
            'criteria' => ['season' => 'monsoon', 'months' => [10, 11, 12, 1, 2], 'note' => 'Encourage planting during optimal wet season in Sarawak'],
            'multiplier' => 0.900,
            'flat_adjustment' => 0,
            'priority' => 15,
            'is_active' => false,
            'starts_at' => null,
            'ends_at' => null,
        ]);

        // Endangered species surcharge — applied to Belian sales to fund conservation
        PricingRule::create([
            'name' => 'Endangered Species Conservation Levy',
            'rule_type' => 'surcharge',
            'entity_type' => 'both',
            'criteria' => ['species_scientific_name' => 'Eusideroxylon zwageri', 'note' => 'Conservation levy on Belian to fund replanting and habitat restoration'],
            'multiplier' => 1.000,
            'flat_adjustment' => 5.00,
            'priority' => 5,
            'is_active' => true,
            'starts_at' => null,
            'ends_at' => null,
        ]);

        // Walk-in customer small-order handling fee
        PricingRule::create([
            'name' => 'Small Order Handling Fee',
            'rule_type' => 'surcharge',
            'entity_type' => 'seed',
            'criteria' => ['max_quantity' => 10, 'note' => 'Flat handling fee for small seed orders under 10 units'],
            'multiplier' => 1.000,
            'flat_adjustment' => 2.00,
            'priority' => 30,
            'is_active' => true,
            'starts_at' => null,
            'ends_at' => null,
        ]);
    }
}
