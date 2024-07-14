<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\TransactionService;
use App\Types\TransactionTypes;
use App\Types\WalletAccountTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Psy\Readline\Transient;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request) {
        $isLoop = false;
        $filters = [];
        do {
            if ($isLoop) {
                $filters['start_date'] = Carbon::parse($filters['start_date'])->subDays(7);
            } else {
                $filters = $request->all();
            }

            $transactions = Transaction::filter($filters)->get();

            $hasMore = Transaction::filter([
                'end_date' => $filters['start_date'],
                'wallet_id' => $request->wallet_id,
            ])->count() > 0;

            $isLoop = true;
        } while ($hasMore && $transactions->isEmpty());

        if ($transactions->isNotEmpty()) {
            $transactions->load(['walletAccount.wallet']);
        }

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
        return view('transaction.edit', [
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
}
