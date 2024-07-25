<?php

namespace App\Http\Controllers;

use App\Models\InstallmentTransaction;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $wallets = Wallet::with('walletAccounts')->get();

        $latestTransactions = Transaction::with(['walletAccount.wallet'])->latest()->take(5)->get();

        $totalInstallment = InstallmentTransaction::where('remain_months', '>', 0)->get()->reduce(function($result, $item) {
            return $result + ($item->monthly_amount * $item->remain_months);
        }, 0);

        return view('home.index', [
            'wallets' => $wallets,
            'totalInstallment' => $totalInstallment,
            'latestTransactions' => $latestTransactions,
        ]);
    }
}
