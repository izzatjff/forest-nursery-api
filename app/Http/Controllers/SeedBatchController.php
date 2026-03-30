<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeedBatchRequest;
use App\Http\Requests\UpdateSeedBatchRequest;
use App\Http\Resources\SeedBatchResource;
use App\Models\SeedBatch;
use App\Services\InventoryService;
use App\Services\PricingEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SeedBatchController extends \App\Http\Controllers\Controller
{
    public function __construct(
        protected InventoryService $inventoryService,
        protected PricingEngine $pricingEngine,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $batches = SeedBatch::with(['species', 'origin'])
            ->withCount('plants')
            ->when($request->species_id, fn ($q, $id) => $q->where('species_id', $id))
            ->when($request->origin_id, fn ($q, $id) => $q->where('origin_id', $id))
            ->when($request->source_type, fn ($q, $type) => $q->where('source_type', $type))
            ->when($request->search, fn ($q, $s) => $q->where('batch_code', 'like', "%{$s}%"))
            ->when($request->in_stock, fn ($q) => $q->where('remaining_quantity', '>', 0))
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 15);

        return SeedBatchResource::collection($batches);
    }

    public function store(StoreSeedBatchRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['remaining_quantity'] = $data['initial_quantity'];

        $batch = $this->inventoryService->createSeedBatch($data);

        return (new SeedBatchResource($batch->load(['species', 'origin'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(SeedBatch $seedBatch): SeedBatchResource
    {
        return new SeedBatchResource(
            $seedBatch->load(['species', 'origin'])->loadCount('plants')
        );
    }

    public function update(UpdateSeedBatchRequest $request, SeedBatch $seedBatch): SeedBatchResource
    {
        $seedBatch->update($request->validated());

        return new SeedBatchResource($seedBatch->fresh(['species', 'origin']));
    }

    public function destroy(SeedBatch $seedBatch): JsonResponse
    {
        $seedBatch->delete();

        return response()->json(['message' => 'Seed batch deleted successfully.']);
    }

    /**
     * Get the dynamic price calculation for this batch.
     */
    public function price(SeedBatch $seedBatch): JsonResponse
    {
        $seedBatch->load(['species', 'origin']);
        $pricing = $this->pricingEngine->calculateSeedPrice($seedBatch);

        return response()->json(['data' => $pricing]);
    }
}
