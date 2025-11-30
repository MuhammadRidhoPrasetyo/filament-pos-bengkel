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

            $stock = ProductStock::query()
                ->whereKey($item->product_stock_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($item->quantity > $stock->quantity) {
                throw new \RuntimeException('Stok tidak mencukupi untuk transaksi ini.');
            }

            $stock->decrement('quantity', $item->quantity);

            ProductMovement::create([
                'product_id'        => $item->product_id,
                'store_id'          => $item->store_id,
                'movement_type'     => 'out',
                'quantity'          => $item->quantity,
                'movementable_type' => TransactionItem::class,
                'movementable_id'   => $item->id,
                'occurred_at'       => $transaction->transaction_date,
                'created_by'        => $transaction->user_id,
                'note'              => 'POS #' . $transaction->number,
            ]);
        });
    }

    /**
     * Saat item transaksi di-edit (misal qty salah, diperbaiki).
     */
    public function handleSaleUpdated(TransactionItem $item): void
    {
        DB::transaction(function () use ($item) {
            $transaction = $item->transaction;

            // === NON-STOK (LABOR) → TIDAK ADA PERUBAHAN STOK ===
            if ($this->isNonStockItem($item)) {
                // Kalau kamu sebelumnya tidak pernah buat movement untuk jasa,
                // maka tidak perlu melakukan apa-apa di sini.
                return;
            }

            // === PRODUK PART / TRACK_STOCK ===
            $originalQty = $item->getOriginal('quantity');
            $newQty      = $item->quantity;

            $stock = ProductStock::query()
                ->whereKey($item->product_stock_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Hitung selisih
            $diff = $originalQty - $newQty;

            if ($diff > 0) {
                // Qty turun → balikin stok sebanyak selisih
                $stock->increment('quantity', $diff);
            } elseif ($diff < 0) {
                // Qty naik → kurangi stok lagi
                $need = abs($diff);

                if ($stock->quantity < $need) {
                    throw new \RuntimeException('Stok tidak cukup untuk mengubah qty transaksi.');
                }

                $stock->decrement('quantity', $need);
            }

            // Update movement
            $movement = ProductMovement::where('movementable_type', TransactionItem::class)
                ->where('movementable_id', $item->getKey())
                ->lockForUpdate()
                ->first();

            if ($movement) {
                $movement->update([
                    'quantity'    => $newQty,
                    'occurred_at' => $transaction->transaction_date,
                    'note'        => 'POS (edited) #' . $transaction->number,
                ]);
            } else {
                ProductMovement::create([
                    'product_id'        => $item->product_id,
                    'store_id'          => $item->store_id,
                    'movement_type'     => 'out',
                    'quantity'          => $newQty,
                    'movementable_type' => TransactionItem::class,
                    'movementable_id'   => $item->id,
                    'occurred_at'       => $transaction->transaction_date,
                    'created_by'        => $transaction->user_id,
                    'note'              => 'POS (recreated) #' . $transaction->number,
                ]);
            }
        });
    }

    /**
     * Saat item transaksi dihapus.
     */
    public function handleSaleDeleted(TransactionItem $item): void
    {
        DB::transaction(function () use ($item) {
            // === PRODUK NON-STOK (LABOR) ===
            if ($this->isNonStockItem($item)) {
                // Untuk jasa, stok tidak pernah bergerak, jadi:
                // cukup pastikan movement (kalau ada) ikut dibersihkan.
                ProductMovement::query()
                    ->where('movementable_type', TransactionItem::class)
                    ->where('movementable_id', $item->id)
                    ->delete();

                return;
            }

            // === PRODUK PART / TRACK_STOCK ===
            $movement = ProductMovement::query()
                ->where('movementable_type', TransactionItem::class)
                ->where('movementable_id', $item->id)
                ->lockForUpdate()
                ->first();

            if ($movement && $item->product_stock_id) {
                $stock = ProductStock::query()
                    ->whereKey($item->product_stock_id)
                    ->lockForUpdate()
                    ->first();

                if ($stock) {
                    $stock->increment('quantity', $movement->quantity);
                }

                $movement->delete();
            } else {
                // Fallback kalau movement tidak ada tapi item masih punya qty
                if ($item->product_stock_id && $item->quantity > 0) {
                    $stock = ProductStock::query()
                        ->whereKey($item->product_stock_id)
                        ->lockForUpdate()
                        ->first();

                    if ($stock) {
                        $stock->increment('quantity', $item->quantity);
                    }
                }

                ProductMovement::query()
                    ->where('movementable_type', TransactionItem::class)
                    ->where('movementable_id', $item->id)
                    ->delete();
            }
        });
    }

    private function isNonStockItem(TransactionItem $item): bool
    {
        $product = $item->product()
            ->with('productCategory') // pastikan relasi category ada di Product
            ->first();

        if (! $product) {
            // kalau produk tidak ditemukan, anggap saja item stok biasa
            // supaya ketahuan error-nya (bukan diam-diam di-skip)
            return false;
        }

        // asumsi: product_categories punya field item_type: 'part' | 'labor'
        return $product->productCategory?->item_type === 'labor';
    }
}
