<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_id',
        'store_id',
        'discount_type_id',
        'type',
        'value',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function discountType()
    {
        return $this->belongsTo(DiscountType::class);
    }
}
