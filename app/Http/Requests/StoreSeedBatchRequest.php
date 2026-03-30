<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeedBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'species_id' => ['required', 'exists:species,id'],
            'origin_id' => ['required', 'exists:origins,id'],
            'source_type' => ['required', 'in:purchased,wild_collected'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'collection_date' => ['required', 'date'],
            'initial_quantity' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['sometimes', 'in:pieces,grams,kg'],
            'storage_location' => ['nullable', 'string', 'max:255'],
            'viability_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
