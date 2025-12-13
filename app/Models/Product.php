<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasUuids, InteractsWithMedia;

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

    protected $appends = [
        'full_name'
    ];

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->productCategory->item_type === 'part') {
                    return $this->brand->name . ' | ' . $this->name;
                } else {
                    return $this->name;
                }
            }
        );
    }

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

    public function stock()
    {
        return $this->hasOne(ProductStock::class, 'product_id')
            ->where('store_id', auth()->user()->store_id)
        ;
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
    }

    public function discounts()
    {
        return $this->hasMany(ProductDiscount::class, 'product_id');
    }

    public function isService(): bool
    {
        return $this->productCategory?->item_type === 'labor';
        // atau: return $this->category?->trackStock() === false;
    }
}
