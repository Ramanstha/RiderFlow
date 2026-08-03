<?php

namespace App\Http\Requests\Company;

use App\Enums\CompanyStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->route('company')?->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('companies', 'name')->ignore($companyId)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('companies', 'email')->ignore($companyId)],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            // Blank means "leave the existing password alone" — see CompanyService.
            'password' => ['nullable', 'string', 'confirmed', 'required_with:password_confirmation', Password::defaults()],
            'status' => ['required', Rule::enum(CompanyStatus::class)],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (filled($this->input('password')) && blank($this->input('email'))) {
                $validator->errors()->add('email', 'An email address is required to give this company portal access.');
            }
        });
    }
}
