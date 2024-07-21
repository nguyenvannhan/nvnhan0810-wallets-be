<?php
namespace App\Services;

use App\Models\Transaction;
use App\Types\TransactionTypes;
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

                Transaction::where('id', $transaction->id)->update($data);

                $transaction->refresh();

                $transaction->walletAccount->update([
                    'balance' => DB::raw('balance ' . $newOperator . ' ' . $transaction->amount),
                ]);
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
