<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSpeciesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $speciesId = $this->route('species');

        return [
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('species', 'name')->ignore($speciesId)],
            'scientific_name' => ['sometimes', 'string', 'max:255', Rule::unique('species', 'scientific_name')->ignore($speciesId)],
            'description' => ['nullable', 'string'],
            'base_price' => ['sometimes', 'numeric', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
