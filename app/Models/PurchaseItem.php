<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

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

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
