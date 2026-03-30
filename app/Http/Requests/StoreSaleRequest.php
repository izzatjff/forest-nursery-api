<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_contact' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['nullable', 'in:cash,card,transfer'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_type' => ['required', 'in:seed_batch,plant'],
            'items.*.seed_batch_id' => ['required_if:items.*.item_type,seed_batch', 'nullable', 'exists:seed_batches,id'],
            'items.*.plant_uuid' => ['required_if:items.*.item_type,plant', 'nullable', 'exists:plants,uuid'],
            'items.*.quantity' => ['required_if:items.*.item_type,seed_batch', 'nullable', 'numeric', 'min:0.01'],
        ];
    }
}
