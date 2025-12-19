<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Validation\ValidationException;

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
        'label'
    ];

    public function label(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->productLabel?->display_name ?? $this->name;
            }
        );
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function productLabel()
    {
        return $this->hasOne(ProductLabel::class, 'product_id', 'id');
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

    protected static function booted()
    {
        // Prevent saving a product when another product already exists
        // with the exact same values for the selected identifying columns.
        // This is an application-level uniqueness check (no DB unique index required).
        static::saving(function (Product $product) {
            // Columns to compare for full-duplicate detection
            $cols = [
                'product_category_id',
                'brand_id',
                'unit_id',
                'name',
                'type',
                'keyword',
                'size',
                'compatibility',
                'unit',
            ];

            $query = self::query();
            // exclude self when updating
            if ($product->getKey()) {
                $query->where('id', '!=', $product->getKey());
            }

            foreach ($cols as $col) {
                $val = $product->{$col} ?? null;
                if ($val === null) {
                    $query->whereNull($col);
                } else {
                    $query->where($col, $val);
                }
            }

            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'name' => 'Produk dengan kombinasi field yang sama sudah ada. Pastikan setidaknya satu field berbeda.',
                ]);
            }
        });

        static::deleting(function ($product) {
            // Hapus semua diskon terkait sebelum produk dihapus
            $product->discounts()->delete();

            $product->prices->each(function ($price) {
                // Hapus stok milik harga tersebut
                $price->productStocks()->delete();

                // Baru hapus harganya
                $price->delete();
            });

            // Tambahkan relasi lain jika ada (misal: stok, varian, dll)
            // $product->variants()->delete();
        });
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
