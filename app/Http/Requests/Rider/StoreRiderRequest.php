<?php

namespace App\Http\Requests\Rider;

use App\Enums\RiderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreRiderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:riders,email'],
            'phone' => ['required', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'license_number' => ['required', 'string', 'max:255', 'unique:riders,license_number'],
            'status' => ['required', Rule::enum(RiderStatus::class)],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'performance' => ['nullable', 'integer', 'min:0', 'max:100'],
            // Optional: leaving it blank simply means the rider cannot sign in
            // to the rider portal yet.
            'password' => ['nullable', 'string', 'confirmed', Password::defaults()],
        ];
    }
}
