<?php

namespace App\Observers;

use App\Models\TransactionItem;
use App\Services\StockService;

class TransactionItemObserver
{
    public function __construct(
        protected StockService $stockService,
    ) {}

    public function created(TransactionItem $item): void
    {
        $transaction = $item->transaction;

        if ($transaction?->status !== 'completed') {
            return;
        }

        // Saat status baru saja berubah ke completed, TransactionObserver::updated yang handle full snapshot.
        if ($transaction->wasChanged('status')) {
            return;
        }

        $this->stockService->handleSaleCreated($item);
    }

    public function updated(TransactionItem $item): void
    {
        $transaction = $item->transaction;

        if ($transaction?->status !== 'completed') {
            return;
        }

        if ($transaction->wasChanged('status')) {
            return;
        }

        $this->stockService->handleSaleUpdated($item);
    }

    public function deleted(TransactionItem $item): void
    {
        $transaction = $item->transaction;

        if ($transaction?->status !== 'completed') {
            return;
        }

        if ($transaction->wasChanged('status')) {
            return;
        }

        $this->stockService->handleSaleDeleted($item);
    }
}
