<?php

namespace App\Services;

use App\Models\ProductMovement;
use App\Models\ProductStock;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Pencatatan movement saat item transaksi baru dibuat.
     */
    public function handleSaleCreated(TransactionItem $item): void
    {
        DB::transaction(function () use ($item) {
            $transaction = $item->transaction;

            if ($this->isNonStockItem($item)) {
                return;
            }

            if (! $item->product_stock_id) {
                return;
            }

            $stock = ProductStock::query()
                ->whereKey($item->product_stock_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($item->quantity > $stock->quantity) {
                throw new \RuntimeException('Stok tidak mencukupi untuk transaksi ini.');
            }

            $stock->decrement('quantity', $item->quantity);

            ProductMovement::create([
                'product_id' => $item->product_id,
                'store_id' => $item->store_id,
                'movement_type' => 'out',
                'quantity' => $item->quantity,
                'movementable_type' => TransactionItem::class,
                'movementable_id' => $item->id,
                'occurred_at' => $transaction->transaction_date ?? now(),
                'created_by' => $transaction->user_id,
                'note' => 'POS #'.$transaction->number,
            ]);
        });
    }

    /**
     * Saat item transaksi di-edit (qty, product, atau store berubah).
     */
    public function handleSaleUpdated(TransactionItem $item): void
    {
        DB::transaction(function () use ($item) {
            $transaction = $item->transaction;

            if ($this->isNonStockItem($item)) {
                return;
            }

            $oldStockId = $item->getOriginal('product_stock_id');
            $oldProductId = $item->getOriginal('product_id');
            $oldStoreId = $item->getOriginal('store_id');
            $oldQty = (int) $item->getOriginal('quantity');

            $newStockId = $item->product_stock_id;
            $newProductId = $item->product_id;
            $newStoreId = $item->store_id;
            $newQty = (int) $item->quantity;

            $movement = ProductMovement::query()
                ->where('movementable_type', TransactionItem::class)
                ->where('movementable_id', $item->getKey())
                ->lockForUpdate()
                ->first();

            // CASE 1: product_stock_id berubah → rollback penuh stok lama, apply penuh stok baru.
            if ((string) $oldStockId !== (string) $newStockId) {
                if ($oldStockId && $oldQty > 0) {
                    $oldStock = ProductStock::query()
                        ->whereKey($oldStockId)
                        ->lockForUpdate()
                        ->first();

                    if ($oldStock) {
                        $oldStock->increment('quantity', $oldQty);
                    }
                }

                if ($newStockId && $newQty > 0) {
                    $newStock = ProductStock::query()
                        ->whereKey($newStockId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($newQty > $newStock->quantity) {
                        throw new \RuntimeException('Stok tidak cukup untuk mengubah produk pada transaksi.');
                    }

                    $newStock->decrement('quantity', $newQty);
                }

                $this->upsertSaleMovement($movement, $item, $newQty, $transaction, 'updated (product changed)');

                return;
            }

            // CASE 2: product_stock_id sama → delta-based.
            $diff = $oldQty - $newQty;

            if ($diff !== 0 && $newStockId) {
                $stock = ProductStock::query()
                    ->whereKey($newStockId)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($diff > 0) {
                    $stock->increment('quantity', $diff);
                } else {
                    $need = abs($diff);
                    if ($stock->quantity < $need) {
                        throw new \RuntimeException('Stok tidak cukup untuk mengubah qty transaksi.');
                    }
                    $stock->decrement('quantity', $need);
                }
            }

            $this->upsertSaleMovement($movement, $item, $newQty, $transaction, 'updated');
        });
    }

    /**
     * Saat item transaksi dihapus, atau saat status transaksi berubah dari
     * completed → void/draft (stok dikembalikan, movement dihapus, item tetap).
     */
    public function handleSaleDeleted(TransactionItem $item): void
    {
        DB::transaction(function () use ($item) {
            if ($this->isNonStockItem($item)) {
                ProductMovement::query()
                    ->where('movementable_type', TransactionItem::class)
                    ->where('movementable_id', $item->id)
                    ->delete();

                return;
            }

            $movement = ProductMovement::query()
                ->where('movementable_type', TransactionItem::class)
                ->where('movementable_id', $item->id)
                ->lockForUpdate()
                ->first();

            $qtyToRollback = (int) ($movement?->quantity ?? $item->getOriginal('quantity') ?? $item->quantity);
            $resolvedStockId = $item->getOriginal('product_stock_id') ?? $item->product_stock_id;

            if ($resolvedStockId && $qtyToRollback > 0) {
                $stock = ProductStock::query()
                    ->whereKey($resolvedStockId)
                    ->lockForUpdate()
                    ->first();

                if ($stock) {
                    $stock->increment('quantity', $qtyToRollback);
                }
            }

            $movement?->delete();
        });
    }

    /**
     * Buat atau update ProductMovement untuk TransactionItem.
     */
    protected function upsertSaleMovement(?ProductMovement $movement, TransactionItem $item, int $newQty, $transaction, string $reason): void
    {
        if ($newQty <= 0) {
            $movement?->delete();

            return;
        }

        $payload = [
            'product_id' => $item->product_id,
            'store_id' => $item->store_id,
            'quantity' => $newQty,
            'occurred_at' => $transaction->transaction_date ?? now(),
            'note' => 'POS ('.$reason.') #'.$transaction->number,
        ];

        if ($movement) {
            $movement->update($payload);

            return;
        }

        ProductMovement::create(array_merge($payload, [
            'movement_type' => 'out',
            'movementable_type' => TransactionItem::class,
            'movementable_id' => $item->id,
            'created_by' => $transaction->user_id,
        ]));
    }

    private function isNonStockItem(TransactionItem $item): bool
    {
        $product = $item->product()
            ->with('productCategory')
            ->first();

        if (! $product) {
            return false;
        }

        return $product->productCategory?->item_type === 'labor';
    }
}
