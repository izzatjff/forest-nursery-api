<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PlantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'species' => new SpeciesResource($this->whenLoaded('species')),
            'seed_batch' => new SeedBatchResource($this->whenLoaded('seedBatch')),
            'origin' => new OriginResource($this->whenLoaded('origin')),
            'height_cm' => (float) $this->height_cm,
            'health_status' => $this->health_status,
            'growth_stage' => $this->growth_stage,
            'tray_number' => $this->tray_number,
            'location' => $this->location,
            'potting_date' => $this->potting_date?->toDateString(),
            'notes' => $this->notes,
            'is_sold' => $this->is_sold,
            'qr_code_url' => $this->qr_code_path
                ? Storage::disk('public')->url($this->qr_code_path)
                : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
