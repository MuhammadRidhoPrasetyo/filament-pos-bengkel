<?php

namespace App\Observers;

use App\Models\CashFlow;
use App\Models\CashFlowCategory;
use App\Models\Transaction;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class TransactionObserver
{
    public function __construct(
        protected StockService $stockService,
    ) {}

    public function created(Transaction $transaction): void
    {
        if ($transaction->status !== 'completed') {
            return;
        }

        $this->createSalesCashFlow($transaction);
    }

    public function updated(Transaction $transaction): void
    {
        if (! $transaction->wasChanged('status')) {
            $this->syncCashFlowIfNeeded($transaction);

            return;
        }

        $oldStatus = $transaction->getOriginal('status');
        $newStatus = $transaction->status;

        DB::transaction(function () use ($transaction, $oldStatus, $newStatus) {
            if ($oldStatus === 'completed' && in_array($newStatus, ['void', 'draft'], true)) {
                $this->rollbackStock($transaction);
                $transaction->cashFlows()->delete();

                return;
            }

            if (in_array($oldStatus, ['draft', 'void'], true) && $newStatus === 'completed') {
                $this->applyStock($transaction);
                $this->createSalesCashFlow($transaction);

                return;
            }
        });
    }

    protected function applyStock(Transaction $transaction): void
    {
        $transaction->load('items');

        foreach ($transaction->items as $item) {
            $this->stockService->handleSaleCreated($item);
        }
    }

    protected function rollbackStock(Transaction $transaction): void
    {
        $transaction->load('items');

        foreach ($transaction->items as $item) {
            $this->stockService->handleSaleDeleted($item);
        }
    }

    protected function createSalesCashFlow(Transaction $transaction): void
    {
        $category = CashFlowCategory::query()
            ->where('is_system', true)
            ->where('type', 'income')
            ->where('name', 'Penjualan')
            ->first();

        if (! $category) {
            return;
        }

        CashFlow::create([
            'store_id' => $transaction->store_id,
            'user_id' => $transaction->user_id,
            'category_id' => $category->id,
            'type' => 'income',
            'amount' => $transaction->grand_total,
            'date' => $transaction->transaction_date,
            'description' => "Penjualan #{$transaction->number}",
            'reference_type' => Transaction::class,
            'reference_id' => $transaction->id,
        ]);
    }

    /**
     * Sinkronkan CashFlow amount/date jika grand_total atau transaction_date berubah pada transaksi completed.
     */
    protected function syncCashFlowIfNeeded(Transaction $transaction): void
    {
        if ($transaction->status !== 'completed') {
            return;
        }

        if (! $transaction->wasChanged(['grand_total', 'transaction_date'])) {
            return;
        }

        $transaction->cashFlows()->update([
            'amount' => $transaction->grand_total,
            'date' => $transaction->transaction_date,
        ]);
    }
}
