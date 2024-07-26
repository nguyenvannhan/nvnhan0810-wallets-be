<?php

namespace App\Console\Commands;

use App\Models\InstallmentTransaction;
use App\Models\Transaction;
use App\Models\WalletAccount;
use App\Types\TransactionAttributeTypes;
use App\Types\TransactionTypes;
use App\Types\WalletAccountTypes;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateInstallmentPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-installment-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create installment payment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Create installment payment');

        $installments = InstallmentTransaction::where('remain_months', '>', 0)->get();

        foreach ($installments as $installment) {
            $transaction = Transaction::whereHas('transactionAttributes', function ($query) use ($installment) {
                $query->where(function($query) use ($installment) {
                    $query->where('key', TransactionAttributeTypes::INSTALLMENT_ID)
                        ->where('value', $installment->id);
                })->where(function($query) use ($installment) {
                    $query->where('key', TransactionAttributeTypes::INSTALLMENT_PAID_DATE)
                        ->where('value', $installment->next_paid_date);
                });
            })->first();

            if ($transaction || Carbon::create($installment->next_paid_date)->startOfDay()->diffInDays(now()->startOfDay()) > -14) {
                continue;
            }

            $defaultWalletAccount = WalletAccount::where('type', '<>', WalletAccountTypes::TYPE_CREDIT)
                ->first();

            $transaction = Transaction::create([
                'type' => TransactionTypes::TYPE_EXPENSE,
                'amount' => $installment->monthly_amount,
                'description' => 'Thanh toán trả góp #' . $installment->id . ' tháng thứ ' . $installment->total_months - $installment->remain_months,
                'wallet_account_id' => $defaultWalletAccount->id,
            ]);

            $transaction->transactionAttributes()->createMany([
                [
                    'key' => TransactionAttributeTypes::INSTALLMENT_ID,
                    'value' => $installment->id,
                ],
                [
                    'key' => TransactionAttributeTypes::INSTALLMENT_PAID_DATE,
                    'value' => $installment->next_paid_date,
                ],
                [
                    'key' => TransactionAttributeTypes::INSTALLMENT_PAID_STATUS,
                    'value' => false,
                ],
            ]);
        }

        $this->info('Create installment payment done');
    }
}
