<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_id',
        'store_id',
        'quantity',
        'product_price_id',
    ];

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
        return $this->belongsTo(ProductPrice::class, 'product_price_id', 'id');
    }

    public function discounts()
    {
        return $this->hasMany(ProductDiscount::class, 'product_id', 'product_id')
            ->where('store_id', $this->store_id);
    }
}
