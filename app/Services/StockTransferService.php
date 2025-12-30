<?php

namespace App\Services;

use App\Models\StockTransfer;
use App\Models\ProductStock;
use App\Models\ProductMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StockTransferService
{
    /**
     * Post a StockTransfer: move stock and create product movements.
     *
     * @param StockTransfer $transfer
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @throws \Exception
     */
    public function post(StockTransfer $transfer, $user = null): void
    {
        if ($transfer->status === 'posted') {
            return;
        }

        $userId = $user?->id ?? Auth::id();
        $occurredAt = $transfer->occurred_at ?? Carbon::now();

        DB::transaction(function () use ($transfer, $userId, $occurredAt) {
            $transfer->load('items');

            foreach ($transfer->items as $item) {
                // Lock from and to product_stock rows
                $fromStock = ProductStock::where('product_id', $item->product_id)
                    ->where('store_id', $transfer->from_store_id)
                    ->lockForUpdate()
                    ->first();

                if (! $fromStock || ($fromStock->quantity < $item->quantity)) {
                    throw new \Exception(sprintf('Stok tidak cukup untuk produk %s di bengkel %s', $item->product_id, $transfer->from_store_id));
                }

                $toStock = ProductStock::where('product_id', $item->product_id)
                    ->where('store_id', $transfer->to_store_id)
                    ->lockForUpdate()
                    ->first();

                if (! $toStock) {
                    $toStock = ProductStock::create([
                        'product_id' => $item->product_id,
                        'store_id' => $transfer->to_store_id,
                        'quantity' => 0,
                    ]);
                }

                // Decrement fromStock and increment toStock
                $fromStock->quantity = $fromStock->quantity - $item->quantity;
                $fromStock->save();

                $toStock->quantity = $toStock->quantity + $item->quantity;
                $toStock->save();

                // Create product movements: out (from) and in (to)
                ProductMovement::create([
                    'product_id' => $item->product_id,
                    'store_id' => $transfer->from_store_id,
                    'movement_type' => 'out',
                    'quantity' => $item->quantity,
                    'movementable_type' => get_class($item),
                    'movementable_id' => $item->getKey(),
                    'occurred_at' => $occurredAt,
                    'created_by' => $userId,
                    'note' => sprintf('Transfer to store %s (reference %s)', $transfer->to_store_id, $transfer->reference_number),
                ]);

                ProductMovement::create([
                    'product_id' => $item->product_id,
                    'store_id' => $transfer->to_store_id,
                    'movement_type' => 'in',
                    'quantity' => $item->quantity,
                    'movementable_type' => get_class($item),
                    'movementable_id' => $item->getKey(),
                    'occurred_at' => $occurredAt,
                    'created_by' => $userId,
                    'note' => sprintf('Transfer from store %s (reference %s)', $transfer->from_store_id, $transfer->reference_number),
                ]);
            }

            // Mark transfer posted
            $transfer->status = 'posted';
            $transfer->posted_by = $userId;
            $transfer->posted_at = Carbon::now();
            $transfer->save();
        });
    }

    /**
     * Cancel a posted StockTransfer: reverse stock and create reversal movements.
     * If transfer is not posted, just mark as cancelled.
     *
     * @param StockTransfer $transfer
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @throws \Exception
     */
    public function cancel(StockTransfer $transfer, $user = null): void
    {
        if ($transfer->status !== 'posted') {
            $transfer->status = 'cancelled';
            $transfer->save();
            return;
        }

        $userId = $user?->id ?? Auth::id();
        $occurredAt = Carbon::now();

        DB::transaction(function () use ($transfer, $userId, $occurredAt) {
            $transfer->load('items');

            foreach ($transfer->items as $item) {
                // Lock relevant product_stock rows
                $toStock = ProductStock::where('product_id', $item->product_id)
                    ->where('store_id', $transfer->to_store_id)
                    ->lockForUpdate()
                    ->first();

                if (! $toStock || ($toStock->quantity < $item->quantity)) {
                    throw new \Exception(sprintf('Tidak cukup stok di bengkel tujuan (%s) untuk membatalkan transfer produk %s', $transfer->to_store_id, $item->product_id));
                }

                $fromStock = ProductStock::where('product_id', $item->product_id)
                    ->where('store_id', $transfer->from_store_id)
                    ->lockForUpdate()
                    ->first();

                if (! $fromStock) {
                    $fromStock = ProductStock::create([
                        'product_id' => $item->product_id,
                        'store_id' => $transfer->from_store_id,
                        'quantity' => 0,
                    ]);
                }

                // Reverse: add back to fromStock, subtract from toStock
                $toStock->quantity = $toStock->quantity - $item->quantity;
                $toStock->save();

                $fromStock->quantity = $fromStock->quantity + $item->quantity;
                $fromStock->save();

                // Create reversal movements: in to fromStore, out from toStore
                ProductMovement::create([
                    'product_id' => $item->product_id,
                    'store_id' => $transfer->from_store_id,
                    'movement_type' => 'in',
                    'quantity' => $item->quantity,
                    'movementable_type' => get_class($item),
                    'movementable_id' => $item->getKey(),
                    'occurred_at' => $occurredAt,
                    'created_by' => $userId,
                    'note' => sprintf('Reversal (cancel) transfer from %s to %s (reference %s)', $transfer->from_store_id, $transfer->to_store_id, $transfer->reference_number),
                ]);

                ProductMovement::create([
                    'product_id' => $item->product_id,
                    'store_id' => $transfer->to_store_id,
                    'movement_type' => 'out',
                    'quantity' => $item->quantity,
                    'movementable_type' => get_class($item),
                    'movementable_id' => $item->getKey(),
                    'occurred_at' => $occurredAt,
                    'created_by' => $userId,
                    'note' => sprintf('Reversal (cancel) transfer from %s to %s (reference %s)', $transfer->from_store_id, $transfer->to_store_id, $transfer->reference_number),
                ]);
            }

            $transfer->status = 'cancelled';
            $transfer->save();
        });
    }
}
