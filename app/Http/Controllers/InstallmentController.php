<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstallmentTransaction\StoreInstallmentTransactionRequest;
use App\Models\InstallmentTransaction;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\WalletAccount;
use App\Types\TransactionAttributeTypes;
use App\Types\TransactionTypes;
use App\Types\WalletAccountTypes;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InstallmentController extends Controller
{
    public function index()
    {
        $installments = InstallmentTransaction::where('remain_months', '>', 0)
            ->orderBy('remain_months', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('installment.index', [
            'installments' => $installments,
        ]);
    }

    public function create()
    {
        $wallets = Wallet::with([
            'walletAccounts' => function ($accountQuery) {
                $accountQuery->where('type', WalletAccountTypes::TYPE_CREDIT);
            },
        ])
            ->whereHas('walletAccounts', function($accountQuery) {
                $accountQuery->where('type', WalletAccountTypes::TYPE_CREDIT);
            })
            ->get();

        $walletAccountTypes = WalletAccountTypes::getList();

        return view('installment.create', [
            'wallets' => $wallets,
            'walletAccountTypes' => $walletAccountTypes,
        ]);
    }

    public function store(StoreInstallmentTransactionRequest $request)
    {
        DB::transaction(function() use ($request) {
            $data = $request->validated();

            $startDate = Carbon::parse($request->start_paid_date);
            if (now()->startOfDay()->gt($startDate)) {
                $data['next_paid_date'] = now()->day((int) $startDate->format('d'))->addMonthNoOverflow();
            } else {
                $data['next_paid_date'] = $startDate;
            }

            $installment = InstallmentTransaction::create($data);

            if ($request->wallet_account_id) {
                $transaction = Transaction::create([
                    'type' => TransactionTypes::TYPE_EXPENSE,
                    'amount' => $installment->monthly_amount * $installment->remain_months,
                    'description' => '#' . $installment->id . ' : ' . $installment->name,
                    'wallet_account_id' => $installment->wallet_account_id,
                ]);

                $transaction->transactionAttributes()->create([
                    'key' => TransactionAttributeTypes::INSTALLMENT_ID,
                    'value' => $installment->id,
                ]);

                $transaction->walletAccount->update([
                    'balance' => DB::raw('balance - ' . $transaction->amount),
                ]);
            }
        });

        return redirect()->route('installments.index');
    }

    public function createTransaction(int $id)
    {
        DB::transaction(function() use ($id) {
            $installment = InstallmentTransaction::findOrFail($id);

            $transactions = Transaction::with(['transactionAttributes'])->get();

            $transactions = $transactions->filter(function($item) use ($installment) {
                $idAttrObj = $item->transactionAttributes->where('key', TransactionAttributeTypes::INSTALLMENT_ID)->first();

                if ($idAttrObj?->value != $installment->id) {
                    return false;
                }

                $idAttrObj = $item->transactionAttributes->where('key', TransactionAttributeTypes::INSTALLMENT_PAID_DATE)->first();
                if ($idAttrObj?->value != $installment->next_paid_date) {
                    return false;
                }

                $statusObj = $item->transactionAttributes->where('key', TransactionAttributeTypes::INSTALLMENT_PAID_STATUS)->first();
                if ($statusObj?->value) {
                    return false;
                }

                return true;
            });

            if ($transactions->isNotEmpty()) {
                return back()->withErrors(['Khoản trả góp này có giao dịch chưa thanh toán']);
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
        });

        return redirect()->route('installments.index')->with('create-transaction-success', true);
    }
}
