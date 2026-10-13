<?php

namespace App\Http\Requests\SalaryAdvance;

use App\Enums\SalaryAdvanceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewSalaryAdvanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('riders.manage');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in([SalaryAdvanceStatus::Approved->value, SalaryAdvanceStatus::Rejected->value])],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
