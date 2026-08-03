<?php

namespace App\Http\Requests\Rider\Portal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $riderId = Auth::guard('rider')->id();

        return [
            // Riders may correct their contact details; status, company and
            // licence stay under admin control.
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('riders', 'email')->ignore($riderId)],
            'phone' => ['required', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
