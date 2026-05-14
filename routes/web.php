<?php

use App\Livewire\CashierPage;
use Illuminate\Support\Facades\Route;
use App\Models\Transaction;
use Illuminate\Http\Request;

Route::redirect('/', '/admin');

Route::get('/cashier', CashierPage::class)->name('cashier');
Route::get('/cashier-page', CashierPage::class)->name('cashier-page');



Route::get('/transactions/{transaction}/receipt', function (Transaction $transaction, Request $request) {
    // allow unauthenticated users to view the receipt when requested from the cashier (Filament UI)
    return view('filament.receipts.transaction', compact('transaction'));
})->name('transactions.receipt');
