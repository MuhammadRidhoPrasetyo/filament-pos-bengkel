<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ServiceOrderItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'service_order_unit_id',
        'item_type',
        'product_id',
        'description',
        'quantity',
        'unit_price',
        'line_total',
    ];

    public function unit()
    {
        return $this->belongsTo(ServiceOrderUnit::class, 'service_order_unit_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
