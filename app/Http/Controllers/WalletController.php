<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wallet\CreateWalletRequest;
use App\Http\Requests\Wallet\UpdateWalletRequest;
use App\Models\Wallet;
use App\Services\WalletService;
use App\Types\WalletAccountTypes;
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

        if ($accounts->count() !== $accounts->unique()->count()) {
            throw ValidationException::withMessages(['Account types must be unique']);
        }

        $this->walletService->updateWallet($wallet, $request->validated());

        return redirect()->route('wallets.show', $wallet->id);
    }
}
