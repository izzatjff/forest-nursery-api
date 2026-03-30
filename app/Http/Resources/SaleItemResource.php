<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_type' => $this->item_type,
            'seed_batch' => new SeedBatchResource($this->whenLoaded('seedBatch')),
            'plant' => new PlantResource($this->whenLoaded('plant')),
            'quantity' => (float) $this->quantity,
            'unit_price' => (float) $this->unit_price,
            'calculated_price' => (float) $this->calculated_price,
            'subtotal' => (float) $this->subtotal,
            'price_breakdown' => $this->price_breakdown,
        ];
    }
}
