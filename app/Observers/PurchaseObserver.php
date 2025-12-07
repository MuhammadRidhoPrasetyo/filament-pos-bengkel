<?php

namespace App\Observers;

use App\Models\Purchase;
use App\Models\ProductStock;
use App\Models\ProductMovement;
use App\Traits\HasDocumentNumber;
use Illuminate\Support\Facades\DB;

class PurchaseObserver
{

    use HasDocumentNumber;

    public function creating(Purchase $purchase)
    {
        $purchase->number = $this->generateDocumentNumber('PRC', storeId: $purchase->store_id);
    }

    public function deleting(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            // Kunci header (store_id bisa terpakai)
            $purchase->lockForUpdate();

            // Ambil semua item sebagai model (bukan mass delete)
            $items = $purchase->items()->get();

            foreach ($items as $item) {
                // 1) Temukan movement yang terkait baris ini (aman untuk morph map)
                $movement = ProductMovement::query()
                    ->whereMorphedTo('movementable', $item)
                    ->lockForUpdate()
                    ->first();

                // 2) Hitung qty rollback dari movement (kalau ada), fallback ke original qty
                $qtyToRollback = (int) ($movement?->quantity ?? $item->getOriginal('quantity_ordered'));

                // 3) Kurangi stok agregat di store header
                $stock = ProductStock::query()
                    ->where('product_id', $item->getOriginal('product_id'))
                    ->where('store_id',  $purchase->store_id)
                    ->lockForUpdate()
                    ->first();

                if ($stock && $qtyToRollback > 0) {
                    // guard agar tidak minus
                    $stock->decrement('quantity', min($stock->quantity, $qtyToRollback));
                }

                // 4) Hapus movement kalau ada
                if ($movement) {
                    $movement->delete();
                }

                // 5) Terakhir: hapus item (via model, supaya rapi & trigger lain tetap jalan)
                $item->delete(); // <- penting: bukan $purchase->items()->delete();
            }
        });
    }
}
