<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_category_id',
        'brand_id',
        'unit_id',
        'sku',
        'name',
        'type',
        'keyword',
        'compatibility',
        'size',
        'unit',
        'description',
    ];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class, 'product_id');
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id');
    }

    public function discounts()
    {
        return $this->hasMany(ProductDiscount::class, 'product_id');
    }

}
