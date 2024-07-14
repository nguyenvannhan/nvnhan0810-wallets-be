<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletAccount;
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
            $deletedIds = [];
            $dbAccounts = $wallet->walletAccounts;

            foreach ($data['types'] as $type) {
                $dbAccount = $dbAccounts->where('type', $type)->first();

                if (!$dbAccount) {
                    $deletedIds = [$dbAccount->id];
                } else {
                    $accounts[] = [
                        'wallet_id' => $wallet->id,
                        'type' => $type,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            WalletAccount::insert($accounts);
            WalletAccount::whereIn('id', $deletedIds)->delete();
        });
    }
}
