<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOriginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $originId = $this->route('origin');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'region_code' => ['sometimes', 'string', 'max:50', Rule::unique('origins', 'region_code')->ignore($originId)],
            'country' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
