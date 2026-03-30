<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SaleController extends \App\Http\Controllers\Controller
{
    public function __construct(
        protected InventoryService $inventoryService,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $sales = Sale::with(['items.seedBatch.species', 'items.plant.species', 'seller'])
            ->when($request->search, fn ($q, $s) => $q->where('sale_number', 'like', "%{$s}%")->orWhere('customer_name', 'like', "%{$s}%"))
            ->when($request->date_from, fn ($q, $d) => $q->whereDate('sold_at', '>=', $d))
            ->when($request->date_to, fn ($q, $d) => $q->whereDate('sold_at', '<=', $d))
            ->orderByDesc('sold_at')
            ->paginate($request->per_page ?? 15);

        return SaleResource::collection($sales);
    }

    public function store(StoreSaleRequest $request): JsonResponse
    {
        $sale = $this->inventoryService->processSale(
            $request->only(['customer_name', 'customer_contact', 'payment_method', 'notes']),
            $request->items,
            $request->user()?->id,
        );

        return (new SaleResource($sale->load(['items.seedBatch.species', 'items.plant.species', 'seller'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Sale $sale): SaleResource
    {
        return new SaleResource(
            $sale->load(['items.seedBatch.species', 'items.plant.species', 'seller'])
        );
    }
}
