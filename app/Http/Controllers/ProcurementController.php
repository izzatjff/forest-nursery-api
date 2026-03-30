<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProcurementRequest;
use App\Http\Resources\ProcurementResource;
use App\Models\Procurement;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProcurementController extends \App\Http\Controllers\Controller
{
    public function __construct(
        protected InventoryService $inventoryService,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $procurements = Procurement::with(['species', 'origin', 'seedBatch'])
            ->when($request->search, fn ($q, $s) => $q->where('procurement_number', 'like', "%{$s}%")->orWhere('supplier_name', 'like', "%{$s}%"))
            ->orderByDesc('received_date')
            ->paginate($request->per_page ?? 15);

        return ProcurementResource::collection($procurements);
    }

    /**
     * Create a new procurement, which also creates a seed batch.
     */
    public function store(StoreProcurementRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Create the seed batch first
        $batch = $this->inventoryService->createSeedBatch([
            'species_id' => $validated['species_id'],
            'origin_id' => $validated['origin_id'],
            'source_type' => $validated['source_type'],
            'supplier_name' => $validated['supplier_name'],
            'collection_date' => $validated['received_date'],
            'initial_quantity' => $validated['quantity'],
            'remaining_quantity' => $validated['quantity'],
            'unit' => $validated['unit'] ?? 'pieces',
        ]);

        // Create the procurement record
        $procurement = Procurement::create(array_merge($validated, [
            'total_cost' => ($validated['cost_per_unit'] ?? 0) * $validated['quantity'],
            'seed_batch_id' => $batch->id,
        ]));

        return (new ProcurementResource($procurement->load(['species', 'origin', 'seedBatch'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Procurement $procurement): ProcurementResource
    {
        return new ProcurementResource(
            $procurement->load(['species', 'origin', 'seedBatch'])
        );
    }
}
