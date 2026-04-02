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
            [
                'name' => 'Sibu, Sarawak',
                'region_code' => 'SWK-SBU',
                'country' => 'Malaysia',
                'description' => 'Lowland peat swamp and mixed dipterocarp forest region along the Rejang River basin.',
            ],
            [
                'name' => 'Bintulu, Sarawak',
                'region_code' => 'SWK-BTU',
                'country' => 'Malaysia',
                'description' => 'Coastal region with extensive planted forests and Sarawak Forestry Corporation nurseries.',
            ],
            [
                'name' => 'Kuching, Sarawak',
                'region_code' => 'SWK-KCH',
                'country' => 'Malaysia',
                'description' => 'Capital division of Sarawak with kerangas and mixed dipterocarp forest zones.',
            ],
            [
                'name' => 'Sandakan, Sabah',
                'region_code' => 'SBH-SDK',
                'country' => 'Malaysia',
                'description' => 'Major timber town with access to Sepilok and Kabili-Sepilok virgin jungle reserve.',
            ],
            [
                'name' => 'Kota Kinabalu, Sabah',
                'region_code' => 'SBH-KK',
                'country' => 'Malaysia',
                'description' => 'Highland and lowland forest regions near Mount Kinabalu, rich in endemic species.',
            ],
            [
                'name' => 'Semengoh, Sarawak',
                'region_code' => 'SWK-SMG',
                'country' => 'Malaysia',
                'description' => 'Site of the Semengoh Forest Nursery and seed bank, primary local seedling source.',
            ],
        ];

        foreach ($origins as $o) {
            Origin::create($o);
        }

        // Origin price multipliers reflect rarity and logistics cost of sourcing from each region.
        // - Semengoh (local nursery stock) is the baseline at 1.00×
        // - Remote or ecologically sensitive origins carry a premium
        $multipliers = [
            'SWK-SBU' => [
                'multiplier' => 1.10,
                'notes' => 'Nearby lowland source; slight transport markup from Sibu.',
            ],
            'SWK-BTU' => [
                'multiplier' => 1.00,
                'notes' => 'Well-established plantation supply chain; no premium.',
            ],
            'SWK-KCH' => [
                'multiplier' => 1.05,
                'notes' => 'Close proximity to main nursery; minimal logistics cost.',
            ],
            'SBH-SDK' => [
                'multiplier' => 1.30,
                'notes' => 'Cross-state transport from Sabah; premium wild-collected genetics from virgin jungle reserve.',
            ],
            'SBH-KK' => [
                'multiplier' => 1.50,
                'notes' => 'Highland provenance near Mount Kinabalu; rare endemic genetics, difficult collection terrain.',
            ],
            'SWK-SMG' => [
                'multiplier' => 1.00,
                'notes' => 'Local nursery baseline — seeds and seedlings from our own Semengoh facility.',
            ],
        ];

        foreach ($multipliers as $code => $data) {
            $origin = Origin::where('region_code', $code)->first();
            if ($origin) {
                OriginPriceMultiplier::create(array_merge($data, ['origin_id' => $origin->id]));
            }
        }
    }
}
