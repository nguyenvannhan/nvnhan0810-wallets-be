<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $wallets = Wallet::with('walletAccounts')->get();

        $latestTransactions = Transaction::with(['walletAccount.wallet'])->latest()->take(5)->get();

        return view('home', [
            'wallets' => $wallets,
            'latestTransactions' => $latestTransactions,
        ]);
    }
}
