<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Models\InstallmentTransaction;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\WalletAccount;
use App\Services\TransactionService;
use App\Types\TransactionAttributeTypes;
use App\Types\TransactionTypes;
use App\Types\WalletAccountTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Psy\Readline\Transient;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index() {
        return view('transaction.index', [
            'transactions' => collect([]),
        ]);
    }

    public function loadData(Request $request) {
        $isLoop = false;
        $filters = [];
        do {
            if ($isLoop) {
                $filters['start_date'] = Carbon::parse($filters['start_date'])->subDays(7);
            } else {
                $filters = $request->all();
            }

            $transactions = Transaction::filter($filters)->orderBy('created_at', 'DESC')->get();

            $hasMore = Transaction::filter([
                'end_date' => $filters['start_date'],
                'wallet_id' => $request->wallet_id,
            ])->count() > 0;

            $isLoop = true;
        } while ($hasMore && $transactions->isEmpty());

        if ($transactions->isNotEmpty()) {
            $transactions->load(['walletAccount.wallet', 'transactionAttributes']);
        }

        $transactions = $transactions->map(function ($transaction) {
            $paidAttribute = $transaction->transactionAttributes->where('key', TransactionAttributeTypes::INSTALLMENT_PAID_STATUS)->first();

            $transaction->paid_status = $paidAttribute ? $paidAttribute->value : true;
            return $transaction;
        });

        return response()->json([
            'transaction' => view('transaction.components.item-on-wallet-detail', [
                'transactions' => $transactions,
            ])->render(),
            'has_more' => $hasMore,
        ]);
    }

    public function show(Transaction $transaction)
    {
        return view('transaction.show', [
            'transaction' => $transaction,
        ]);
    }

    public function create() {
        $types = TransactionTypes::getTypeList();
        $wallets = Wallet::with('walletAccounts')->get();
        $walletAccountTypes = WalletAccountTypes::getList();

        return view('transaction.create', [
            'types' => $types,
            'wallets' => $wallets,
            'walletAccountTypes' => $walletAccountTypes,
        ]);
    }

    public function store(CreateTransactionRequest $request)
    {
        $this->transactionService->createTransaction($request->validated());

        return redirect()->route('transactions.index');
    }

    public function edit(Transaction $transaction)
    {
        $types = TransactionTypes::getTypeList();
        $wallets = Wallet::with('walletAccounts')->get();
        $walletAccountTypes = WalletAccountTypes::getList();

        $transaction->load(['walletAccount']);

        return view('transaction.edit', [
            'types' => $types,
            'wallets' => $wallets,
            'walletAccountTypes' => $walletAccountTypes,
            'transaction' => $transaction,
        ]);
    }

    public function update(Transaction $transaction, UpdateTransactionRequest $request)
    {
        $this->transactionService->updateTransaction($transaction , $request->validated());

        return redirect()->route('transactions.index');
    }

    public function destroy(Transaction $transaction)
    {
        $this->transactionService->deleteTransaction($transaction);

        return redirect()->route('transactions.index');
    }

    public function showPayInstallment(Transaction $transaction)
    {
        $wallets = Wallet::with(['walletAccounts' => function($eager) {
            $eager->where('type', '<>', WalletAccountTypes::TYPE_CREDIT);
        }])->get();
        $walletAccountTypes = WalletAccountTypes::getList();

        return view('transaction.pay-installment', [
            'transaction' => $transaction,
            'wallets' => $wallets,
            'walletAccountTypes' => $walletAccountTypes,
        ]);
    }

    public function payInstallment(Request $request)
    {
        DB::transaction(function() use ($request) {
            $request->validate([
                'transaction_id' => 'required|exists:transactions,id',
                'wallet_account_id' => 'required|exists:wallet_accounts,id',
            ]);

            $transaction = Transaction::with(['transactionAttributes'])->findOrFail($request->transaction_id);

            $attribute = $transaction->transactionAttributes->where('key', TransactionAttributeTypes::INSTALLMENT_PAID_STATUS)->first();
            $installAttribute = $transaction->transactionAttributes->where('key', TransactionAttributeTypes::INSTALLMENT_ID)->first();

            if (!$attribute || $attribute->value || !$installAttribute) {
                throw ValidationException::withMessages([
                    'Atribute not found',
                ]);
            }

            $attribute->value = true;
            $attribute->save();

            $installment = InstallmentTransaction::findOrFail($installAttribute->value);

            $nextDate = Carbon::parse($installment->next_paid_date);
            $nextDate->addMonthNoOverflow();
            $startDate = Carbon::parse($installment->start_paid_date);

            $nextDay = (int) $nextDate->format('d');
            $lastDay = (int) $nextDate->clone()->endOfMonth()->format('d');
            $startDay = (int) $startDate->format('d');

            if ($nextDay < $startDay && $lastDay > $startDay) {
                $nextDate->day($startDay);
            }

            $installment->next_paid_date = $nextDate;
            $installment->remain_months = $installment->remain_months - 1;
            $installment->save();

            $walletAccount = WalletAccount::findOrFail($request->wallet_account_id);

            if ($walletAccount->type === WalletAccountTypes::TYPE_CREDIT || $walletAccount->balance < $transaction->amount) {
                throw ValidationException::withMessages([
                    'Wallet not have enough blance or is credit',
                ]);
            }

            $transaction->wallet_account_id = $request->wallet_account_id;
            $transaction->save();

            $walletAccount->balance = $walletAccount->balance - $transaction->amount;
            $walletAccount->save();
        });

        return redirect()->route('transactions.index');
    }
}
