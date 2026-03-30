<?php

namespace App\Http\Controllers;

use App\Http\Resources\SaleResource;
use App\Models\Sale;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;

class DashboardController extends \App\Http\Controllers\Controller
{
    public function __construct(
        protected InventoryService $inventoryService,
    ) {}

    public function stats(): JsonResponse
    {
        return response()->json([
            'data' => $this->inventoryService->getDashboardStats(),
        ]);
    }

    public function lowStock(): JsonResponse
    {
        return response()->json([
            'data' => $this->inventoryService->getLowStockAlerts(),
        ]);
    }

    public function recentSales(): JsonResponse
    {
        $sales = Sale::with(['items.seedBatch.species', 'items.plant.species', 'seller'])
            ->orderByDesc('sold_at')
            ->limit(10)
            ->get();

        return response()->json([
            'data' => SaleResource::collection($sales),
        ]);
    }
}
