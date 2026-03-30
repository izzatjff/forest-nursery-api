<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProcurementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'procurement_number' => $this->procurement_number,
            'supplier_name' => $this->supplier_name,
            'supplier_contact' => $this->supplier_contact,
            'species' => new SpeciesResource($this->whenLoaded('species')),
            'origin' => new OriginResource($this->whenLoaded('origin')),
            'source_type' => $this->source_type,
            'quantity' => (float) $this->quantity,
            'unit' => $this->unit,
            'cost_per_unit' => (float) $this->cost_per_unit,
            'total_cost' => (float) $this->total_cost,
            'received_date' => $this->received_date?->toDateString(),
            'seed_batch' => new SeedBatchResource($this->whenLoaded('seedBatch')),
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
