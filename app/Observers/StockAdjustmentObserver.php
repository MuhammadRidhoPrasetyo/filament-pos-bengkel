<?php

namespace App\Observers;

use App\Models\ProductStock;
use App\Models\ProductMovement;
use App\Models\StockAdjustment;
use App\Traits\HasDocumentNumber;
use Illuminate\Support\Facades\DB;

class StockAdjustmentObserver
{
    use HasDocumentNumber;

    public function creating(StockAdjustment $stockAdjustment)
    {
        $stockAdjustment->reference_number = $this->generateDocumentNumber('ADJ', storeId: $stockAdjustment->store_id);
    }

    public function updating(StockAdjustment $stockAdjustment)
    {
        DB::transaction(function () use ($stockAdjustment) {

            $oldStoreId = (string) $stockAdjustment->getOriginal('store_id');
            $newStoreId = (string) $stockAdjustment->store_id;

            $oldOccurredAt = $stockAdjustment->getOriginal('occurred_at');
            $newOccurredAt = $stockAdjustment->occurred_at;

            // Lock header & items
            $stockAdjustment->lockForUpdate();
            $items = $stockAdjustment->items()->lockForUpdate()->get();

            // 1) Jika store berpindah: rollback di store lama, apply di store baru
            if ($oldStoreId !== $newStoreId) {
                foreach ($items as $item) {
                    $qty  = (int) $item->quantity;
                    if ($qty <= 0) {
                        // kalau qty 0, lewati
                        continue;
                    }

                    // --- rollback stok di store lama
                    $oldStock = ProductStock::query()
                        ->where('product_id', $item->product_id)
                        ->where('store_id',  $oldStoreId)
                        ->lockForUpdate()
                        ->first();

                    if ($oldStock) {
                        if ($item->adjustment_type === 'increase') {
                            // dulunya nambah → sekarang kurangi
                            $oldStock->decrement('quantity', min($oldStock->quantity, $qty));
                        } else {
                            // dulunya ngurangin → sekarang tambahkan kembali
                            $oldStock->increment('quantity', $qty);
                        }
                    }

                    // --- apply stok di store baru
                    $newStock = ProductStock::query()
                        ->where('product_id', $item->product_id)
                        ->where('store_id',  $newStoreId)
                        ->lockForUpdate()
                        ->firstOrCreate(
                            ['product_id' => $item->product_id, 'store_id' => $newStoreId],
                            ['quantity' => 0]
                        );

                    if ($item->adjustment_type === 'increase') {
                        $newStock->increment('quantity', $qty);
                    } else {
                        $newStock->decrement('quantity', min($newStock->quantity, $qty));
                    }

                    // --- update movement store_id untuk item ini (kalau ada)
                    ProductMovement::query()
                        ->whereMorphedTo('movementable', $item)
                        ->update(['store_id' => $newStoreId]);
                }
            }

            // 2) Jika occurred_at berubah: update semua movement terkait
            if ($oldOccurredAt != $newOccurredAt) {
                foreach ($items as $item) {
                    ProductMovement::query()
                        ->whereMorphedTo('movementable', $item)
                        ->update(['occurred_at' => $newOccurredAt]);
                }
            }
        });
    }

    public function deleting(StockAdjustment $stockAdjustment)
    {
        DB::transaction(function () use ($stockAdjustment) {
            // kunci header (store_id dipakai)
            $stockAdjustment->lockForUpdate();

            // ambil semua item sbg model (jangan mass delete)
            $items = $stockAdjustment->items()->get();

            foreach ($items as $item) {
                // cari movement yang terkait item ini (aman untuk morph map)
                $movement = ProductMovement::query()
                    ->whereMorphedTo('movementable', $item)
                    ->lockForUpdate()
                    ->first();

                // qty yang harus di-rollback: pakai movement->quantity jika ada, fallback ke original item
                $qtyToRollback = (int) ($movement?->quantity ?? $item->getOriginal('quantity'));
                $productId     = (string) $item->getOriginal('product_id');
                $storeId       = (string) $stockAdjustment->store_id;

                // ambil stok agregat
                $stock = ProductStock::query()
                    ->where('product_id', $productId)
                    ->where('store_id',  $storeId)
                    ->lockForUpdate()
                    ->first();

                if ($stock && $qtyToRollback > 0) {
                    // jika adjustment aslinya 'increase', penghapusan harus MENGURANGI stok
                    // jika adjustment aslinya 'decrease', penghapusan harus MENAMBAH stok
                    if ($item->adjustment_type === 'in') {
                        // guard agar tidak minus
                        $dec = min($stock->quantity, $qtyToRollback);
                        if ($dec > 0) {
                            $stock->decrement('quantity', $dec);
                        }
                    } else { // 'decrease'
                        $stock->increment('quantity', $qtyToRollback);
                    }
                }

                // hapus movement jika ada
                if ($movement) {
                    $movement->delete();
                }

                // terakhir, hapus item via model (bukan mass delete)
                $item->delete();
            }
        });
    }
}
