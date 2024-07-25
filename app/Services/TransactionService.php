<?php
namespace App\Services;

use App\Models\Transaction;
use App\Models\WalletAccountAttribute;
use App\Types\TransactionTypes;
use App\Types\WalletAccountAttributeTypes;
use App\Types\WalletAccountTypes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TransactionService {
    public function createTransaction(array $data)
    {
        DB::transaction(function() use ($data) {
            $transaction = Transaction::create($data);

            if ($data['type'] === TransactionTypes::TYPE_INCOME) {
                $balanceQuery = ' + ';
            } else {
                $balanceQuery = ' - ';
            }

            $transaction->walletAccount->update([
                'balance' => DB::raw('balance ' . $balanceQuery . ' ' . $transaction->amount),
            ]);

            if ($transaction->walletAccount->type === WalletAccountTypes::TYPE_CREDIT) {
                $attribute = WalletAccountAttribute::where('wallet_account_id', $transaction->walletAccount->id)
                    ->where('key', WalletAccountAttributeTypes::CREDIT_STATEMENT_AMOUNT)
                    ->first();

                if (!$attribute) {
                    WalletAccountAttribute::create([
                        'wallet_account_id' => $transaction->walletAccount->id,
                        'key' => WalletAccountAttributeTypes::CREDIT_STATEMENT_AMOUNT,
                        'value' => $transaction->amount,
                    ]);
                } else {
                    $attribute->value += $transaction->amount;
                    $attribute->save();
                }
            }

            return $transaction;
        });
    }

    public function updateTransaction(Transaction $transaction, array $data)
    {

        Cache::lock('lock-update-wallet', 10)->block(
            10,
            function () use ($transaction, $data) {
            DB::transaction(function () use ($transaction, $data) {
                $oldOperator = ($transaction->type === TransactionTypes::TYPE_INCOME ? ' - ' : ' + ');
                $newOperator = ($data['type'] === TransactionTypes::TYPE_INCOME ? ' + ' : ' - ');

                $transaction->walletAccount->update([
                    'balance' => DB::raw('balance ' . $oldOperator . ' ' . $transaction->amount),
                ]);

                if ($transaction->type === TransactionTypes::TYPE_EXPENSE && $transaction->walletAccount->type === WalletAccountTypes::TYPE_CREDIT) {
                    $attribute = WalletAccountAttribute::where('wallet_account_id', $transaction->wallet_account_id)
                        ->where('key', WalletAccountAttributeTypes::CREDIT_STATEMENT_AMOUNT)
                        ->first();

                    if (!$attribute) {
                        throw new Exception('Credit statement not found');
                    }

                    $attribute->value -= $transaction->amount;
                    $attribute->save();
                }

                Transaction::where('id', $transaction->id)->update($data);

                $transaction->refresh();

                $transaction->walletAccount->update([
                    'balance' => DB::raw('balance ' . $newOperator . ' ' . $transaction->amount),
                ]);

                if ($transaction->type === TransactionTypes::TYPE_EXPENSE && $transaction->walletAccount->type === WalletAccountTypes::TYPE_CREDIT) {
                    $attribute = WalletAccountAttribute::where('wallet_account_id', $transaction->wallet_account_id)
                        ->where('key', WalletAccountAttributeTypes::CREDIT_STATEMENT_AMOUNT)
                        ->first();

                    if (!$attribute) {
                        WalletAccountAttribute::create([
                            'wallet_account_id' => $transaction->wallet_account_id,
                            'key' => WalletAccountAttributeTypes::CREDIT_STATEMENT_AMOUNT,
                            'value' => $transaction->amount,
                        ]);
                    } else {
                        $attribute->value += $transaction->amount;
                        $attribute->save();
                    }
                }
            });
        });
    }

    public function deleteTransaction(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            $transaction->walletAccount()->update([
                'balance' => DB::raw(
                    'balance ' . ($transaction->type === TransactionTypes::TYPE_INCOME ? ' - ' : ' + ') . $transaction->amount
                ),
            ]);

            $transaction->delete();
        });
    }
}
