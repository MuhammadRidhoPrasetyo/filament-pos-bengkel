<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_id',
        'store_id',
        'price_type',
        'purchase_price',
        'markup',
        'selling_price',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function productStocks()
    {
        return $this->hasMany(ProductStock::class, 'product_price_id');
    }
}
