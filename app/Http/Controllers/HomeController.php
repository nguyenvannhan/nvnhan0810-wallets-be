<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $wallets = Wallet::with('walletAccounts')->get();

        return view('home', [
            'wallets' => $wallets,
        ]);
    }
}
