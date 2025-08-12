<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'pricing_mode'
    ];

    public function pricingMode(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value == 'fixed' ? 'Harga Tetap' : 'Harga Bisa Diubah',
        );
    }
}
