<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePricingRuleRequest;
use App\Models\HeightPriceBracket;
use App\Models\OriginPriceMultiplier;
use App\Models\PricingRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricingController extends \App\Http\Controllers\Controller
{
    // --- Pricing Rules CRUD ---

    public function rules(Request $request): JsonResponse
    {
        $rules = PricingRule::when($request->active_only, fn ($q) => $q->active())
            ->when($request->entity_type, fn ($q, $t) => $q->forEntity($t))
            ->orderBy('priority')
            ->paginate($request->per_page ?? 15);

        return response()->json($rules);
    }

    public function storeRule(StorePricingRuleRequest $request): JsonResponse
    {
        $rule = PricingRule::create($request->validated());

        return response()->json(['data' => $rule], 201);
    }

    public function showRule(PricingRule $pricingRule): JsonResponse
    {
        return response()->json(['data' => $pricingRule]);
    }

    public function updateRule(StorePricingRuleRequest $request, PricingRule $pricingRule): JsonResponse
    {
        $pricingRule->update($request->validated());

        return response()->json(['data' => $pricingRule->fresh()]);
    }

    public function destroyRule(PricingRule $pricingRule): JsonResponse
    {
        $pricingRule->delete();

        return response()->json(['message' => 'Pricing rule deleted.']);
    }

    // --- Origin Multipliers ---

    public function originMultipliers(): JsonResponse
    {
        $multipliers = OriginPriceMultiplier::with('origin')->get();

        return response()->json(['data' => $multipliers]);
    }

    public function storeOriginMultiplier(Request $request): JsonResponse
    {
        $request->validate([
            'origin_id' => ['required', 'exists:origins,id'],
            'multiplier' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $multiplier = OriginPriceMultiplier::updateOrCreate(
            ['origin_id' => $request->origin_id],
            ['multiplier' => $request->multiplier, 'notes' => $request->notes],
        );

        return response()->json(['data' => $multiplier->load('origin')], 201);
    }

    // --- Height Brackets ---

    public function heightBrackets(Request $request): JsonResponse
    {
        $brackets = HeightPriceBracket::with('species')
            ->when($request->species_id, fn ($q, $id) => $q->where('species_id', $id))
            ->orderBy('min_height_cm')
            ->get();

        return response()->json(['data' => $brackets]);
    }

    public function storeHeightBracket(Request $request): JsonResponse
    {
        $request->validate([
            'species_id' => ['nullable', 'exists:species,id'],
            'min_height_cm' => ['required', 'numeric', 'min:0'],
            'max_height_cm' => ['nullable', 'numeric', 'gt:min_height_cm'],
            'multiplier' => ['required', 'numeric', 'min:0'],
        ]);

        $bracket = HeightPriceBracket::create($request->only([
            'species_id', 'min_height_cm', 'max_height_cm', 'multiplier',
        ]));

        return response()->json(['data' => $bracket->load('species')], 201);
    }

    public function destroyHeightBracket(HeightPriceBracket $heightBracket): JsonResponse
    {
        $heightBracket->delete();

        return response()->json(['message' => 'Height bracket deleted.']);
    }
}
