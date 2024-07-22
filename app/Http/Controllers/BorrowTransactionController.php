<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowTransaction\StoreBorrowTransactionRequest;
use App\Http\Requests\BorrowTransaction\UpdateBorrowTransactionRequest;
use App\Models\BorrowTransaction;
use App\Models\Friend;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\BorrowTransactionService;
use App\Types\BorrowTransactionTypes;
use App\Types\WalletAccountTypes;
use Illuminate\Http\Request;

class BorrowTransactionController extends Controller
{
    private BorrowTransactionService $borrowTransactionService;

    public function __construct(BorrowTransactionService $borrowTransactionService)
    {
        $this->borrowTransactionService = $borrowTransactionService;
    }

    public function index()
    {
        $transactions = BorrowTransaction::orderBy('transaction_date', 'desc')->get();

        return view('borrow.index', [
            'transactions' => $transactions,
        ]);
    }

    public function create()
    {
        $typeList = BorrowTransactionTypes::getTypeList();

        $wallets = Wallet::with(['walletAccounts'])->get();

        $walletAccountTypes = WalletAccountTypes::getList();

        $friends = Friend::all();

        return view('borrow.create', [
            'typeList' => $typeList,
            'wallets' => $wallets,
            'walletAccountTypes' => $walletAccountTypes,
            'friends' => $friends,
        ]);
    }

    public function store(StoreBorrowTransactionRequest $request)
    {
        $this->borrowTransactionService->createTransaction($request->validated());

        return redirect()->route('borrows.index');
    }

    public function edit(BorrowTransaction $borrow)
    {
        $wallets = Wallet::with(['walletAccounts'])->get();

        $walletAccountTypes = WalletAccountTypes::getList();

        return view('borrow.edit', [
            'wallets' => $wallets,
            'walletAccountTypes' => $walletAccountTypes,
            'borrow' => $borrow,
        ]);
    }

    public function update(BorrowTransaction $borrow, UpdateBorrowTransactionRequest $request)
    {
        $this->borrowTransactionService->updateTransaction($borrow, $request->validated());

        return redirect()->route('borrows.index');
    }
}
