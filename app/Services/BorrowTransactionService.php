<?php

namespace App\Services;

use App\Models\BorrowTransaction;
use App\Models\Transaction;
use App\Types\BorrowTransactionTypes;
use App\Types\TransactionAttributeTypes;
use App\Types\TransactionTypes;
use Illuminate\Support\Facades\DB;

class BorrowTransactionService {
    public function createTransaction(array $data)
    {
        DB::transaction(function() use ($data) {
            $borrow = BorrowTransaction::where('friend_id', $data['friend_id'])
                ->where('type', $data['type'])
                ->first();

            if (!$borrow) {
                $borrow = BorrowTransaction::create([
                    'type' => $data['type'],
                    'description' => $data['description'] ?? '',
                    'amount' => $data['amount'],
                    'transaction_date' => $data['transaction_date'],
                    'wallet_account_id' => $data['wallet_account_id'],
                    'friend_id' => $data['friend_id'] ?? null,
                ]);
            } else {
                $borrow->amount += ($data['type'] === BorrowTransactionTypes::TYPE_BORROW ? 1 : -1) * $data['amount'];
                $borrow->transaction_date = $data['transaction_date'];
                $borrow->save();
            }

            $tranasctionData = [
                'amount' => $data['amount'],
                'wallet_account_id' => $data['wallet_account_id'],
            ];

            if ($borrow->type === BorrowTransactionTypes::TYPE_BORROW) {
                $tranasctionData['type'] = TransactionTypes::TYPE_INCOME;
                $tranasctionData['description'] = 'Đi mượn - #' . $borrow->id . ' - ' . $borrow->ammount_currency;
            } else {
                $tranasctionData['type'] = TransactionTypes::TYPE_EXPENSE;
                $tranasctionData['description'] = 'Cho mượn - #' . $borrow->id . ' - ' . $borrow->ammount_currency;
            }

            $transaction = Transaction::create($tranasctionData);

            if ($tranasctionData['type'] === TransactionTypes::TYPE_INCOME) {
                $balanceQuery = ' + ';
            } else {
                $balanceQuery = ' - ';
            }

            $transaction->walletAccount->update([
                'balance' => DB::raw('balance ' . $balanceQuery . ' ' . $transaction->amount),
            ]);

            $transaction->transactionAttributes()->create([
                'key' => TransactionAttributeTypes::BORROW_LEND_ID,
                'value' => $borrow->id,
            ]);
        });
    }

    public function updateTransaction(BorrowTransaction $borrow, array $data)
    {
        DB::transaction(function() use ($borrow, $data) {
            $borrow->amount -= $data['amount'];
            $borrow->save();

            $tranasctionData = [
                'amount' => $data['amount'],
                'wallet_account_id' => $data['wallet_account_id'],
            ];

            if ($borrow->type === BorrowTransactionTypes::TYPE_BORROW) {
                $tranasctionData['type'] = TransactionTypes::TYPE_EXPENSE;
                $tranasctionData['description'] = 'Trả nợ - #' . $borrow->id . ' - ' . $borrow->ammount_currency;
            } else {
                $tranasctionData['type'] = TransactionTypes::TYPE_INCOME;
                $tranasctionData['description'] = 'Trả nợ - #' . $borrow->id . ' - ' . $borrow->ammount_currency;
            }

            $transaction = Transaction::create($tranasctionData);

            if ($tranasctionData['type'] === TransactionTypes::TYPE_INCOME) {
                $balanceQuery = ' + ';
            } else {
                $balanceQuery = ' - ';
            }

            $transaction->walletAccount->update([
                'balance' => DB::raw('balance ' . $balanceQuery . ' ' . $transaction->amount),
            ]);

            $transaction->transactionAttributes()->create([
                'key' => TransactionAttributeTypes::BORROW_LEND_ID,
                'value' => $borrow->id,
            ]);
        });
    }
}
