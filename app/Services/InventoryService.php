<?php

namespace App\Services;

use App\Models\Plant;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SeedBatch;
use App\Models\Species;
use App\Models\StockMovement;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class InventoryService
{
    public function __construct(
        protected QrCodeService $qrCodeService,
        protected PricingEngine $pricingEngine,
    ) {}

    /**
     * Create a new seed batch from a procurement and generate its QR code.
     */
    public function createSeedBatch(array $data, ?int $performedBy = null): SeedBatch
    {
        return DB::transaction(function () use ($data, $performedBy) {
            $batch = SeedBatch::create($data);

            // Generate QR code
            $qrPath = $this->qrCodeService->generateForSeedBatch($batch->batch_code);
            $batch->update(['qr_code_path' => $qrPath]);

            // Log stock movement
            StockMovement::create([
                'movable_type' => SeedBatch::class,
                'movable_id' => $batch->id,
                'movement_type' => 'procurement_in',
                'quantity' => $batch->initial_quantity,
                'notes' => 'Initial stock from procurement',
                'performed_by' => $performedBy,
            ]);

            return $batch->fresh(['species', 'origin']);
        });
    }

    /**
     * Germinate seeds from a batch into individual plants.
     * Deducts from the seed batch remaining_quantity and creates Plant records.
     *
     * @return Collection<Plant>
     */
    public function germinateSeeds(SeedBatch $batch, int $quantity, array $plantDefaults = [], ?int $performedBy = null): Collection
    {
        if ($quantity > $batch->remaining_quantity) {
            throw new InvalidArgumentException(
                "Cannot germinate {$quantity} seeds. Only {$batch->remaining_quantity} remaining in batch {$batch->batch_code}."
            );
        }

        return DB::transaction(function () use ($batch, $quantity, $plantDefaults, $performedBy) {
            // Deduct from seed batch
            $batch->decrement('remaining_quantity', $quantity);

            // Log outbound movement from seed batch
            StockMovement::create([
                'movable_type' => SeedBatch::class,
                'movable_id' => $batch->id,
                'movement_type' => 'germination_out',
                'quantity' => -$quantity,
                'notes' => "Germinated {$quantity} seeds into plants",
                'performed_by' => $performedBy,
            ]);

            // Create individual plants
            $plants = collect();
            for ($i = 0; $i < $quantity; $i++) {
                $plant = Plant::create(array_merge([
                    'species_id' => $batch->species_id,
                    'seed_batch_id' => $batch->id,
                    'origin_id' => $batch->origin_id,
                    'health_status' => 'healthy',
                    'growth_stage' => 'seedling',
                    'potting_date' => now()->toDateString(),
                ], $plantDefaults));

                // Generate QR code for the plant
                $qrPath = $this->qrCodeService->generateForPlant($plant->uuid);
                $plant->update(['qr_code_path' => $qrPath]);

                // Log inbound movement for plant
                StockMovement::create([
                    'movable_type' => Plant::class,
                    'movable_id' => $plant->id,
                    'movement_type' => 'germination_in',
                    'quantity' => 1,
                    'reference_type' => SeedBatch::class,
                    'reference_id' => $batch->id,
                    'notes' => "Germinated from batch {$batch->batch_code}",
                    'performed_by' => $performedBy,
                ]);

                $plants->push($plant->fresh(['species', 'origin']));
            }

            return $plants;
        });
    }

    /**
     * Process a sale with multiple items.
     * Deducts stock and creates all necessary records.
     */
    public function processSale(array $saleData, array $items, ?int $soldBy = null): Sale
    {
        return DB::transaction(function () use ($saleData, $items, $soldBy) {
            $sale = Sale::create(array_merge($saleData, [
                'sold_by' => $soldBy,
                'sold_at' => $saleData['sold_at'] ?? now(),
            ]));

            $subtotal = 0;

            foreach ($items as $itemData) {
                $saleItem = $this->processSaleItem($sale, $itemData, $soldBy);
                $subtotal += (float) $saleItem->subtotal;
            }

            $taxRate = 0; // configurable
            $taxAmount = round($subtotal * $taxRate, 2);

            $sale->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $subtotal + $taxAmount,
            ]);

            return $sale->fresh(['items.seedBatch', 'items.plant', 'seller']);
        });
    }

    /**
     * Process a single sale item within a sale transaction.
     */
    protected function processSaleItem(Sale $sale, array $itemData, ?int $soldBy): SaleItem
    {
        if ($itemData['item_type'] === 'seed_batch') {
            return $this->processSeedBatchSaleItem($sale, $itemData, $soldBy);
        } elseif ($itemData['item_type'] === 'plant') {
            return $this->processPlantSaleItem($sale, $itemData, $soldBy);
        }

        throw new InvalidArgumentException("Unknown item type: {$itemData['item_type']}");
    }

    protected function processSeedBatchSaleItem(Sale $sale, array $itemData, ?int $soldBy): SaleItem
    {
        $batch = SeedBatch::findOrFail($itemData['seed_batch_id']);
        $quantity = (float) $itemData['quantity'];

        if ($quantity > $batch->remaining_quantity) {
            throw new InvalidArgumentException(
                "Insufficient stock in batch {$batch->batch_code}. Requested: {$quantity}, Available: {$batch->remaining_quantity}"
            );
        }

        // Calculate price
        $pricing = $this->pricingEngine->calculateSeedPrice($batch);

        $calculatedPrice = $pricing['calculated_price'];
        $itemSubtotal = round($calculatedPrice * $quantity, 2);

        // Deduct stock
        $batch->decrement('remaining_quantity', $quantity);

        // Log movement
        StockMovement::create([
            'movable_type' => SeedBatch::class,
            'movable_id' => $batch->id,
            'movement_type' => 'sale_out',
            'quantity' => -$quantity,
            'reference_type' => Sale::class,
            'reference_id' => $sale->id,
            'notes' => "Sold in sale {$sale->sale_number}",
            'performed_by' => $soldBy,
        ]);

        return SaleItem::create([
            'sale_id' => $sale->id,
            'item_type' => 'seed_batch',
            'seed_batch_id' => $batch->id,
            'quantity' => $quantity,
            'unit_price' => $pricing['unit_price'],
            'calculated_price' => $calculatedPrice,
            'subtotal' => $itemSubtotal,
            'price_breakdown' => $pricing['breakdown'],
        ]);
    }

    protected function processPlantSaleItem(Sale $sale, array $itemData, ?int $soldBy): SaleItem
    {
        $plant = Plant::where('uuid', $itemData['plant_uuid'])->firstOrFail();

        if ($plant->is_sold) {
            throw new InvalidArgumentException(
                "Plant {$plant->uuid} has already been sold."
            );
        }

        // Calculate price
        $pricing = $this->pricingEngine->calculatePlantPrice($plant);

        // Mark as sold
        $plant->update(['is_sold' => true]);

        // Log movement
        StockMovement::create([
            'movable_type' => Plant::class,
            'movable_id' => $plant->id,
            'movement_type' => 'sale_out',
            'quantity' => -1,
            'reference_type' => Sale::class,
            'reference_id' => $sale->id,
            'notes' => "Sold in sale {$sale->sale_number}",
            'performed_by' => $soldBy,
        ]);

        return SaleItem::create([
            'sale_id' => $sale->id,
            'item_type' => 'plant',
            'plant_id' => $plant->id,
            'quantity' => 1,
            'unit_price' => $pricing['unit_price'],
            'calculated_price' => $pricing['calculated_price'],
            'subtotal' => $pricing['calculated_price'],
            'price_breakdown' => $pricing['breakdown'],
        ]);
    }

    /**
     * Get dashboard statistics.
     */
    public function getDashboardStats(): array
    {
        return [
            'total_seed_batches' => SeedBatch::count(),
            'total_seeds_in_stock' => (float) SeedBatch::sum('remaining_quantity'),
            'total_plants' => Plant::where('is_sold', false)->count(),
            'total_plants_sold' => Plant::where('is_sold', true)->count(),
            'total_species' => Species::count(),
            'total_sales' => Sale::count(),
            'total_revenue' => (float) Sale::sum('total_amount'),
            'revenue_this_month' => (float) Sale::whereMonth('sold_at', now()->month)
                ->whereYear('sold_at', now()->year)
                ->sum('total_amount'),
            'sales_this_month' => Sale::whereMonth('sold_at', now()->month)
                ->whereYear('sold_at', now()->year)
                ->count(),
        ];
    }

    /**
     * Get low stock alerts.
     */
    public function getLowStockAlerts(): array
    {
        $lowStockBatches = SeedBatch::with('species')
            ->whereColumn('remaining_quantity', '<=', DB::raw(
                '(SELECT low_stock_threshold FROM species WHERE species.id = seed_batches.species_id)'
            ))
            ->where('remaining_quantity', '>', 0)
            ->get()
            ->map(fn ($batch) => [
                'type' => 'seed_batch',
                'batch_code' => $batch->batch_code,
                'species' => $batch->species->name,
                'remaining' => (float) $batch->remaining_quantity,
                'threshold' => $batch->species->low_stock_threshold,
            ])
            ->values();

        // Species with no available plants
        $speciesWithNoPlants = Species::whereDoesntHave('plants', function ($q) {
            $q->where('is_sold', false);
        })
            ->get()
            ->map(fn ($species) => [
                'type' => 'no_plants',
                'species' => $species->name,
                'message' => 'No unsold plants available',
            ])
            ->values();

        return collect($lowStockBatches)->merge($speciesWithNoPlants)->toArray();
    }
}
