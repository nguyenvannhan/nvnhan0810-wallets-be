<?php

namespace App\Services;

use App\Models\BorrowTransaction;
use App\Models\Debt;
use App\Types\BorrowTransactionTypes;
use App\Types\DebtAttributeTypes;
use App\Types\DebtTypes;
use App\Types\TransactionAttributeTypes;
use App\Types\TransactionTypes;
use Illuminate\Support\Facades\DB;

class BorrowTransactionService {
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function createTransaction(array $data)
    {
        DB::transaction(function() use ($data) {
            $borrow = BorrowTransaction::create([
                'type' => $data['type'],
                'description' => $data['description'] ?? '',
                'amount' => $data['amount'],
                'transaction_date' => $data['transaction_date'],
                'wallet_account_id' => $data['wallet_account_id'],
                'friend_id' => $data['friend_id'] ?? null,
            ]);

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

            $transaction = $this->transactionService->createTransaction($tranasctionData);

            $transaction->transactionAttributes()->create([
                'key' => TransactionAttributeTypes::BORROW_LEND_ID,
                'value' => $borrow->id,
            ]);
        });
    }
}
