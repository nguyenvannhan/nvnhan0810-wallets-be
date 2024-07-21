<?php

namespace App\Http\Requests\BorrowTransaction;

use App\Types\BorrowTransactionTypes;
use App\Types\DebtTypes;
use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowTransactionRequest extends FormRequest
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
            'type' => 'required|string|in:' . implode(',', array_keys(BorrowTransactionTypes::getTypeList())),
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:1',
            'wallet_account_id' => 'required|numeric|exists:wallet_accounts,id',
            'transaction_date' => 'required|date',
            'friend_id' => 'nullable|numeric|exists:friends,id',
        ];
    }
}
