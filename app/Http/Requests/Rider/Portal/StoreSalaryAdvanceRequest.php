<?php

namespace App\Http\Requests\Rider\Portal;

use App\Enums\SalaryAdvanceStatus;
use App\Models\Rider;
use App\Services\RiderAttendanceService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class StoreSalaryAdvanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $amount = ['required', 'numeric', 'min:1', 'max:99999999.99'];

        // An advance cannot exceed what is still owed this month (earnings on Present
        // days less advances already approved) when pay is on file.
        $payable = app(RiderAttendanceService::class)->monthlyCalendar($this->rider())['payroll']['payable'];

        if ($payable !== null) {
            $amount[] = 'max:'.max(0, $payable);
        }

        return [
            // Riders request for themselves, so rider_id is never accepted from input.
            'amount' => $amount,
            'reason' => ['required', 'string', 'max:1000'],
            'needed_by' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.max' => 'The advance cannot be more than what you have earned and not yet received this month (Rs. :max).',
            'needed_by.after_or_equal' => 'The needed-by date cannot be in the past.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->rider()->salaryAdvances()->where('status', SalaryAdvanceStatus::Pending)->exists()) {
                    $validator->errors()->add('amount', 'You already have a pending advance request. Wait for it to be reviewed or cancel it first.');
                }
            },
        ];
    }

    protected function rider(): Rider
    {
        /** @var Rider $rider */
        $rider = Auth::guard('rider')->user();

        return $rider;
    }
}
