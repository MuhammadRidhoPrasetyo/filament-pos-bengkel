<?php

use App\Livewire\CashierPage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cashier', CashierPage::class)->name('cashier');
