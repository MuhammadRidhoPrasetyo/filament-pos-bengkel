<?php

namespace App\Observers;

use App\Models\CashFlow;
use App\Models\CashFlowCategory;
use App\Models\Purchase;
use App\Traits\HasDocumentNumber;
use Illuminate\Support\Facades\DB;

class PurchaseObserver
{
    use HasDocumentNumber;

    public function creating(Purchase $purchase)
    {
        $purchase->number = $this->generateDocumentNumber('PRC', storeId: $purchase->store_id);
    }

    public function created(Purchase $purchase): void
    {
        $category = CashFlowCategory::where('is_system', true)
            ->where('type', 'expense')
            ->where('name', 'Pembelian Stok')
            ->first();

        if (! $category) {
            return;
        }

        CashFlow::create([
            'store_id' => $purchase->store_id,
            'user_id' => $purchase->created_by,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => $purchase->price ?? 0,
            'date' => $purchase->purchase_date ?? now()->toDateString(),
            'description' => "Pembelian #{$purchase->number}",
            'reference_type' => Purchase::class,
            'reference_id' => $purchase->id,
        ]);
    }

    public function updated(Purchase $purchase): void
    {
        if (! $purchase->wasChanged(['price', 'purchase_date'])) {
            return;
        }

        $purchase->cashFlows()->update([
            'amount' => $purchase->price ?? 0,
            'date' => $purchase->purchase_date ?? now()->toDateString(),
        ]);
    }

    public function deleting(Purchase $purchase): void
    {
        DB::transaction(function () use ($purchase) {
            $purchase->lockForUpdate();

            // Hapus per item agar PurchaseItemObserver::deleting handle rollback stok + movement.
            $purchase->items()->get()->each->delete();

            $purchase->cashFlows()->delete();
        });
    }
}
