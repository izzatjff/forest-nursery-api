<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOriginRequest;
use App\Http\Requests\UpdateOriginRequest;
use App\Http\Resources\OriginResource;
use App\Models\Origin;
use App\Models\OriginPriceMultiplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OriginController extends \App\Http\Controllers\Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $origins = Origin::with('priceMultiplier')
            ->when($request->search, fn ($q, $search) => $q->where('name', 'like', "%{$search}%")->orWhere('region_code', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate($request->per_page ?? 15);

        return OriginResource::collection($origins);
    }

    public function store(StoreOriginRequest $request): JsonResponse
    {
        $origin = Origin::create($request->validated());

        return (new OriginResource($origin->load('priceMultiplier')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Origin $origin): OriginResource
    {
        return new OriginResource($origin->load('priceMultiplier'));
    }

    public function update(UpdateOriginRequest $request, Origin $origin): OriginResource
    {
        $origin->update($request->validated());

        return new OriginResource($origin->fresh()->load('priceMultiplier'));
    }

    public function destroy(Origin $origin): JsonResponse
    {
        $origin->delete();

        return response()->json(['message' => 'Origin deleted successfully.']);
    }

    /**
     * Set or update the price multiplier for an origin.
     */
    public function setMultiplier(Request $request, Origin $origin): JsonResponse
    {
        $request->validate([
            'multiplier' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        OriginPriceMultiplier::updateOrCreate(
            ['origin_id' => $origin->id],
            ['multiplier' => $request->multiplier, 'notes' => $request->notes],
        );

        return response()->json([
            'message' => 'Price multiplier updated.',
            'data' => new OriginResource($origin->fresh()->load('priceMultiplier')),
        ]);
    }
}
