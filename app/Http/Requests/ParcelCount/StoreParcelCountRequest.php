<?php

namespace App\Http\Requests\ParcelCount;

use Illuminate\Foundation\Http\FormRequest;

class StoreParcelCountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rider_id' => ['required', 'integer', 'exists:riders,id'],
            'date' => ['required', 'date'],
            'assigned' => ['required', 'integer', 'min:0'],
            'delivered' => ['nullable', 'integer', 'min:0'],
            'start_km' => ['sometimes', 'integer', 'min:0'],
            'end_km' => ['nullable', 'integer', 'min:0', 'gte:start_km'],
        ];
    }
}
