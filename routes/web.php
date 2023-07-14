<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/create-account', [AuthController::class, 'showCreateAccountForm'])->name('account.create');
    Route::post('/create-account', [AuthController::class, 'createAccount']);

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');   
    Route::get('/deposit', [TransactionController::class, 'showDepositForm'])->name('transactions.deposit');
    Route::post('/deposit', [TransactionController::class, 'deposit']);
    Route::get('/withdraw', [TransactionController::class, 'showWithdrawForm'])->name('transactions.withdraw');
    Route::post('/withdraw', [TransactionController::class, 'withdraw']);
    Route::get('/transfer', [TransactionController::class, 'showTransferForm'])->name('transactions.transfer');
    Route::post('/transfer', [TransactionController::class, 'transfer']);

    // Route::get('/transfer/{sender}/{recipient}', [TransactionController::class, 'showTransferForm'])->name('transfer.form');
    // Route::post('/transfer/{sender}/{recipient}', [TransactionController::class, 'transfer'])->name('transfer');
});
