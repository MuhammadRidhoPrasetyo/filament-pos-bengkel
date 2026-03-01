<?php

namespace App\Observers;

use App\Models\CashFlow;
use App\Models\CashFlowCategory;
use App\Models\Transaction;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        if ($transaction->status !== 'completed') {
            return;
        }

        $category = CashFlowCategory::where('is_system', true)
            ->where('type', 'income')
            ->where('name', 'Penjualan')
            ->first();

        if (! $category) {
            return;
        }

        CashFlow::create([
            'store_id'       => $transaction->store_id,
            'user_id'        => $transaction->user_id,
            'category_id'    => $category->id,
            'type'           => 'income',
            'amount'         => $transaction->grand_total,
            'date'           => $transaction->transaction_date,
            'description'    => "Penjualan #{$transaction->number}",
            'reference_type' => Transaction::class,
            'reference_id'   => $transaction->id,
        ]);
    }
}
