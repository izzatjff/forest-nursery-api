<?php

namespace Database\Seeders;

use App\Models\Origin;
use App\Models\OriginPriceMultiplier;
use Illuminate\Database\Seeder;

class OriginSeeder extends Seeder
{
    public function run(): void
    {
        $origins = [
            ['name' => 'Borneo Highlands', 'region_code' => 'BRN-HL', 'country' => 'Malaysia', 'description' => 'Pristine highland rainforest region.'],
            ['name' => 'Western Ghats', 'region_code' => 'IND-WG', 'country' => 'India', 'description' => 'Biodiversity hotspot along India\'s western coast.'],
            ['name' => 'Amazon Basin', 'region_code' => 'BRA-AM', 'country' => 'Brazil', 'description' => 'World\'s largest tropical rainforest.'],
            ['name' => 'Central Java', 'region_code' => 'IDN-CJ', 'country' => 'Indonesia', 'description' => 'Volcanic soil regions with rich forest cover.'],
            ['name' => 'Local Nursery', 'region_code' => 'LOCAL', 'country' => null, 'description' => 'Seeds sourced from our own nursery stock.'],
            ['name' => 'Congo Basin', 'region_code' => 'COD-CB', 'country' => 'DR Congo', 'description' => 'Africa\'s largest contiguous forest.'],
        ];

        foreach ($origins as $o) {
            $origin = Origin::create($o);
        }

        // Set origin price multipliers
        $multipliers = [
            'BRN-HL' => ['multiplier' => 1.75, 'notes' => 'Premium highland genetics'],
            'IND-WG' => ['multiplier' => 1.30, 'notes' => 'Biodiversity hotspot premium'],
            'BRA-AM' => ['multiplier' => 2.00, 'notes' => 'Rare Amazonian cultivar premium'],
            'IDN-CJ' => ['multiplier' => 1.15, 'notes' => 'Volcanic soil advantage'],
            'LOCAL' => ['multiplier' => 1.00, 'notes' => 'Standard local pricing'],
            'COD-CB' => ['multiplier' => 1.50, 'notes' => 'African forest genetics premium'],
        ];

        foreach ($multipliers as $code => $data) {
            $origin = Origin::where('region_code', $code)->first();
            if ($origin) {
                OriginPriceMultiplier::create(array_merge($data, ['origin_id' => $origin->id]));
            }
        }
    }
}
