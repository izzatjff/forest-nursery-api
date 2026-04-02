<?php

namespace Database\Seeders;

use App\Models\Origin;
use App\Models\Procurement;
use App\Models\SeedBatch;
use App\Models\Species;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;

class ProcurementSeeder extends Seeder
{
    public function run(): void
    {
        $species = Species::all()->keyBy('name');
        $origins = Origin::all()->keyBy('region_code');
        $batches = SeedBatch::all()->keyBy('batch_code');

        $procurements = [
            // ── Acacia — Sibu wild collection ─────────────────────────────
            [
                'procurement_number' => 'PO-2025-00001',
                'supplier_name' => 'Sibu Forest Department',
                'supplier_contact' => '+60 84-336 721',
                'species_name' => 'Acacia',
                'origin_code' => 'SWK-SBU',
                'source_type' => 'wild_collection',
                'quantity' => 5000,
                'unit' => 'pieces',
                'cost_per_unit' => 0.05,
                'total_cost' => 250.00,
                'received_date' => '2025-01-10',
                'batch_code' => 'SB-2025-00001',
                'notes' => 'Annual supply contract — first delivery of 2025. Collected from mature Acacia stands along Sungai Rajang.',
            ],

            // ── Acacia — Sandakan purchased ───────────────────────────────
            [
                'procurement_number' => 'PO-2025-00002',
                'supplier_name' => 'Sandakan Seed Supply Sdn Bhd',
                'supplier_contact' => '+60 89-612 100',
                'species_name' => 'Acacia',
                'origin_code' => 'SBH-SDK',
                'source_type' => 'purchased',
                'quantity' => 3000,
                'unit' => 'pieces',
                'cost_per_unit' => 0.08,
                'total_cost' => 240.00,
                'received_date' => '2025-02-15',
                'batch_code' => 'SB-2025-00002',
                'notes' => 'Purchased batch from Sabah. Uniform seed size, passed quality inspection on arrival.',
            ],

            // ── Batai — Bintulu wild collection ───────────────────────────
            [
                'procurement_number' => 'PO-2025-00003',
                'supplier_name' => 'Bintulu Forest Services',
                'supplier_contact' => '+60 86-255 100',
                'species_name' => 'Batai',
                'origin_code' => 'SWK-BTU',
                'source_type' => 'wild_collection',
                'quantity' => 2000,
                'unit' => 'pieces',
                'cost_per_unit' => 0.06,
                'total_cost' => 120.00,
                'received_date' => '2025-01-20',
                'batch_code' => 'SB-2025-00003',
                'notes' => 'Collected from mature Batai stand near Bintulu. Seeds pre-treated with hot water before dispatch.',
            ],

            // ── Batai — Kuching nursery propagation ───────────────────────
            [
                'procurement_number' => 'PO-2025-00004',
                'supplier_name' => 'In-House Propagation',
                'supplier_contact' => null,
                'species_name' => 'Batai',
                'origin_code' => 'SWK-KCH',
                'source_type' => 'nursery_propagation',
                'quantity' => 1500,
                'unit' => 'pieces',
                'cost_per_unit' => 0.02,
                'total_cost' => 30.00,
                'received_date' => '2025-03-05',
                'batch_code' => 'SB-2025-00004',
                'notes' => 'Propagated from our own mother trees in Kuching nursery. Labour cost only.',
            ],

            // ── Belian — Sibu wild collection (2024) ──────────────────────
            [
                'procurement_number' => 'PO-2024-00001',
                'supplier_name' => 'Sarawak Forestry Corporation',
                'supplier_contact' => '+60 82-610 088',
                'species_name' => 'Belian / Borneo Ironwood',
                'origin_code' => 'SWK-SBU',
                'source_type' => 'wild_collection',
                'quantity' => 200,
                'unit' => 'pieces',
                'cost_per_unit' => 5.00,
                'total_cost' => 1000.00,
                'received_date' => '2024-09-15',
                'batch_code' => 'SB-2024-00001',
                'notes' => 'Endangered species. Collected under SFC permit SFC-2024-0892. Premium cold-chain transport from collection site.',
            ],

            // ── Belian — Sandakan wild collection ─────────────────────────
            [
                'procurement_number' => 'PO-2025-00005',
                'supplier_name' => 'Sabah Forestry Department',
                'supplier_contact' => '+60 88-326 400',
                'species_name' => 'Belian / Borneo Ironwood',
                'origin_code' => 'SBH-SDK',
                'source_type' => 'wild_collection',
                'quantity' => 150,
                'unit' => 'pieces',
                'cost_per_unit' => 6.00,
                'total_cost' => 900.00,
                'received_date' => '2025-01-08',
                'batch_code' => 'SB-2025-00005',
                'notes' => 'Sabah provenance from protected reserve. Air-freighted with cold pack. Permit ref: SFD-BEL-2025-003.',
            ],

            // ── Engkabang Jantong — Bintulu mast fruiting (2024) ──────────
            [
                'procurement_number' => 'PO-2024-00002',
                'supplier_name' => 'Bintulu Division Forest Office',
                'supplier_contact' => '+60 86-332 400',
                'species_name' => 'Engkabang Jantong',
                'origin_code' => 'SWK-BTU',
                'source_type' => 'wild_collection',
                'quantity' => 800,
                'unit' => 'pieces',
                'cost_per_unit' => 0.50,
                'total_cost' => 400.00,
                'received_date' => '2024-10-20',
                'batch_code' => 'SB-2024-00002',
                'notes' => 'Mast fruiting event collection. Recalcitrant seeds — dispatched within 24 hours of harvest. Time-critical delivery.',
            ],

            // ── Engkabang Jantong — Sibu collection ───────────────────────
            [
                'procurement_number' => 'PO-2025-00006',
                'supplier_name' => 'Sibu Forest Department',
                'supplier_contact' => '+60 84-336 721',
                'species_name' => 'Engkabang Jantong',
                'origin_code' => 'SWK-SBU',
                'source_type' => 'wild_collection',
                'quantity' => 600,
                'unit' => 'pieces',
                'cost_per_unit' => 0.60,
                'total_cost' => 360.00,
                'received_date' => '2025-02-01',
                'batch_code' => 'SB-2025-00006',
                'notes' => 'Fresh collection from Sibu riparian forest. Good seed coat condition. Stored in damp sphagnum during transit.',
            ],

            // ── Kelampayan — Kuching wild collection ──────────────────────
            [
                'procurement_number' => 'PO-2025-00007',
                'supplier_name' => 'Kuching Green Resources',
                'supplier_contact' => '+60 82-421 300',
                'species_name' => 'Kelampayan',
                'origin_code' => 'SWK-KCH',
                'source_type' => 'wild_collection',
                'quantity' => 10000,
                'unit' => 'pieces',
                'cost_per_unit' => 0.01,
                'total_cost' => 100.00,
                'received_date' => '2025-01-25',
                'batch_code' => 'SB-2025-00007',
                'notes' => 'Bulk fine-seed collection from roadside Kelampayan trees near Kuching. Total weight under 200g for 10,000 seeds.',
            ],

            // ── Kelampayan — Kota Kinabalu purchased ──────────────────────
            [
                'procurement_number' => 'PO-2025-00008',
                'supplier_name' => 'KK Green Nursery',
                'supplier_contact' => '+60 88-723 456',
                'species_name' => 'Kelampayan',
                'origin_code' => 'SBH-KK',
                'source_type' => 'purchased',
                'quantity' => 5000,
                'unit' => 'pieces',
                'cost_per_unit' => 0.02,
                'total_cost' => 100.00,
                'received_date' => '2025-03-10',
                'batch_code' => 'SB-2025-00008',
                'notes' => 'Purchased from Kota Kinabalu nursery supplier. Shipped via express courier from Sabah.',
            ],
        ];

        foreach ($procurements as $p) {
            $speciesModel = $species->get($p['species_name']);
            $originModel = $origins->get($p['origin_code']);
            $batchModel = $batches->get($p['batch_code']);

            if (! $speciesModel || ! $originModel) {
                $this->command->warn("Skipping procurement {$p['procurement_number']}: missing species or origin.");

                continue;
            }

            $procurement = Procurement::create([
                'procurement_number' => $p['procurement_number'],
                'supplier_name' => $p['supplier_name'],
                'supplier_contact' => $p['supplier_contact'],
                'species_id' => $speciesModel->id,
                'origin_id' => $originModel->id,
                'source_type' => $p['source_type'],
                'quantity' => $p['quantity'],
                'unit' => $p['unit'],
                'cost_per_unit' => $p['cost_per_unit'],
                'total_cost' => $p['total_cost'],
                'received_date' => $p['received_date'],
                'seed_batch_id' => $batchModel?->id,
                'notes' => $p['notes'],
            ]);

            // Record inbound stock movement linked to the seed batch
            if ($batchModel) {
                StockMovement::create([
                    'movable_type' => SeedBatch::class,
                    'movable_id' => $batchModel->id,
                    'movement_type' => 'procurement_inbound',
                    'quantity' => $p['quantity'],
                    'reference_type' => Procurement::class,
                    'reference_id' => $procurement->id,
                    'notes' => "Inbound: {$p['procurement_number']} — {$p['quantity']} {$p['unit']} of {$speciesModel->name} from {$p['supplier_name']}.",
                    'performed_by' => null,
                    'created_at' => $p['received_date'],
                ]);
            }
        }

        $this->command->info('Seeded '.count($procurements).' procurement records with stock movements.');
    }
}
