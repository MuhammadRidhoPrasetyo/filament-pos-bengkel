<?php

namespace App\Observers;

use App\Models\ProductMovement;
use App\Models\ProductStock;
use App\Models\StockAdjustmentItem;
use Illuminate\Support\Facades\DB;

class StockAdjustmentItemObserver
{
    public function created(StockAdjustmentItem $item): void
    {
        DB::transaction(function () use ($item) {
            $adjustment = $item->stockAdjustment()->lockForUpdate()->first();

            if (! $adjustment) {
                return;
            }

            $qty = (int) $item->quantity;

            if ($qty <= 0) {
                return;
            }

            $this->applyStockChange(
                $item->product_id,
                $adjustment->store_id,
                $item->adjustment_type,
                $qty,
            );

            ProductMovement::create([
                'product_id' => $item->product_id,
                'store_id' => $adjustment->store_id,
                'movement_type' => $this->movementType($item->adjustment_type),
                'quantity' => $qty,
                'movementable_type' => StockAdjustmentItem::class,
                'movementable_id' => $item->id,
                'occurred_at' => $adjustment->occurred_at ?? now(),
                'created_by' => $adjustment->posted_by,
                'note' => $item->note,
            ]);
        });
    }

    public function updating(StockAdjustmentItem $item): void
    {
        DB::transaction(function () use ($item) {
            $adjustment = $item->stockAdjustment()->lockForUpdate()->first();

            if (! $adjustment) {
                return;
            }

            $storeId = $adjustment->store_id;

            $oldProductId = (string) $item->getOriginal('product_id');
            $oldType = (string) $item->getOriginal('adjustment_type');
            $oldQty = (int) $item->getOriginal('quantity');

            $newProductId = (string) $item->product_id;
            $newType = (string) $item->adjustment_type;
            $newQty = max(0, (int) $item->quantity);

            $movement = ProductMovement::query()
                ->where('movementable_type', StockAdjustmentItem::class)
                ->where('movementable_id', $item->id)
                ->lockForUpdate()
                ->first();

            // Rollback efek lama.
            if ($oldQty > 0 && $oldProductId && $oldType) {
                $this->applyStockChange($oldProductId, $storeId, $this->inverseType($oldType), $oldQty);
            }

            // Apply efek baru.
            if ($newQty > 0 && $newProductId && $newType) {
                $this->applyStockChange($newProductId, $storeId, $newType, $newQty);
            }

            if ($newQty <= 0) {
                $movement?->delete();

                return;
            }

            $payload = [
                'product_id' => $newProductId,
                'store_id' => $storeId,
                'movement_type' => $this->movementType($newType),
                'quantity' => $newQty,
                'occurred_at' => $adjustment->occurred_at ?? now(),
                'note' => $item->note,
            ];

            if ($movement) {
                $movement->update($payload);

                return;
            }

            ProductMovement::create(array_merge($payload, [
                'movementable_type' => StockAdjustmentItem::class,
                'movementable_id' => $item->id,
                'created_by' => $adjustment->posted_by,
            ]));
        });
    }

    public function deleting(StockAdjustmentItem $item): void
    {
        DB::transaction(function () use ($item) {
            $adjustment = $item->stockAdjustment()->lockForUpdate()->first();

            if (! $adjustment) {
                return;
            }

            $oldQty = (int) ($item->getOriginal('quantity') ?? $item->quantity);
            $oldType = (string) ($item->getOriginal('adjustment_type') ?? $item->adjustment_type);
            $oldProductId = (string) ($item->getOriginal('product_id') ?? $item->product_id);

            if ($oldQty > 0 && $oldProductId && $oldType) {
                $this->applyStockChange($oldProductId, $adjustment->store_id, $this->inverseType($oldType), $oldQty);
            }

            ProductMovement::query()
                ->where('movementable_type', StockAdjustmentItem::class)
                ->where('movementable_id', $item->id)
                ->delete();
        });
    }

    /**
     * Increment/decrement stok berdasarkan tipe adjustment.
     */
    protected function applyStockChange(string $productId, string $storeId, string $type, int $qty): void
    {
        $stock = ProductStock::query()
            ->where('product_id', $productId)
            ->where('store_id', $storeId)
            ->lockForUpdate()
            ->firstOrCreate(
                ['product_id' => $productId, 'store_id' => $storeId],
                ['quantity' => 0],
            );

        if ($type === 'increase') {
            $stock->increment('quantity', $qty);

            return;
        }

        // decrease: guard agar tidak minus.
        $stock->decrement('quantity', min($stock->quantity, $qty));
    }

    protected function inverseType(string $type): string
    {
        return $type === 'increase' ? 'decrease' : 'increase';
    }

    protected function movementType(string $adjustmentType): string
    {
        return $adjustmentType === 'increase' ? 'in' : 'out';
    }
}
