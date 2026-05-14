<?php

namespace App\Observers;

use App\Models\ProductMovement;
use App\Models\ProductStock;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;

class PurchaseItemObserver
{
    public function created(PurchaseItem $item): void
    {
        DB::transaction(function () use ($item) {
            $purchase = $item->purchase()->lockForUpdate()->first();

            if (! $purchase) {
                return;
            }

            $qty = (int) $item->quantity_ordered;

            if ($qty <= 0) {
                return;
            }

            $stock = ProductStock::query()
                ->where('product_id', $item->product_id)
                ->where('store_id', $purchase->store_id)
                ->lockForUpdate()
                ->firstOrCreate(
                    ['product_id' => $item->product_id, 'store_id' => $purchase->store_id],
                    ['quantity' => 0],
                );

            $stock->increment('quantity', $qty);

            ProductMovement::create([
                'product_id' => $item->product_id,
                'store_id' => $purchase->store_id,
                'movement_type' => 'in',
                'quantity' => $qty,
                'movementable_type' => PurchaseItem::class,
                'movementable_id' => $item->id,
                'occurred_at' => $purchase->purchase_date ?? now(),
                'created_by' => $purchase->received_by ?? $purchase->created_by,
                'note' => 'Purchase #'.($purchase->number ?? $purchase->id),
            ]);
        });
    }

    public function updating(PurchaseItem $item): void
    {
        DB::transaction(function () use ($item) {
            $purchase = $item->purchase()->lockForUpdate()->first();
            $storeId = $purchase->store_id;

            $oldProductId = (string) $item->getOriginal('product_id');
            $oldQty = (int) $item->getOriginal('quantity_ordered');
            $newProductId = (string) $item->product_id;
            $newQty = max(0, (int) $item->quantity_ordered);

            $movement = ProductMovement::query()
                ->where('movementable_type', PurchaseItem::class)
                ->where('movementable_id', $item->id)
                ->lockForUpdate()
                ->first();

            if (! $movement) {
                $movement = ProductMovement::create([
                    'product_id' => $newProductId,
                    'store_id' => $storeId,
                    'movement_type' => 'in',
                    'quantity' => 0,
                    'movementable_type' => PurchaseItem::class,
                    'movementable_id' => $item->id,
                    'occurred_at' => $purchase->purchase_date ?? now(),
                    'created_by' => $purchase->received_by ?? $purchase->created_by,
                    'note' => 'Purchase #'.($purchase->number ?? $purchase->id),
                ]);
            }

            // CASE 1: product berubah.
            if ($oldProductId !== $newProductId) {
                if ($oldQty > 0) {
                    $oldStock = ProductStock::query()
                        ->where('product_id', $oldProductId)
                        ->where('store_id', $storeId)
                        ->lockForUpdate()
                        ->first();

                    if ($oldStock) {
                        $oldStock->decrement('quantity', min($oldStock->quantity, $oldQty));
                    }
                }

                if ($newQty > 0) {
                    $newStock = ProductStock::query()
                        ->where('product_id', $newProductId)
                        ->where('store_id', $storeId)
                        ->lockForUpdate()
                        ->firstOrCreate(
                            ['product_id' => $newProductId, 'store_id' => $storeId],
                            ['quantity' => 0],
                        );

                    $newStock->increment('quantity', $newQty);
                }

                if ($newQty <= 0) {
                    $movement->delete();

                    return;
                }

                $movement->update([
                    'product_id' => $newProductId,
                    'store_id' => $storeId,
                    'quantity' => $newQty,
                    'occurred_at' => $purchase->purchase_date ?? now(),
                    'note' => 'Purchase updated #'.($purchase->number ?? $purchase->id),
                ]);

                return;
            }

            // CASE 2: product sama → delta untuk stok.
            $delta = $newQty - $oldQty;

            if ($delta !== 0) {
                $stock = ProductStock::query()
                    ->where('product_id', $newProductId)
                    ->where('store_id', $storeId)
                    ->lockForUpdate()
                    ->firstOrCreate(
                        ['product_id' => $newProductId, 'store_id' => $storeId],
                        ['quantity' => 0],
                    );

                if ($delta > 0) {
                    $stock->increment('quantity', $delta);
                } else {
                    $stock->decrement('quantity', min($stock->quantity, abs($delta)));
                }
            }

            if ($newQty <= 0) {
                $movement->delete();

                return;
            }

            $movement->update([
                'quantity' => $newQty,
                'occurred_at' => $purchase->purchase_date ?? now(),
                'note' => 'Purchase updated #'.($purchase->number ?? $purchase->id),
            ]);
        });
    }

    public function deleting(PurchaseItem $item): void
    {
        DB::transaction(function () use ($item) {
            $purchase = $item->purchase()->lockForUpdate()->first();

            if (! $purchase) {
                return;
            }

            $movement = ProductMovement::query()
                ->where('movementable_type', PurchaseItem::class)
                ->where('movementable_id', $item->id)
                ->lockForUpdate()
                ->first();

            $qtyToRollback = (int) ($movement?->quantity ?? $item->getOriginal('quantity_ordered') ?? $item->quantity_ordered);
            $productId = (string) ($item->getOriginal('product_id') ?? $item->product_id);

            if ($qtyToRollback > 0 && $productId) {
                $stock = ProductStock::query()
                    ->where('product_id', $productId)
                    ->where('store_id', $purchase->store_id)
                    ->lockForUpdate()
                    ->first();

                if ($stock) {
                    $stock->decrement('quantity', min($stock->quantity, $qtyToRollback));
                }
            }

            $movement?->delete();
        });
    }
}
