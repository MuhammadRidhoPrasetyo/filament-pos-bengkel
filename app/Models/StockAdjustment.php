<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class StockAdjustment extends Model
{
    use HasUuids;

    protected $fillable = [
        'store_id',
        'posted_by',
        'reference_number',
        'occurred_at',
        'note',
    ];

    protected static function booted()
    {
        static::creating(function ($stockAdjustment) {
            // ambil tahun-bulan sekarang
            $prefix = 'ADJ-' . now()->format('Y-m');

            // cari nomor terakhir bulan ini
            $latestNumber = DB::table('stock_adjustments')
                ->where('reference_number', 'like', $prefix . '%')
                ->orderByDesc('reference_number')
                ->value('reference_number');

            if ($latestNumber) {
                // ambil 3 digit terakhir
                $lastSeq = (int) substr($latestNumber, -3);
                $newSeq = $lastSeq + 1;
            } else {
                $newSeq = 1;
            }

            // generate nomor baru
            $stockAdjustment->reference_number = $prefix . '-' . str_pad($newSeq, 3, '0', STR_PAD_LEFT);
        });

        static::deleting(function (StockAdjustment $adj) {
            DB::transaction(function () use ($adj) {
                // kunci header (store_id dipakai)
                $adj->lockForUpdate();

                // ambil semua item sbg model (jangan mass delete)
                $items = $adj->items()->get();

                foreach ($items as $item) {
                    // cari movement yang terkait item ini (aman untuk morph map)
                    $movement = ProductMovement::query()
                        ->whereMorphedTo('movementable', $item)
                        ->lockForUpdate()
                        ->first();

                    // qty yang harus di-rollback: pakai movement->quantity jika ada, fallback ke original item
                    $qtyToRollback = (int) ($movement?->quantity ?? $item->getOriginal('quantity'));
                    $productId     = (string) $item->getOriginal('product_id');
                    $storeId       = (string) $adj->store_id;

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
        });

        static::updating(function (StockAdjustment $adj) {
            DB::transaction(function () use ($adj) {

                $oldStoreId = (string) $adj->getOriginal('store_id');
                $newStoreId = (string) $adj->store_id;

                $oldOccurredAt = $adj->getOriginal('occurred_at');
                $newOccurredAt = $adj->occurred_at;

                // Lock header & items
                $adj->lockForUpdate();
                $items = $adj->items()->lockForUpdate()->get();

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
        });
    }

    public function items()
    {
        return $this->hasMany(StockAdjustmentItem::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
