<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePricingRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Convert JSON string criteria from textarea to array
        if (is_string($this->criteria) && ! empty($this->criteria)) {
            $decoded = json_decode($this->criteria, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge(['criteria' => $decoded]);
            }
        }

        // Ensure is_active is cast to boolean
        if ($this->has('is_active')) {
            $this->merge(['is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN)]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'rule_type' => ['required', 'in:seasonal,bulk_discount,special_event,custom,discount,markup'],
            'entity_type' => ['required', 'in:seed,plant,both,seed_batch,all'],
            'criteria' => ['nullable', 'array'],
            'multiplier' => ['sometimes', 'numeric', 'min:0'],
            'flat_adjustment' => ['sometimes', 'numeric'],
            'priority' => ['sometimes', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ];
    }
}
