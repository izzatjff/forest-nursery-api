<?php

namespace App\Http\Controllers;

use App\Http\Requests\GerminateSeedsRequest;
use App\Http\Requests\StorePlantRequest;
use App\Http\Requests\UpdatePlantRequest;
use App\Http\Resources\PlantResource;
use App\Models\Plant;
use App\Models\SeedBatch;
use App\Services\InventoryService;
use App\Services\PricingEngine;
use App\Services\QrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlantController extends \App\Http\Controllers\Controller
{
    public function __construct(
        protected InventoryService $inventoryService,
        protected PricingEngine $pricingEngine,
        protected QrCodeService $qrCodeService,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $plants = Plant::with(['species', 'origin', 'seedBatch'])
            ->when($request->species_id, fn ($q, $id) => $q->where('species_id', $id))
            ->when($request->origin_id, fn ($q, $id) => $q->where('origin_id', $id))
            ->when($request->health_status, fn ($q, $s) => $q->where('health_status', $s))
            ->when($request->growth_stage, fn ($q, $s) => $q->where('growth_stage', $s))
            ->when($request->has('is_sold'), fn ($q) => $q->where('is_sold', $request->boolean('is_sold')))
            ->when($request->search, fn ($q, $s) => $q->where('uuid', 'like', "%{$s}%")->orWhere('tray_number', 'like', "%{$s}%"))
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 15);

        return PlantResource::collection($plants);
    }

    public function store(StorePlantRequest $request): JsonResponse
    {
        $plant = Plant::create($request->validated());

        // Generate QR code
        $qrPath = $this->qrCodeService->generateForPlant($plant->uuid);
        $plant->update(['qr_code_path' => $qrPath]);

        return (new PlantResource($plant->fresh(['species', 'origin', 'seedBatch'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Plant $plant): PlantResource
    {
        return new PlantResource(
            $plant->load(['species', 'origin', 'seedBatch'])
        );
    }

    public function update(UpdatePlantRequest $request, Plant $plant): PlantResource
    {
        $plant->update($request->validated());

        return new PlantResource($plant->fresh(['species', 'origin', 'seedBatch']));
    }

    public function destroy(Plant $plant): JsonResponse
    {
        $plant->delete();

        return response()->json(['message' => 'Plant deleted successfully.']);
    }

    /**
     * Germinate seeds from a batch into individual plants.
     */
    public function germinate(GerminateSeedsRequest $request): JsonResponse
    {
        $batch = SeedBatch::findOrFail($request->seed_batch_id);

        $plants = $this->inventoryService->germinateSeeds(
            $batch,
            $request->quantity,
            array_filter([
                'tray_number' => $request->tray_number,
                'location' => $request->location,
            ]),
        );

        return response()->json([
            'message' => "{$request->quantity} plants germinated from batch {$batch->batch_code}.",
            'data' => PlantResource::collection($plants),
        ], 201);
    }

    /**
     * Get the dynamic price calculation for this plant.
     */
    public function price(Plant $plant): JsonResponse
    {
        $plant->load(['species', 'origin']);
        $pricing = $this->pricingEngine->calculatePlantPrice($plant);

        return response()->json(['data' => $pricing]);
    }
}
