<?php

namespace App\Http\Requests\BorrowTransaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBorrowTransactionRequest extends FormRequest
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
        $borrow = request()->borrow;

        return [
            'amount' => 'required|numeric|min:1|max:' . $borrow->amount,
            'transaction_date' => 'nullable|date',
            'wallet_account_id' => 'required|numeric|exists:wallet_accounts,id',
        ];
    }
}
