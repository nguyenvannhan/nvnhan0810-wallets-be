<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wallet\CreateWalletRequest;
use App\Http\Requests\Wallet\UpdateWalletRequest;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\WalletAccountAttribute;
use App\Services\WalletService;
use App\Types\TransactionTypes;
use App\Types\WalletAccountTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WalletController extends Controller
{
    private WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function index()
    {
        $wallets = Wallet::with('walletAccounts')->get();

        return view('wallet.index', [
            'wallets' => $wallets,
        ]);
    }

    public function show(Wallet $wallet)
    {
        $wallet->load(['walletAccounts']);

        return view('wallet.show', [
            'wallet' => $wallet,
        ]);
    }

    public function create()
    {
        $accounts = WalletAccountTypes::getList();

        return view('wallet.create', [
            'accounts' => $accounts,
        ]);
    }

    public function store(CreateWalletRequest $request)
    {
        $accounts = collect($request->accounts)->pluck('type');

        if ($accounts->count() !== $accounts->unique()->count()) {
            throw ValidationException::withMessages(['Account types must be unique']);
        }

        $this->walletService->createWallet($request->validated());

        return redirect()->route('wallets.index');
    }

    public function edit(Wallet $wallet)
    {
        $wallet->load(['walletAccounts']);

        $accounts = WalletAccountTypes::getList();

        return view('wallet.edit', [
            'wallet' => $wallet,
            'walletAccounts' => $wallet->walletAccounts,
            'accounts' => $accounts
        ]);
    }

    public function update(Wallet $wallet, UpdateWalletRequest $request)
    {
        $accounts = collect($request->accounts)->pluck('type');

        $this->walletService->updateWallet($wallet, $request->validated());

        return redirect()->route('wallets.show', $wallet->id);
    }

    public function creditPayment(Request $request)
    {
        $request->validate([
            'statement_id' => 'required|exists:wallet_account_attributes,id',
        ]);

        $statement = WalletAccountAttribute::find($request->statement_id);
        $walletAccount = $statement->walletAccount;

        if ($walletAccount->type !== WalletAccountTypes::TYPE_CREDIT) {
            throw new Exception('Wallet account is not credit');
        }

        DB::transaction(function () use ($walletAccount, $statement) {
            Transaction::create([
                'wallet_account_id' => $walletAccount->id,
                'type' => TransactionTypes::TYPE_INCOME,
                'amount' => $statement->value,
                'description' => 'Thanh toán sao kê thẻ tín dụng ngày ' . now()->format('d/m/Y'),
            ]);

            $walletAccount->update([
                'balance' => DB::raw('balance + ' . $statement->value),
            ]);

            $statement->value = 0;
            $statement->save();
        });

        return redirect()->route('wallets.show', $walletAccount->wallet_id);
    }
}
