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

    public function __toString(): string
    {
        try {
            $product = $this->product;

            if ($product) {
                return (string) ($product->label ?? $product->name ?? $this->description ?? $product->getKey() ?? $this->getKey() ?? '');
            }

            return (string) ($this->description ?? $this->getKey() ?? '');
        } catch (\Throwable) {
            return (string) ($this->getKey() ?? '');
        }
    }

    public function unit()
    {
        return $this->belongsTo(ServiceOrderUnit::class, 'service_order_unit_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
