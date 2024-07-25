<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\WalletAccount;
use App\Models\WalletAccountAttribute;
use App\Types\TransactionTypes;
use App\Types\WalletAccountAttributeTypes;
use App\Types\WalletAccountTypes;
use Illuminate\Support\Facades\DB;

class WalletService {
    public function createWallet(array $data)
    {
        DB::transaction(function() use ($data) {
            $wallet = Wallet::create([
                'name' => $data['name']
            ]);

            $accounts = [];

            foreach($data['accounts'] as $account) {
                $accounts[] = [
                    'wallet_id' => $wallet->id,
                    'type' => $account['type'],
                    'balance' => $account['balance'],
                    'name' => $account['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            WalletAccount::insert($accounts);
        });
    }

    public function updateWallet(Wallet $wallet, array $data)
    {
        DB::transaction(function () use ($wallet, $data) {
            $wallet->name = $data['name'];
            $wallet->save();

            $accounts = [];
            $dbAccounts = $wallet->walletAccounts;

            foreach ($data['accounts'] as $account) {
                $dbAccount = null;

                if (isset($account['id'])) {
                    $dbAccount = $dbAccounts->find($account['id']);
                }

                if (!$dbAccount) {
                    $accounts[] = WalletAccount::create([
                        'wallet_id' => $wallet->id,
                        'type' => $account['type'],
                        'balance' => $account['balance'],
                        'name' => $account['name'],
                    ]);
                } else {
                    if ($dbAccount->balance != $account['balance']) {
                        Transaction::create([
                            'wallet_account_id' => $dbAccount->id,
                            'amount' => abs($account['balance'] - $dbAccount->balance),
                            'type' => $account['balance'] > $dbAccount->balance ? TransactionTypes::TYPE_INCOME : TransactionTypes::TYPE_EXPENSE,
                            'description' => 'Cân đối Ví',
                        ]);

                        $walletStatement = WalletAccountAttribute::where('wallet_account_id', $dbAccount->id)
                            ->where('key', WalletAccountAttributeTypes::CREDIT_STATEMENT_AMOUNT)
                            ->first();

                        if ($dbAccount->type === WalletAccountTypes::TYPE_CREDIT) {
                            if ($account['balance'] > $dbAccount->balance) {
                                if ($walletStatement) {
                                    $walletStatement->value -= abs($account['balance'] - $dbAccount->balance);
                                    $walletStatement->save();
                                }
                            } else {
                                if ($walletStatement) {
                                    $walletStatement->value += abs($account['balance'] - $dbAccount->balance);
                                    $walletStatement->save();
                                } else {
                                    WalletAccountAttribute::create([
                                        'wallet_account_id' => $dbAccount->id,
                                        'key' => WalletAccountAttributeTypes::CREDIT_STATEMENT_AMOUNT,
                                        'value' => abs($account['balance'] - $dbAccount->balance),
                                    ]);
                                }
                            }
                        }
                    }

                    $dbAccount->balance = $account['balance'];
                    $dbAccount->type = $account['type'];
                    $dbAccount->name = $account['name'];
                    $dbAccount->save();
                }
            }

            $transactions = [];
            foreach($accounts as $account) {
                if ($account->balance > 0) {
                    $transactions[] = [
                        'wallet_account_id' => $account->id,
                        'amount' => abs($account->balance),
                        'type' => $account->balance > 0 ? TransactionTypes::TYPE_INCOME : TransactionTypes::TYPE_EXPENSE,
                        'description' => 'Tạo mới Ví',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (count($transactions)) {
                Transaction::insert($transactions);
            }
        });
    }
}
