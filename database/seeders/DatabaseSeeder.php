<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order matters — each seeder depends on the data created by previous ones:
     *   1. Species       → base catalog (no dependencies)
     *   2. Origins        → geographic provenances + price multipliers
     *   3. Pricing        → height brackets + pricing rules (depends on species)
     *   4. SeedBatches    → seed inventory (depends on species + origins)
     *   5. Plants         → individual seedlings (depends on species + origins + seed batches)
     *   6. Procurements   → inbound purchase records (depends on species + origins + seed batches)
     *   7. Sales          → completed transactions (depends on plants + seed batches)
     */
    public function run(): void
    {
        $this->call([
            SpeciesSeeder::class,
            OriginSeeder::class,
            PricingSeeder::class,
            SeedBatchSeeder::class,
            PlantSeeder::class,
            ProcurementSeeder::class,
            SaleSeeder::class,
        ]);
    }
}
