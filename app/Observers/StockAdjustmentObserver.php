<?php

namespace App\Observers;

use App\Models\ProductMovement;
use App\Models\StockAdjustment;
use App\Traits\HasDocumentNumber;
use Illuminate\Support\Facades\DB;

class StockAdjustmentObserver
{
    use HasDocumentNumber;

    public function creating(StockAdjustment $stockAdjustment): void
    {
        $stockAdjustment->reference_number = $this->generateDocumentNumber('ADJ', storeId: $stockAdjustment->store_id);
    }

    public function updating(StockAdjustment $stockAdjustment): void
    {
        DB::transaction(function () use ($stockAdjustment) {
            $oldOccurredAt = $stockAdjustment->getOriginal('occurred_at');
            $newOccurredAt = $stockAdjustment->occurred_at;

            if ($oldOccurredAt == $newOccurredAt) {
                return;
            }

            // store_id dikunci pada form edit, jadi tidak perlu handle perpindahan store.
            $stockAdjustment->lockForUpdate();
            $items = $stockAdjustment->items()->get();

            foreach ($items as $item) {
                ProductMovement::query()
                    ->whereMorphedTo('movementable', $item)
                    ->update(['occurred_at' => $newOccurredAt]);
            }
        });
    }

    public function deleting(StockAdjustment $stockAdjustment): void
    {
        DB::transaction(function () use ($stockAdjustment) {
            $stockAdjustment->lockForUpdate();

            // Hapus per item agar StockAdjustmentItemObserver::deleting handle rollback stok + movement.
            $stockAdjustment->items()->get()->each->delete();
        });
    }
}
