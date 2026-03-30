<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'height_cm' => ['sometimes', 'numeric', 'min:0'],
            'health_status' => ['sometimes', 'in:healthy,diseased,damaged,dead'],
            'growth_stage' => ['sometimes', 'in:seedling,juvenile,mature,ready_for_sale'],
            'tray_number' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
