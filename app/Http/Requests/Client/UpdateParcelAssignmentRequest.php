<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParcelAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // The rider and date are fixed once assigned — withdraw and
            // re-assign to move a load to a different rider or day.
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
