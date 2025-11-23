<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Purchase extends Model
{
    use HasUuids;

    protected $fillable = [
        'store_id',
        'supplier_id',
        'created_by',
        'received_by',
        'number',
        'invoice_number',
        'purchase_date',
        'discount_type',
        'discount_value',
        'price',
        'notes'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->number = (string) 'BRG-' . now()->timestamp . now()->micro;
        });

        static::deleting(function (Purchase $purchase) {
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
        });
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
