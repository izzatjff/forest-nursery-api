<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlantRequest extends FormRequest
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
            'seed_batch_id' => ['nullable', 'exists:seed_batches,id'],
            'height_cm' => ['nullable', 'numeric', 'min:0'],
            'health_status' => ['sometimes', 'in:healthy,diseased,damaged,dead'],
            'growth_stage' => ['sometimes', 'in:seedling,juvenile,mature,ready_for_sale'],
            'tray_number' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'potting_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
