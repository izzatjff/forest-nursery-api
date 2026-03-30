<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlantResource;
use App\Http\Resources\SeedBatchResource;
use App\Models\Plant;
use App\Models\SeedBatch;
use App\Services\PricingEngine;
use Illuminate\Http\JsonResponse;

class AssetController extends \App\Http\Controllers\Controller
{
    public function __construct(
        protected PricingEngine $pricingEngine,
    ) {}

    /**
     * Resolve a QR-scanned batch code to its data.
     * GET /api/assets/batch/{batchCode}
     */
    public function resolveBatch(string $batchCode): JsonResponse
    {
        $batch = SeedBatch::with(['species', 'origin'])
            ->where('batch_code', $batchCode)
            ->firstOrFail();

        $pricing = $this->pricingEngine->calculateSeedPrice($batch);

        return response()->json([
            'asset_type' => 'seed_batch',
            'data' => new SeedBatchResource($batch),
            'pricing' => $pricing,
        ]);
    }

    /**
     * Resolve a QR-scanned plant UUID to its data.
     * GET /api/assets/plant/{uuid}
     */
    public function resolvePlant(string $uuid): JsonResponse
    {
        $plant = Plant::with(['species', 'origin', 'seedBatch'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $pricing = $this->pricingEngine->calculatePlantPrice($plant);

        return response()->json([
            'asset_type' => 'plant',
            'data' => new PlantResource($plant),
            'pricing' => $pricing,
        ]);
    }
}
