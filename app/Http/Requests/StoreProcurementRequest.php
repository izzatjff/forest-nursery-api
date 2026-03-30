<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_name' => ['required', 'string', 'max:255'],
            'supplier_contact' => ['nullable', 'string', 'max:255'],
            'species_id' => ['required', 'exists:species,id'],
            'origin_id' => ['required', 'exists:origins,id'],
            'source_type' => ['required', 'in:purchased,wild_collected,donated'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['sometimes', 'in:pieces,grams,kg'],
            'cost_per_unit' => ['nullable', 'numeric', 'min:0'],
            'received_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
