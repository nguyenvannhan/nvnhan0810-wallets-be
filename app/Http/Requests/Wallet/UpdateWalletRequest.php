<?php

namespace App\Http\Requests\Wallet;

use App\Types\WalletAccountTypes;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWalletRequest extends FormRequest
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
            'name' => 'required|string',
            'accounts' => 'required|array|min:1',
            'accounts.*.id' => 'nullable|exists:wallet_accounts,id',
            'accounts.*.type' => 'in:' . implode(',', array_keys(WalletAccountTypes::getList())),
            'accounts.*.balance' => 'nullable',
        ];
    }
}
