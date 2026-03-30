<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OriginResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'region_code' => $this->region_code,
            'country' => $this->country,
            'description' => $this->description,
            'price_multiplier' => $this->whenLoaded('priceMultiplier', fn () => [
                'multiplier' => (float) $this->priceMultiplier->multiplier,
                'notes' => $this->priceMultiplier->notes,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
