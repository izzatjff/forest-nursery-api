<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SeedBatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'batch_code' => $this->batch_code,
            'species' => new SpeciesResource($this->whenLoaded('species')),
            'origin' => new OriginResource($this->whenLoaded('origin')),
            'source_type' => $this->source_type,
            'supplier_name' => $this->supplier_name,
            'collection_date' => $this->collection_date?->toDateString(),
            'initial_quantity' => (float) $this->initial_quantity,
            'remaining_quantity' => (float) $this->remaining_quantity,
            'unit' => $this->unit,
            'storage_location' => $this->storage_location,
            'viability_percentage' => $this->viability_percentage ? (float) $this->viability_percentage : null,
            'notes' => $this->notes,
            'qr_code_url' => $this->qr_code_path
                ? Storage::disk('public')->url($this->qr_code_path)
                : null,
            'plants_count' => $this->whenCounted('plants'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
