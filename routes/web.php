<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowTransactionController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');

Route::prefix('auth')->group(function() {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/callback', [AuthController::class, 'callback'])->name('callback');
});

Route::middleware('auth')->group(function() {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('wallets', WalletController::class);

    Route::get('/transactions/load', [TransactionController::class, 'loadData'])->name('transactions.load');

    Route::patch('transactions/pay-installment', [TransactionController::class, 'payInstallment'])->name('transactions.pay_installment');
    Route::resource('transactions', TransactionController::class);
    Route::get('transactions/{transaction}/pay-installment', [TransactionController::class, 'showPayInstallment'])->name('transactions.pay_installment_form');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    Route::resource('/borrows', BorrowTransactionController::class)->except(['show', 'destroy']);

    Route::resource('/friends', FriendController::class);

    Route::resource('/installments', InstallmentController::class)->only(['index', 'create', 'store']);
    Route::post('/installments/{id}/transactions/create', [InstallmentController::class, 'createTransaction'])->name('installements.transactions.create');
});
