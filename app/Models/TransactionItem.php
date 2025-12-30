<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\TransactionItemObserver;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(TransactionItemObserver::class)]
class TransactionItem extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

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

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productStock()
    {
        return $this->belongsTo(ProductStock::class, 'product_stock_id');
    }

    public function discountType()
    {
        return $this->belongsTo(DiscountType::class, 'discount_type_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

}
