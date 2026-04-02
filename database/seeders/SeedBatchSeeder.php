<?php

namespace Database\Seeders;

use App\Models\Origin;
use App\Models\SeedBatch;
use App\Models\Species;
use Illuminate\Database\Seeder;

class SeedBatchSeeder extends Seeder
{
    public function run(): void
    {
        $species = Species::all()->keyBy('name');
        $origins = Origin::all()->keyBy('region_code');

        $batches = [
            // ── Acacia (Acacia mangium) ── cheap, fast-growing, large quantities
            [
                'batch_code' => 'SB-2025-00001',
                'species' => 'Acacia',
                'origin_code' => 'SWK-SBU',
                'source_type' => 'wild_collection',
                'supplier_name' => 'Sibu Forest Department',
                'collection_date' => '2025-01-10',
                'initial_quantity' => 5000,
                'remaining_quantity' => 3200,
                'unit' => 'pieces',
                'storage_location' => 'Shed A - Rack 1',
                'viability_percentage' => 85.00,
                'notes' => 'Collected from Sibu reforestation plots. High germination rate expected.',
            ],
            [
                'batch_code' => 'SB-2025-00002',
                'species' => 'Acacia',
                'origin_code' => 'SBH-SDK',
                'source_type' => 'purchased',
                'supplier_name' => 'Sandakan Seed Supply Sdn Bhd',
                'collection_date' => '2025-02-15',
                'initial_quantity' => 3000,
                'remaining_quantity' => 2800,
                'unit' => 'pieces',
                'storage_location' => 'Shed A - Rack 2',
                'viability_percentage' => 80.00,
                'notes' => 'Purchased batch. Uniform seed size, good quality.',
            ],

            // ── Batai (Paraserianthes falcataria) ── fast-growing, moderate quantities
            [
                'batch_code' => 'SB-2025-00003',
                'species' => 'Batai',
                'origin_code' => 'SWK-BTU',
                'source_type' => 'wild_collection',
                'supplier_name' => null,
                'collection_date' => '2025-01-20',
                'initial_quantity' => 2000,
                'remaining_quantity' => 1450,
                'unit' => 'pieces',
                'storage_location' => 'Shed A - Rack 3',
                'viability_percentage' => 78.00,
                'notes' => 'Collected from mature Batai stand near Bintulu.',
            ],
            [
                'batch_code' => 'SB-2025-00004',
                'species' => 'Batai',
                'origin_code' => 'SWK-KCH',
                'source_type' => 'nursery_propagation',
                'supplier_name' => null,
                'collection_date' => '2025-03-05',
                'initial_quantity' => 1500,
                'remaining_quantity' => 1500,
                'unit' => 'pieces',
                'storage_location' => 'Shed A - Rack 4',
                'viability_percentage' => 90.00,
                'notes' => 'Propagated from our own mother trees in Kuching nursery. Premium viability.',
            ],

            // ── Belian / Borneo Ironwood (Eusideroxylon zwageri) ── rare, small quantities, high value
            [
                'batch_code' => 'SB-2024-00001',
                'species' => 'Belian / Borneo Ironwood',
                'origin_code' => 'SWK-SBU',
                'source_type' => 'wild_collection',
                'supplier_name' => 'Sarawak Forestry Corporation',
                'collection_date' => '2024-09-15',
                'initial_quantity' => 200,
                'remaining_quantity' => 85,
                'unit' => 'pieces',
                'storage_location' => 'Cold Room - Cabinet 1',
                'viability_percentage' => 55.00,
                'notes' => 'Endangered species. Collected under permit SFC-2024-0892. Low viability typical for Belian.',
            ],
            [
                'batch_code' => 'SB-2025-00005',
                'species' => 'Belian / Borneo Ironwood',
                'origin_code' => 'SBH-SDK',
                'source_type' => 'wild_collection',
                'supplier_name' => 'Sabah Forestry Department',
                'collection_date' => '2025-01-08',
                'initial_quantity' => 150,
                'remaining_quantity' => 120,
                'unit' => 'pieces',
                'storage_location' => 'Cold Room - Cabinet 2',
                'viability_percentage' => 60.00,
                'notes' => 'Sabah provenance. Slightly better viability than Sarawak batch.',
            ],

            // ── Engkabang Jantong (Shorea macrophylla) ── dipterocarp, moderate rarity
            [
                'batch_code' => 'SB-2024-00002',
                'species' => 'Engkabang Jantong',
                'origin_code' => 'SWK-BTU',
                'source_type' => 'wild_collection',
                'supplier_name' => 'Bintulu Division Forest Office',
                'collection_date' => '2024-10-20',
                'initial_quantity' => 800,
                'remaining_quantity' => 340,
                'unit' => 'pieces',
                'storage_location' => 'Cold Room - Cabinet 3',
                'viability_percentage' => 65.00,
                'notes' => 'Collected during mast fruiting season. Recalcitrant seeds — must be sown quickly.',
            ],
            [
                'batch_code' => 'SB-2025-00006',
                'species' => 'Engkabang Jantong',
                'origin_code' => 'SWK-SBU',
                'source_type' => 'wild_collection',
                'supplier_name' => 'Sibu Forest Department',
                'collection_date' => '2025-02-01',
                'initial_quantity' => 600,
                'remaining_quantity' => 580,
                'unit' => 'pieces',
                'storage_location' => 'Cold Room - Cabinet 4',
                'viability_percentage' => 70.00,
                'notes' => 'Fresh collection from Sibu riparian forest. Good seed coat condition.',
            ],

            // ── Kelampayan (Neolamarckia cadamba) ── fast-growing, abundant tiny seeds
            [
                'batch_code' => 'SB-2025-00007',
                'species' => 'Kelampayan',
                'origin_code' => 'SWK-KCH',
                'source_type' => 'wild_collection',
                'supplier_name' => null,
                'collection_date' => '2025-01-25',
                'initial_quantity' => 10000,
                'remaining_quantity' => 7500,
                'unit' => 'pieces',
                'storage_location' => 'Shed B - Rack 1',
                'viability_percentage' => 72.00,
                'notes' => 'Very fine seeds collected from roadside Kelampayan trees near Kuching.',
            ],
            [
                'batch_code' => 'SB-2025-00008',
                'species' => 'Kelampayan',
                'origin_code' => 'SBH-KK',
                'source_type' => 'purchased',
                'supplier_name' => 'KK Green Nursery',
                'collection_date' => '2025-03-10',
                'initial_quantity' => 5000,
                'remaining_quantity' => 5000,
                'unit' => 'pieces',
                'storage_location' => 'Shed B - Rack 2',
                'viability_percentage' => 75.00,
                'notes' => 'Purchased from Kota Kinabalu nursery supplier. Untested batch.',
            ],
        ];

        foreach ($batches as $batch) {
            $speciesModel = $species->get($batch['species']);
            $originModel = $origins->get($batch['origin_code']);

            if (! $speciesModel || ! $originModel) {
                $this->command->warn("Skipping batch {$batch['batch_code']}: species '{$batch['species']}' or origin '{$batch['origin_code']}' not found.");

                continue;
            }

            SeedBatch::create([
                'batch_code' => $batch['batch_code'],
                'species_id' => $speciesModel->id,
                'origin_id' => $originModel->id,
                'source_type' => $batch['source_type'],
                'supplier_name' => $batch['supplier_name'],
                'collection_date' => $batch['collection_date'],
                'initial_quantity' => $batch['initial_quantity'],
                'remaining_quantity' => $batch['remaining_quantity'],
                'unit' => $batch['unit'],
                'storage_location' => $batch['storage_location'],
                'viability_percentage' => $batch['viability_percentage'],
                'notes' => $batch['notes'],
            ]);
        }

        $this->command->info('Seeded '.count($batches).' seed batches across all species.');
    }
}
