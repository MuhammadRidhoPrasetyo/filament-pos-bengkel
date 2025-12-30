<?php

namespace App\Models;

use App\Models\ProductPriceHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductStock extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_id',
        'store_id',
        'quantity',
        'minimum_stock',
        'product_price_id',
        'is_hidden'
    ];

    public function __toString(): string
    {
        try {
            $product = $this->product;
            $store = $this->store;

            $productPart = $product ? ($product->label ?? $product->name ?? $product->getKey()) : null;
            $storePart = $store ? ($store->name ?? $store->code ?? $store->getKey()) : null;

            return (string) ($productPart ?: $storePart ?: ($this->getKey() ?? ''));
        } catch (\Throwable) {
            return (string) ($this->getKey() ?? '');
        }
    }

    protected function casts(): array
    {
        return [
            'is_hidden' => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function productPrices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'product_id')
            ->where('store_id', $this->store_id);
    }

    public function productPrice()
    {
        return $this->belongsTo(ProductPrice::class, 'product_price_id', 'id')
            ->where('is_active', true);
    }

    public function discounts()
    {
        return $this->hasMany(ProductDiscount::class, 'product_id', 'product_id')
            ->where('store_id', $this->store_id);
    }

    public function productPriceHistories()
    {
        return $this->hasMany(ProductPriceHistory::class, 'product_id', 'product_id')
            ->where('store_id', $this->store_id);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class, 'product_id', 'product_id')
            ->whereRelation('purchase', 'store_id', $this->store_id);
    }

    public function stockAdjustmentItems()
    {
        return $this->hasMany(StockAdjustmentItem::class, 'product_id', 'product_id')
            ->whereRelation('stockAdjustment', 'store_id', $this->store_id);
    }
}
