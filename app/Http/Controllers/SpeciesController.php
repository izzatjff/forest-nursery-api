<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpeciesRequest;
use App\Http\Requests\UpdateSpeciesRequest;
use App\Http\Resources\SpeciesResource;
use App\Models\Species;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpeciesController extends \App\Http\Controllers\Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $species = Species::withCount(['seedBatches', 'plants'])
            ->when($request->search, fn ($q, $search) => $q->where('name', 'like', "%{$search}%")->orWhere('scientific_name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate($request->per_page ?? 15);

        return SpeciesResource::collection($species);
    }

    public function store(StoreSpeciesRequest $request): JsonResponse
    {
        $species = Species::create($request->validated());

        return (new SpeciesResource($species))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Species $species): SpeciesResource
    {
        $species->loadCount(['seedBatches', 'plants']);

        return new SpeciesResource($species);
    }

    public function update(UpdateSpeciesRequest $request, Species $species): SpeciesResource
    {
        $species->update($request->validated());

        return new SpeciesResource($species->fresh());
    }

    public function destroy(Species $species): JsonResponse
    {
        $species->delete();

        return response()->json(['message' => 'Species deleted successfully.']);
    }
}
