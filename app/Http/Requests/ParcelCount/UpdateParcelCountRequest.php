<?php

namespace App\Http\Requests\ParcelCount;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParcelCountRequest extends FormRequest
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
            'delivered' => ['required', 'integer', 'min:0'],
        ];
    }
}
