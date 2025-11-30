<?php

namespace App\Observers;

use App\Services\StockService;
use App\Models\TransactionItem;

class TransactionItemObserver
{
     public function __construct(
        protected StockService $stockService,
    ) {}

    public function created(TransactionItem $item): void
    {
        // Jika transaksi statusnya completed baru ngaruh ke stok
        if ($item->transaction?->status === 'completed') {
            $this->stockService->handleSaleCreated($item);
        }
    }

    public function updated(TransactionItem $item): void
    {
        if ($item->transaction?->status === 'completed') {
            $this->stockService->handleSaleUpdated($item);
        }
    }

    public function deleted(TransactionItem $item): void
    {
        if ($item->transaction?->status === 'completed') {
            $this->stockService->handleSaleDeleted($item);
        }
    }
}
