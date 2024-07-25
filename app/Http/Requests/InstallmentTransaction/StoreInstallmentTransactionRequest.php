<?php

namespace App\Http\Requests\InstallmentTransaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstallmentTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'monthly_amount' => 'required|numeric|min:1',
            'wallet_account_id' => 'nullable|numeric|exists:wallet_accounts,id',
            'start_paid_date' => 'required|date',
            'total_months' => 'required|numeric|min:1|gte:remain_months',
            'remain_months' => 'required|numeric|min:1',
        ];
    }
}
