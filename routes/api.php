<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OriginController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SeedBatchController;
use App\Http\Controllers\SpeciesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Forest Nursery API Routes
|--------------------------------------------------------------------------
|
| Standalone API for the Forest Nursery Management System.
| All routes are prefixed with /api automatically.
|
*/

// ─── Dashboard ────────────────────────────────────────────────────────────────
Route::prefix('dashboard')->group(function () {
    Route::get('/stats', [DashboardController::class, 'stats']);
    Route::get('/low-stock', [DashboardController::class, 'lowStock']);
    Route::get('/recent-sales', [DashboardController::class, 'recentSales']);
});

// ─── Species ──────────────────────────────────────────────────────────────────
Route::apiResource('species', SpeciesController::class);

// ─── Origins ──────────────────────────────────────────────────────────────────
Route::apiResource('origins', OriginController::class);
Route::put('origins/{origin}/multiplier', [OriginController::class, 'setMultiplier']);

// ─── Seed Batches ─────────────────────────────────────────────────────────────
Route::apiResource('seed-batches', SeedBatchController::class);
Route::get('seed-batches/{seed_batch}/price', [SeedBatchController::class, 'price']);

// ─── Plants ───────────────────────────────────────────────────────────────────
Route::apiResource('plants', PlantController::class);
Route::get('plants/{plant}/price', [PlantController::class, 'price']);
Route::post('plants/germinate', [PlantController::class, 'germinate']);

// ─── Pricing Engine ───────────────────────────────────────────────────────────
Route::prefix('pricing')->group(function () {
    Route::get('/rules', [PricingController::class, 'rules']);
    Route::post('/rules', [PricingController::class, 'storeRule']);
    Route::get('/rules/{pricing_rule}', [PricingController::class, 'showRule']);
    Route::put('/rules/{pricing_rule}', [PricingController::class, 'updateRule']);
    Route::delete('/rules/{pricing_rule}', [PricingController::class, 'destroyRule']);

    Route::get('/origin-multipliers', [PricingController::class, 'originMultipliers']);
    Route::post('/origin-multipliers', [PricingController::class, 'storeOriginMultiplier']);

    Route::get('/height-brackets', [PricingController::class, 'heightBrackets']);
    Route::post('/height-brackets', [PricingController::class, 'storeHeightBracket']);
    Route::delete('/height-brackets/{height_bracket}', [PricingController::class, 'destroyHeightBracket']);
});

// ─── Sales (Point of Sale) ────────────────────────────────────────────────────
Route::apiResource('sales', SaleController::class)->only(['index', 'store', 'show']);

// ─── Procurements (Inbound) ───────────────────────────────────────────────────
Route::apiResource('procurements', ProcurementController::class)->only(['index', 'store', 'show']);

// ─── QR Code / Asset Resolution ───────────────────────────────────────────────
Route::prefix('assets')->group(function () {
    Route::get('/batch/{batchCode}', [AssetController::class, 'resolveBatch']);
    Route::get('/plant/{uuid}', [AssetController::class, 'resolvePlant']);
});
