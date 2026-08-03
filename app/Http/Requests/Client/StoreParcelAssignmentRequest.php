<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreParcelAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Ownership of the rider is enforced in ClientParcelAssignmentService,
            // which checks it against the signed-in company's own roster.
            'rider_id' => ['required', 'integer', 'exists:riders,id'],
            'date' => ['required', 'date'],
            'assigned' => ['required', 'integer', 'min:1', 'max:100000'],
        ];
    }

    public function messages(): array
    {
        return [
            'assigned.min' => 'Assign at least one parcel.',
        ];
    }
}
