<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowTransaction\StoreBorrowTransactionRequest;
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
        return view('borrow.index');
    }

    public function create()
    {
        $typeList = BorrowTransactionTypes::getTypeList();

        $wallets = Wallet::with(['walletAccounts'])->get();

        $walletAccountTypes = WalletAccountTypes::getList();

        return view('borrow.create', [
            'typeList' => $typeList,
            'wallets' => $wallets,
            'walletAccountTypes' => $walletAccountTypes
        ]);
    }

    public function store(StoreBorrowTransactionRequest $request)
    {
        $this->borrowTransactionService->createTransaction($request->validated());

        return redirect()->route('borrows.index');
    }
}
