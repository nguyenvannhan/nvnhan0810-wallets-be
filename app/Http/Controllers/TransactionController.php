<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\TransactionService;
use App\Types\TransactionTypes;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index() {
        $transactions = Transaction::all();

        return view('transaction.index', [
            'transactions' => $transactions,
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

        return view('transaction.create', [
            'types' => $types,
            'wallets' => $wallets,
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
