<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PurchaseItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'price_type',
        'quantity_ordered',
        'unit_purchase_price',
        'item_discount_type',
        'item_discount_value'
    ];

    protected static function booted()
    {
        static::updating(function (PurchaseItem $item) {
            DB::transaction(function () use ($item) {
                $purchase    = $item->purchase()->lockForUpdate()->first();
                $storeId     = $purchase->store_id;

                $oldProductId = (string) $item->getOriginal('product_id');
                $oldQty       = (int) $item->getOriginal('quantity_ordered');
                $newProductId = (string) $item->product_id;
                $newQty       = max(0, (int) $item->quantity_ordered); // guard

                // Ambil / buat movement untuk item ini
                $movement = \App\Models\ProductMovement::query()
                    ->where('movementable_type', PurchaseItem::class)
                    ->where('movementable_id', $item->id)
                    ->where('product_id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (! $movement) {
                    $movement = \App\Models\ProductMovement::create([
                        'product_id'        => $newProductId,
                        'store_id'          => $storeId,
                        'movement_type'     => 'in',
                        'quantity'          => 0,
                        'movementable_type' => PurchaseItem::class,
                        'movementable_id'   => $item->id,
                        'occurred_at'       => $purchase->purchase_date ?? now(),
                        'created_by'        => $purchase->received_by ?? $purchase->created_by,
                        'note'              => 'Purchase #' . ($purchase->number ?? $purchase->id),
                    ]);
                }

                // CASE 1: product berubah
                if ($oldProductId !== $newProductId) {
                    // rollback stok product lama
                    \App\Models\ProductStock::query()
                        ->where('product_id', $oldProductId)
                        ->where('store_id',  $storeId)
                        ->lockForUpdate()
                        ->decrement('quantity', $oldQty);

                    // apply stok product baru
                    $newStock = \App\Models\ProductStock::query()
                        ->where('product_id', $newProductId)
                        ->where('store_id',  $storeId)
                        ->lockForUpdate()
                        ->firstOrCreate(
                            ['product_id' => $newProductId, 'store_id' => $storeId],
                            ['quantity' => 0]
                        );

                    $newStock->increment('quantity', $newQty);

                    // update movement: set qty = newQty (bukan + delta)
                    $movement->update([
                        'product_id'  => $newProductId,
                        'store_id'    => $storeId,
                        'quantity'    => $newQty, // <<— ini penting: set langsung ke nilai baru
                        'occurred_at' => $purchase->purchase_date ?? now(),
                        'note'        => 'Purchase updated #' . ($purchase->number ?? $purchase->id),
                    ]);

                    return;
                }

                // CASE 2: product sama → pakai delta untuk stok, tapi movement.quantity = newQty
                $delta = $newQty - $oldQty;

                if ($delta !== 0) {
                    \App\Models\ProductStock::query()
                        ->where('product_id', $newProductId)
                        ->where('store_id',  $storeId)
                        ->lockForUpdate()
                        ->increment('quantity', $delta);
                }

                if ($newQty === 0) {
                    // qty jadi nol → hapus movement & selesai
                    $movement->delete();
                } else {
                    // set langsung qty baru (bukan + delta)
                    $movement->update([
                        'quantity'    => $newQty, // <<— kunci perbaikan
                        'occurred_at' => $purchase->purchase_date ?? now(),
                        'note'        => 'Purchase updated #' . ($purchase->number ?? $purchase->id),
                    ]);
                }
            });
        });
    }

    public function movement()
    {
        return $this->morphOne(ProductMovement::class, 'movementable');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
