<?php

namespace Database\Seeders;

use App\Models\Origin;
use App\Models\Plant;
use App\Models\SeedBatch;
use App\Models\Species;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    public function run(): void
    {
        $species = Species::all()->keyBy('scientific_name');
        $origins = Origin::all()->keyBy('region_code');

        // Fetch seed batches grouped by species for linking
        $seedBatches = SeedBatch::all()->groupBy('species_id');

        $plants = [
            // ── Acacia mangium (fast-growing, lots of seedlings) ─────────
            [
                'species' => 'Acacia mangium',
                'origin' => 'SWK-SBU',
                'height_cm' => 5.50,
                'health_status' => 'healthy',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-AC-001',
                'location' => 'Greenhouse A, Row 1',
                'potting_date' => Carbon::now()->subDays(14),
                'notes' => 'Strong germination, uniform growth.',
            ],
            [
                'species' => 'Acacia mangium',
                'origin' => 'SWK-SBU',
                'height_cm' => 6.20,
                'health_status' => 'healthy',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-AC-001',
                'location' => 'Greenhouse A, Row 1',
                'potting_date' => Carbon::now()->subDays(14),
                'notes' => null,
            ],
            [
                'species' => 'Acacia mangium',
                'origin' => 'SWK-SBU',
                'height_cm' => 4.80,
                'health_status' => 'healthy',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-AC-001',
                'location' => 'Greenhouse A, Row 1',
                'potting_date' => Carbon::now()->subDays(14),
                'notes' => null,
            ],
            [
                'species' => 'Acacia mangium',
                'origin' => 'SWK-BTU',
                'height_cm' => 22.00,
                'health_status' => 'healthy',
                'growth_stage' => 'sapling',
                'tray_number' => 'T-AC-002',
                'location' => 'Greenhouse A, Row 3',
                'potting_date' => Carbon::now()->subDays(60),
                'notes' => 'Transferred from seedling tray on schedule.',
            ],
            [
                'species' => 'Acacia mangium',
                'origin' => 'SWK-BTU',
                'height_cm' => 19.50,
                'health_status' => 'moderate',
                'growth_stage' => 'sapling',
                'tray_number' => 'T-AC-002',
                'location' => 'Greenhouse A, Row 3',
                'potting_date' => Carbon::now()->subDays(60),
                'notes' => 'Slightly slower growth; monitor closely.',
            ],
            [
                'species' => 'Acacia mangium',
                'origin' => 'SWK-SBU',
                'height_cm' => 65.00,
                'health_status' => 'healthy',
                'growth_stage' => 'juvenile',
                'tray_number' => null,
                'location' => 'Open Nursery Bed B',
                'potting_date' => Carbon::now()->subDays(120),
                'notes' => 'Ready for field planting within 2 weeks.',
            ],
            [
                'species' => 'Acacia mangium',
                'origin' => 'SWK-SBU',
                'height_cm' => 58.30,
                'health_status' => 'healthy',
                'growth_stage' => 'juvenile',
                'tray_number' => null,
                'location' => 'Open Nursery Bed B',
                'potting_date' => Carbon::now()->subDays(115),
                'notes' => null,
            ],
            [
                'species' => 'Acacia mangium',
                'origin' => 'SBH-SDK',
                'height_cm' => 3.20,
                'health_status' => 'critical',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-AC-003',
                'location' => 'Quarantine Zone',
                'potting_date' => Carbon::now()->subDays(21),
                'notes' => 'Showing signs of damping-off disease. Isolated for treatment.',
            ],
            [
                'species' => 'Acacia mangium',
                'origin' => 'SBH-SDK',
                'height_cm' => 45.00,
                'health_status' => 'healthy',
                'growth_stage' => 'sapling',
                'tray_number' => null,
                'location' => 'Open Nursery Bed A',
                'potting_date' => Carbon::now()->subDays(90),
                'notes' => null,
                'is_sold' => true,
            ],
            [
                'species' => 'Acacia mangium',
                'origin' => 'SWK-SBU',
                'height_cm' => 52.00,
                'health_status' => 'healthy',
                'growth_stage' => 'juvenile',
                'tray_number' => null,
                'location' => 'Open Nursery Bed A',
                'potting_date' => Carbon::now()->subDays(100),
                'notes' => null,
            ],

            // ── Paraserianthes falcataria (Batai) ────────────────────────
            [
                'species' => 'Paraserianthes falcataria',
                'origin' => 'SWK-BTU',
                'height_cm' => 8.00,
                'health_status' => 'healthy',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-BT-001',
                'location' => 'Greenhouse A, Row 5',
                'potting_date' => Carbon::now()->subDays(18),
                'notes' => 'Fast initial growth observed.',
            ],
            [
                'species' => 'Paraserianthes falcataria',
                'origin' => 'SWK-BTU',
                'height_cm' => 7.50,
                'health_status' => 'healthy',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-BT-001',
                'location' => 'Greenhouse A, Row 5',
                'potting_date' => Carbon::now()->subDays(18),
                'notes' => null,
            ],
            [
                'species' => 'Paraserianthes falcataria',
                'origin' => 'SWK-BTU',
                'height_cm' => 35.00,
                'health_status' => 'healthy',
                'growth_stage' => 'sapling',
                'tray_number' => 'T-BT-002',
                'location' => 'Greenhouse B, Row 1',
                'potting_date' => Carbon::now()->subDays(55),
                'notes' => 'Bintulu provenance showing vigorous growth.',
            ],
            [
                'species' => 'Paraserianthes falcataria',
                'origin' => 'SWK-BTU',
                'height_cm' => 32.50,
                'health_status' => 'moderate',
                'growth_stage' => 'sapling',
                'tray_number' => 'T-BT-002',
                'location' => 'Greenhouse B, Row 1',
                'potting_date' => Carbon::now()->subDays(55),
                'notes' => 'Minor leaf yellowing — possible nutrient deficiency.',
            ],
            [
                'species' => 'Paraserianthes falcataria',
                'origin' => 'SWK-BTU',
                'height_cm' => 85.00,
                'health_status' => 'healthy',
                'growth_stage' => 'juvenile',
                'tray_number' => null,
                'location' => 'Open Nursery Bed C',
                'potting_date' => Carbon::now()->subDays(100),
                'notes' => 'Outpacing expected growth rate by 15%.',
            ],
            [
                'species' => 'Paraserianthes falcataria',
                'origin' => 'SWK-BTU',
                'height_cm' => 40.00,
                'health_status' => 'healthy',
                'growth_stage' => 'sapling',
                'tray_number' => null,
                'location' => 'Open Nursery Bed C',
                'potting_date' => Carbon::now()->subDays(70),
                'notes' => null,
            ],

            // ── Eusideroxylon zwageri (Belian — premium, slow-growing) ───
            [
                'species' => 'Eusideroxylon zwageri',
                'origin' => 'SWK-KCH',
                'height_cm' => 2.80,
                'health_status' => 'healthy',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-BL-001',
                'location' => 'Greenhouse B, Row 4 (Climate-Controlled)',
                'potting_date' => Carbon::now()->subDays(45),
                'notes' => 'Premium Belian seedling. Slow but steady germination.',
            ],
            [
                'species' => 'Eusideroxylon zwageri',
                'origin' => 'SWK-KCH',
                'height_cm' => 2.50,
                'health_status' => 'healthy',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-BL-001',
                'location' => 'Greenhouse B, Row 4 (Climate-Controlled)',
                'potting_date' => Carbon::now()->subDays(45),
                'notes' => null,
            ],
            [
                'species' => 'Eusideroxylon zwageri',
                'origin' => 'SWK-KCH',
                'height_cm' => 1.80,
                'health_status' => 'moderate',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-BL-001',
                'location' => 'Greenhouse B, Row 4 (Climate-Controlled)',
                'potting_date' => Carbon::now()->subDays(45),
                'notes' => 'Slower than siblings. Extra shade applied.',
            ],
            [
                'species' => 'Eusideroxylon zwageri',
                'origin' => 'SBH-KK',
                'height_cm' => 8.50,
                'health_status' => 'healthy',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-BL-002',
                'location' => 'Greenhouse B, Row 5 (Climate-Controlled)',
                'potting_date' => Carbon::now()->subDays(120),
                'notes' => 'Sabah provenance. Exceptional specimen for Belian.',
            ],
            [
                'species' => 'Eusideroxylon zwageri',
                'origin' => 'SBH-KK',
                'height_cm' => 15.00,
                'health_status' => 'healthy',
                'growth_stage' => 'sapling',
                'tray_number' => null,
                'location' => 'Shade House A',
                'potting_date' => Carbon::now()->subDays(240),
                'notes' => 'Rare Belian sapling — high conservation value. Handle with care.',
            ],
            [
                'species' => 'Eusideroxylon zwageri',
                'origin' => 'SWK-KCH',
                'height_cm' => 12.00,
                'health_status' => 'healthy',
                'growth_stage' => 'sapling',
                'tray_number' => null,
                'location' => 'Shade House A',
                'potting_date' => Carbon::now()->subDays(200),
                'notes' => null,
            ],

            // ── Shorea macrophylla (Engkabang Jantong) ───────────────────
            [
                'species' => 'Shorea macrophylla',
                'origin' => 'SWK-SBU',
                'height_cm' => 4.50,
                'health_status' => 'healthy',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-EJ-001',
                'location' => 'Greenhouse A, Row 8',
                'potting_date' => Carbon::now()->subDays(30),
                'notes' => 'Good germination from latest Sibu batch.',
            ],
            [
                'species' => 'Shorea macrophylla',
                'origin' => 'SWK-SBU',
                'height_cm' => 3.80,
                'health_status' => 'healthy',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-EJ-001',
                'location' => 'Greenhouse A, Row 8',
                'potting_date' => Carbon::now()->subDays(30),
                'notes' => null,
            ],
            [
                'species' => 'Shorea macrophylla',
                'origin' => 'SWK-BTU',
                'height_cm' => 18.00,
                'health_status' => 'healthy',
                'growth_stage' => 'sapling',
                'tray_number' => 'T-EJ-002',
                'location' => 'Greenhouse B, Row 2',
                'potting_date' => Carbon::now()->subDays(75),
                'notes' => 'Dipterocarp growth on track with expectations.',
            ],
            [
                'species' => 'Shorea macrophylla',
                'origin' => 'SWK-BTU',
                'height_cm' => 16.50,
                'health_status' => 'moderate',
                'growth_stage' => 'sapling',
                'tray_number' => 'T-EJ-002',
                'location' => 'Greenhouse B, Row 2',
                'potting_date' => Carbon::now()->subDays(75),
                'notes' => 'Slight pest damage on lower leaves. Treated with neem oil.',
            ],
            [
                'species' => 'Shorea macrophylla',
                'origin' => 'SWK-SBU',
                'height_cm' => 42.00,
                'health_status' => 'healthy',
                'growth_stage' => 'sapling',
                'tray_number' => null,
                'location' => 'Shade House B',
                'potting_date' => Carbon::now()->subDays(150),
                'notes' => 'Excellent canopy development. Candidate for reforestation project.',
            ],
            [
                'species' => 'Shorea macrophylla',
                'origin' => 'SBH-SDK',
                'height_cm' => 55.00,
                'health_status' => 'healthy',
                'growth_stage' => 'juvenile',
                'tray_number' => null,
                'location' => 'Shade House B',
                'potting_date' => Carbon::now()->subDays(180),
                'notes' => 'Sabah stock. Strong root system development.',
            ],
            [
                'species' => 'Shorea macrophylla',
                'origin' => 'SWK-SBU',
                'height_cm' => 30.00,
                'health_status' => 'healthy',
                'growth_stage' => 'sapling',
                'tray_number' => null,
                'location' => 'Shade House B',
                'potting_date' => Carbon::now()->subDays(110),
                'notes' => null,
            ],

            // ── Neolamarckia cadamba (Kelampayan) ────────────────────────
            [
                'species' => 'Neolamarckia cadamba',
                'origin' => 'SWK-KCH',
                'height_cm' => 10.00,
                'health_status' => 'healthy',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-KL-001',
                'location' => 'Greenhouse A, Row 10',
                'potting_date' => Carbon::now()->subDays(12),
                'notes' => 'Very rapid germination. Kelampayan living up to its reputation.',
            ],
            [
                'species' => 'Neolamarckia cadamba',
                'origin' => 'SWK-KCH',
                'height_cm' => 9.50,
                'health_status' => 'healthy',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-KL-001',
                'location' => 'Greenhouse A, Row 10',
                'potting_date' => Carbon::now()->subDays(12),
                'notes' => null,
            ],
            [
                'species' => 'Neolamarckia cadamba',
                'origin' => 'SWK-KCH',
                'height_cm' => 8.00,
                'health_status' => 'healthy',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-KL-001',
                'location' => 'Greenhouse A, Row 10',
                'potting_date' => Carbon::now()->subDays(12),
                'notes' => null,
            ],
            [
                'species' => 'Neolamarckia cadamba',
                'origin' => 'SWK-BTU',
                'height_cm' => 48.00,
                'health_status' => 'healthy',
                'growth_stage' => 'sapling',
                'tray_number' => null,
                'location' => 'Open Nursery Bed D',
                'potting_date' => Carbon::now()->subDays(50),
                'notes' => 'Fastest grower in the nursery this quarter.',
            ],
            [
                'species' => 'Neolamarckia cadamba',
                'origin' => 'SWK-BTU',
                'height_cm' => 42.50,
                'health_status' => 'healthy',
                'growth_stage' => 'sapling',
                'tray_number' => null,
                'location' => 'Open Nursery Bed D',
                'potting_date' => Carbon::now()->subDays(50),
                'notes' => null,
            ],
            [
                'species' => 'Neolamarckia cadamba',
                'origin' => 'SBH-SDK',
                'height_cm' => 110.00,
                'health_status' => 'healthy',
                'growth_stage' => 'juvenile',
                'tray_number' => null,
                'location' => 'Open Nursery Bed E',
                'potting_date' => Carbon::now()->subDays(90),
                'notes' => 'Exceptional height. Ready for immediate field deployment.',
            ],
            [
                'species' => 'Neolamarckia cadamba',
                'origin' => 'SBH-SDK',
                'height_cm' => 95.00,
                'health_status' => 'healthy',
                'growth_stage' => 'juvenile',
                'tray_number' => null,
                'location' => 'Open Nursery Bed E',
                'potting_date' => Carbon::now()->subDays(85),
                'notes' => null,
            ],
            [
                'species' => 'Neolamarckia cadamba',
                'origin' => 'SWK-KCH',
                'height_cm' => 6.00,
                'health_status' => 'critical',
                'growth_stage' => 'seedling',
                'tray_number' => 'T-KL-002',
                'location' => 'Quarantine Zone',
                'potting_date' => Carbon::now()->subDays(20),
                'notes' => 'Root rot detected. Under fungicide treatment.',
            ],
            [
                'species' => 'Neolamarckia cadamba',
                'origin' => 'SWK-BTU',
                'height_cm' => 70.00,
                'health_status' => 'healthy',
                'growth_stage' => 'juvenile',
                'tray_number' => null,
                'location' => 'Open Nursery Bed D',
                'potting_date' => Carbon::now()->subDays(65),
                'notes' => null,
            ],
            [
                'species' => 'Neolamarckia cadamba',
                'origin' => 'SWK-KCH',
                'height_cm' => 55.00,
                'health_status' => 'healthy',
                'growth_stage' => 'sapling',
                'tray_number' => null,
                'location' => 'Open Nursery Bed D',
                'potting_date' => Carbon::now()->subDays(55),
                'notes' => null,
            ],
        ];

        foreach ($plants as $data) {
            $speciesModel = $species[$data['species']] ?? null;
            $originModel = $origins[$data['origin']] ?? null;

            if (! $speciesModel || ! $originModel) {
                $this->command->warn("Skipping plant: species '{$data['species']}' or origin '{$data['origin']}' not found.");

                continue;
            }

            // Find a seed batch that matches the species and origin
            $batch = null;
            $speciesBatches = $seedBatches->get($speciesModel->id);
            if ($speciesBatches) {
                $batch = $speciesBatches->firstWhere('origin_id', $originModel->id)
                    ?? $speciesBatches->first();
            }

            $plant = Plant::create([
                'species_id' => $speciesModel->id,
                'seed_batch_id' => $batch?->id,
                'origin_id' => $originModel->id,
                'height_cm' => $data['height_cm'],
                'health_status' => $data['health_status'],
                'growth_stage' => $data['growth_stage'],
                'tray_number' => $data['tray_number'],
                'location' => $data['location'],
                'potting_date' => $data['potting_date'],
                'notes' => $data['notes'],
                'is_sold' => false,
            ]);

            // Record a stock movement for germination/potting
            StockMovement::create([
                'movable_type' => Plant::class,
                'movable_id' => $plant->id,
                'movement_type' => 'germination',
                'quantity' => 1,
                'reference_type' => $batch ? SeedBatch::class : null,
                'reference_id' => $batch?->id,
                'notes' => "Plant {$plant->uuid} germinated and potted.",
                'created_at' => $data['potting_date'],
            ]);
        }

        $this->command->info('Seeded '.count($plants).' plants with stock movements.');
    }
}
