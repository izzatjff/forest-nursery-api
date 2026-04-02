<?php

namespace Database\Seeders;

use App\Models\Plant;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SeedBatch;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $batches = SeedBatch::with(['species', 'origin', 'origin.priceMultiplier'])->get()->keyBy('batch_code');

        // Pre-load unsold healthy plants grouped by seed batch code
        $plantsByBatch = [];
        $allPlants = Plant::where('is_sold', false)
            ->where('health_status', 'healthy')
            ->with(['species', 'origin', 'origin.priceMultiplier', 'seedBatch'])
            ->orderByDesc('height_cm')
            ->get();

        foreach ($allPlants as $plant) {
            $code = $plant->seedBatch->batch_code ?? 'unknown';
            $plantsByBatch[$code][] = $plant;
        }

        // Helper to pull N plants from a batch group
        $pickPlants = function (string $batchCode, int $count) use (&$plantsByBatch): array {
            $picked = [];
            for ($i = 0; $i < $count; $i++) {
                if (! empty($plantsByBatch[$batchCode])) {
                    $picked[] = array_shift($plantsByBatch[$batchCode]);
                }
            }

            return $picked;
        };

        // ══════════════════════════════════════════════════════════════════
        //  SALE 1 — Government Reforestation Contract (bulk)
        //  Customer: Sarawak Forestry Corporation
        //  Items: 3 Acacia saplings + 2 Kelampayan saplings + 500 Acacia seeds
        // ══════════════════════════════════════════════════════════════════

        $sale1Items = [];
        $sale1Plants = [];

        // 3 Acacia plants from SB-2025-00001
        foreach ($pickPlants('SB-2025-00001', 3) as $plant) {
            $item = $this->buildPlantItem($plant);
            $sale1Items[] = $item;
            $sale1Plants[] = $plant;
        }

        // 2 Kelampayan plants from SB-2025-00007
        foreach ($pickPlants('SB-2025-00007', 2) as $plant) {
            $item = $this->buildPlantItem($plant);
            $sale1Items[] = $item;
            $sale1Plants[] = $plant;
        }

        // 500 Acacia seeds
        $sale1Items[] = $this->buildSeedItem($batches['SB-2025-00001'], 500);

        $sale1 = $this->createSale(
            saleNumber: 'INV-2025-00001',
            customerName: 'Sarawak Forestry Corporation',
            customerContact: '+60 82-610 088',
            items: $sale1Items,
            paymentMethod: 'bank_transfer',
            notes: 'Government reforestation programme order — Batang Rajang corridor project. Official PO ref: SFC/RFP/2025/0342.',
            soldAt: '2025-03-15 09:30:00',
            taxRate: 0.06,
        );

        $this->markPlantsSold($sale1Plants, $sale1);
        $this->decrementSeedBatch($batches['SB-2025-00001'], 500, $sale1);

        // ══════════════════════════════════════════════════════════════════
        //  SALE 2 — Private Landowner
        //  Customer: Encik Ahmad bin Yusof
        //  Items: 2 Engkabang saplings + 1 Belian seedling
        // ══════════════════════════════════════════════════════════════════

        $sale2Items = [];
        $sale2Plants = [];

        // 2 Engkabang from SB-2024-00002
        foreach ($pickPlants('SB-2024-00002', 2) as $plant) {
            $sale2Items[] = $this->buildPlantItem($plant, speciesBracket: 'engkabang');
            $sale2Plants[] = $plant;
        }

        // 1 Belian from SB-2024-00001
        foreach ($pickPlants('SB-2024-00001', 1) as $plant) {
            $sale2Items[] = $this->buildPlantItem($plant, speciesBracket: 'belian');
            $sale2Plants[] = $plant;
        }

        $sale2 = $this->createSale(
            saleNumber: 'INV-2025-00002',
            customerName: 'Encik Ahmad bin Yusof',
            customerContact: '+60 13-884 2201',
            items: $sale2Items,
            paymentMethod: 'cash',
            notes: 'Walk-in customer. Planting on private land along Sungai Igan for erosion control and fruit trees.',
            soldAt: '2025-03-20 14:15:00',
            taxRate: 0.06,
        );

        $this->markPlantsSold($sale2Plants, $sale2);

        // ══════════════════════════════════════════════════════════════════
        //  SALE 3 — Community Nursery (seed order, tax exempt)
        //  Customer: Kampung Nanga Merit Community Nursery
        //  Items: 200 Batai seeds + 100 Kelampayan seeds
        // ══════════════════════════════════════════════════════════════════

        $sale3Items = [];

        $sale3Items[] = $this->buildSeedItem($batches['SB-2025-00003'], 200);
        $sale3Items[] = $this->buildSeedItem($batches['SB-2025-00007'], 100);

        $sale3 = $this->createSale(
            saleNumber: 'INV-2025-00003',
            customerName: 'Kampung Nanga Merit Community Nursery',
            customerContact: '+60 84-796 112',
            items: $sale3Items,
            paymentMethod: 'bank_transfer',
            notes: 'Community-based reforestation initiative. SST exempt under community programme. Seeds for village nursery establishment.',
            soldAt: '2025-03-25 10:00:00',
            taxRate: 0.00,
        );

        $this->decrementSeedBatch($batches['SB-2025-00003'], 200, $sale3);
        $this->decrementSeedBatch($batches['SB-2025-00007'], 100, $sale3);

        // ══════════════════════════════════════════════════════════════════
        //  SALE 4 — University Research Order
        //  Customer: UNIMAS Faculty of Resource Science
        //  Items: 1 Belian seedling + 1 Engkabang seedling + 50 Engkabang seeds
        // ══════════════════════════════════════════════════════════════════

        $sale4Items = [];
        $sale4Plants = [];

        // 1 Belian from SB-2025-00004
        foreach ($pickPlants('SB-2025-00004', 1) as $plant) {
            $sale4Items[] = $this->buildPlantItem($plant, speciesBracket: 'belian');
            $sale4Plants[] = $plant;
        }

        // 1 Engkabang from SB-2025-00005
        foreach ($pickPlants('SB-2025-00005', 1) as $plant) {
            $sale4Items[] = $this->buildPlantItem($plant, speciesBracket: 'engkabang');
            $sale4Plants[] = $plant;
        }

        // 50 Engkabang seeds (Kinabalu provenance)
        $sale4Items[] = $this->buildSeedItem($batches['SB-2025-00005'], 50);

        $sale4 = $this->createSale(
            saleNumber: 'INV-2025-00004',
            customerName: 'UNIMAS Faculty of Resource Science',
            customerContact: '+60 82-583 000',
            items: $sale4Items,
            paymentMethod: 'bank_transfer',
            notes: 'Research order for provenance trial study. PI: Dr. Lim Chien Yong, Grant ref: FRGS/2025/UNIMAS/ST04.',
            soldAt: '2025-04-02 11:00:00',
            taxRate: 0.06,
        );

        $this->markPlantsSold($sale4Plants, $sale4);
        $this->decrementSeedBatch($batches['SB-2025-00005'], 50, $sale4);

        // ══════════════════════════════════════════════════════════════════
        //  SALE 5 — Walk-in Retail (small purchase)
        //  Customer: Puan Siti Nurhaliza binti Abdullah
        //  Items: 1 Kelampayan sapling + 1 Batai sapling
        // ══════════════════════════════════════════════════════════════════

        $sale5Items = [];
        $sale5Plants = [];

        // 1 Kelampayan from SB-2025-00006
        foreach ($pickPlants('SB-2025-00006', 1) as $plant) {
            $sale5Items[] = $this->buildPlantItem($plant);
            $sale5Plants[] = $plant;
        }

        // 1 Batai from SB-2025-00003
        foreach ($pickPlants('SB-2025-00003', 1) as $plant) {
            $sale5Items[] = $this->buildPlantItem($plant);
            $sale5Plants[] = $plant;
        }

        $sale5 = $this->createSale(
            saleNumber: 'INV-2025-00005',
            customerName: 'Puan Siti Nurhaliza binti Abdullah',
            customerContact: '+60 11-1098 7654',
            items: $sale5Items,
            paymentMethod: 'cash',
            notes: 'Walk-in retail purchase. Planting for home garden shade trees.',
            soldAt: '2025-04-05 16:45:00',
            taxRate: 0.06,
        );

        $this->markPlantsSold($sale5Plants, $sale5);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Helper Methods
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Build a sale-item array for a single plant.
     */
    private function buildPlantItem(Plant $plant, ?string $speciesBracket = null): array
    {
        $basePrice = (float) $plant->species->base_price;
        $originMult = (float) ($plant->origin->priceMultiplier->multiplier ?? 1.000);
        $heightMult = match ($speciesBracket) {
            'belian' => $this->getBelianHeightMultiplier((float) $plant->height_cm),
            'engkabang' => $this->getEngkabangHeightMultiplier((float) $plant->height_cm),
            default => $this->getHeightMultiplier((float) $plant->height_cm),
        };

        $calculatedPrice = round($basePrice * $originMult * $heightMult, 2);

        return [
            'item_type' => 'plant',
            'plant_id' => $plant->id,
            'seed_batch_id' => null,
            'quantity' => 1,
            'unit_price' => $basePrice,
            'calculated_price' => $calculatedPrice,
            'subtotal' => $calculatedPrice,
            'price_breakdown' => [
                'base_price' => $basePrice,
                'origin_multiplier' => $originMult,
                'height_multiplier' => $heightMult,
                'height_cm' => (float) $plant->height_cm,
                'species_specific_bracket' => $speciesBracket !== null,
                'final_price' => $calculatedPrice,
            ],
        ];
    }

    /**
     * Build a sale-item array for a seed batch quantity.
     */
    private function buildSeedItem(SeedBatch $batch, int $quantity): array
    {
        $basePrice = (float) $batch->species->base_price;
        $originMult = (float) ($batch->origin->priceMultiplier->multiplier ?? 1.000);
        $unitCalc = round($basePrice * $originMult, 2);
        $subtotal = round($unitCalc * $quantity, 2);

        return [
            'item_type' => 'seed_batch',
            'plant_id' => null,
            'seed_batch_id' => $batch->id,
            'quantity' => $quantity,
            'unit_price' => $basePrice,
            'calculated_price' => $unitCalc,
            'subtotal' => $subtotal,
            'price_breakdown' => [
                'base_price' => $basePrice,
                'origin_multiplier' => $originMult,
                'quantity' => $quantity,
                'unit_calculated' => $unitCalc,
                'subtotal' => $subtotal,
            ],
        ];
    }

    /**
     * Create a Sale record and its SaleItem records.
     */
    private function createSale(
        string $saleNumber,
        string $customerName,
        ?string $customerContact,
        array $items,
        string $paymentMethod,
        string $notes,
        string $soldAt,
        float $taxRate,
    ): Sale {
        $subtotal = collect($items)->sum('subtotal');
        $taxAmount = round($subtotal * $taxRate, 2);
        $totalAmount = round($subtotal + $taxAmount, 2);

        $sale = Sale::create([
            'sale_number' => $saleNumber,
            'customer_name' => $customerName,
            'customer_contact' => $customerContact,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
            'notes' => $notes,
            'sold_by' => null,
            'sold_at' => Carbon::parse($soldAt),
        ]);

        foreach ($items as $item) {
            SaleItem::create(array_merge($item, ['sale_id' => $sale->id]));
        }

        return $sale;
    }

    /**
     * Mark plants as sold and log stock movements.
     */
    private function markPlantsSold(array $plants, Sale $sale): void
    {
        foreach ($plants as $plant) {
            $plant->update(['is_sold' => true]);

            StockMovement::create([
                'movable_type' => Plant::class,
                'movable_id' => $plant->id,
                'movement_type' => 'sold',
                'quantity' => -1,
                'reference_type' => Sale::class,
                'reference_id' => $sale->id,
                'notes' => "Sold in {$sale->sale_number} to {$sale->customer_name}",
                'performed_by' => null,
                'created_at' => $sale->sold_at,
            ]);
        }
    }

    /**
     * Decrement seed batch remaining quantity and log stock movement.
     */
    private function decrementSeedBatch(SeedBatch $batch, int $quantity, Sale $sale): void
    {
        $batch->decrement('remaining_quantity', $quantity);

        StockMovement::create([
            'movable_type' => SeedBatch::class,
            'movable_id' => $batch->id,
            'movement_type' => 'sold',
            'quantity' => -$quantity,
            'reference_type' => Sale::class,
            'reference_id' => $sale->id,
            'notes' => "{$quantity} {$batch->species->name} seeds sold in {$sale->sale_number}",
            'performed_by' => null,
            'created_at' => $sale->sold_at,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Height Multiplier Helpers (mirror PricingSeeder brackets)
    // ──────────────────────────────────────────────────────────────────────

    private function getHeightMultiplier(float $heightCm): float
    {
        return match (true) {
            $heightCm >= 200 => 6.000,
            $heightCm >= 100 => 4.000,
            $heightCm >= 60 => 2.500,
            $heightCm >= 30 => 1.750,
            $heightCm >= 15 => 1.250,
            default => 1.000,
        };
    }

    private function getBelianHeightMultiplier(float $heightCm): float
    {
        return match (true) {
            $heightCm >= 60 => 10.000,
            $heightCm >= 30 => 5.000,
            $heightCm >= 15 => 2.500,
            default => 1.500,
        };
    }

    private function getEngkabangHeightMultiplier(float $heightCm): float
    {
        return match (true) {
            $heightCm >= 60 => 3.500,
            $heightCm >= 30 => 2.000,
            default => 1.000,
        };
    }
}
