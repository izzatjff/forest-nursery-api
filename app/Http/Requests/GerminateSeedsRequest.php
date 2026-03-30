<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GerminateSeedsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'seed_batch_id' => ['required', 'exists:seed_batches,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'tray_number' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
        ];
    }
}
