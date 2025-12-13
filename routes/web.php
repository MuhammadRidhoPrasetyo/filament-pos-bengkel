<?php

use App\Livewire\CashierPage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cashier', CashierPage::class)->name('cashier');

use App\Models\Transaction;
use Illuminate\Http\Request;

Route::get('/transactions/{transaction}/receipt', function (Transaction $transaction, Request $request) {
    // allow unauthenticated users to view the receipt when requested from the cashier (Filament UI)
    return view('filament.receipts.transaction', compact('transaction'));
})->name('transactions.receipt');
