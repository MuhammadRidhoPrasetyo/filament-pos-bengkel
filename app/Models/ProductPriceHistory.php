<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductPriceHistory extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_id',
        'store_id',
        'product_price_id',
        'date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productPrice()
    {
        return $this->belongsTo(ProductPrice::class, 'product_price_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

}
