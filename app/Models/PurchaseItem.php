<?php

namespace App\Models;

use App\Observers\PurchaseItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

#[ObservedBy(PurchaseItemObserver::class)]
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

    public function __toString(): string
    {
        try {
            $product = $this->product;

            if ($product) {
                return (string) ($product->label ?? $product->name ?? $product->getKey() ?? $this->getKey() ?? '');
            }

            return (string) ($this->getKey() ?? '');
        } catch (\Throwable) {
            return (string) ($this->getKey() ?? '');
        }
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
