<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductLabel extends Model
{
    use HasUuids;

    protected $table = 'product_labels';

    protected $guarded = ['id'];

    protected $fillable = [
        'product_id',
        'product_category_id',
        'brand_id',

        'label_sku',
        'label_category',
        'label_brand',
        'label_type',
        'label_unit',
        'label_size',
        'label_keyword',
        'label_compatibility',
        'label_description',
        'separator',
    ];

    protected $casts = [
        'label_sku' => 'boolean',
        'label_category' => 'boolean',
        'label_brand' => 'boolean',
        'label_type' => 'boolean',
        'label_unit' => 'boolean',
        'label_size' => 'boolean',
        'label_keyword' => 'boolean',
        'label_compatibility' => 'boolean',
        'label_description' => 'boolean',
        'display_name' => 'string',
    ];

    protected $appends = [
        'display_name',
    ];

    public function __toString(): string
    {
        try {
            return $this->displayNameFormat();
        } catch (\Throwable) {
            return (string) ($this->getKey() ?? '');
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->displayNameFormat();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /**
     * Build a display name by reading product values and label flags.
     * If a flag (label_*) on this row is not null, it overrides global/default flags.
     * You may pass $overrides to adjust behavior programmatically.
     *
     * @param array $overrides
     * @return string
     */
    public function displayNameFormat(array $overrides = []): string
    {
        // row-level separator takes precedence
        $separator = $this->separator ?? config('products.separator', ' | ');

        // global defaults
        $defaults = array_merge(['brand' => true, 'sku' => false, 'type' => false, 'category' => false, 'unit' => false, 'size' => false, 'keyword' => false, 'compatibility' => false, 'description' => false], $overrides);

        // override with stored label_* flags when they are not null
        $flagMap = [
            'brand' => 'label_brand',
            'sku' => 'label_sku',
            'type' => 'label_type',
            'category' => 'label_category',
            'unit' => 'label_unit',
            'size' => 'label_size',
            'keyword' => 'label_keyword',
            'compatibility' => 'label_compatibility',
            'description' => 'label_description',
        ];

        foreach ($flagMap as $key => $col) {
            if (array_key_exists($col, $this->attributes) && $this->{$col} !== null) {
                $defaults[$key] = (bool) $this->{$col};
            }
        }

        $parts = [];

        // prefer linked product values when available
        $product = $this->product;

        if ($defaults['brand']) {
            $brandName = $product?->brand?->name ?? null;
            if ($brandName) {
                $parts[] = $brandName;
            }
        }

        // name: product name
        $name = $product?->name ?? null;
        if ($name) {
            $parts[] = $name;
        }

        if ($defaults['sku']) {
            $sku = $product?->sku ?? null;
            if ($sku) {
                $parts[] = $sku;
            }
        }

        if ($defaults['category']) {
            $cat = $product?->productCategory?->name ?? null;
            if ($cat) {
                $parts[] = $cat;
            }
        }

        if ($defaults['type']) {
            $type = $product?->type ?? null;
            if ($type) {
                $parts[] = $type;
            }
        }

        if ($defaults['size']) {
            $size = $product?->size ?? null;
            if ($size) {
                $parts[] = $size;
            }
        }

        if ($defaults['unit']) {
            $unit = $product?->unit?->name ?? $product?->unit ?? null;
            if ($unit) {
                $parts[] = $unit;
            }
        }

        if ($defaults['keyword']) {
            $kw = $product?->keyword ?? null;
            if ($kw) {
                $parts[] = $kw;
            }
        }

        if ($defaults['compatibility']) {
            $comp = $product?->compatibility ?? null;
            if ($comp) {
                $parts[] = $comp;
            }
        }

        if ($defaults['description']) {
            $desc = $product?->description ?? null;
            if ($desc) {
                $parts[] = Str::limit($desc, 40);
            }
        }

        // If productCategory exists and indicates a "part", return the assembled parts.
        if ($this->productCategory?->item_type === 'part') {
            return implode($separator, $parts);
        }

        // Prefer the linked product name when available; otherwise fall back to assembled parts.
        return $product?->name ?? implode($separator, $parts);
    }

    // Attribute accessor for appended property
    public function displayName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->displayNameFormat(),
        )->shouldCache();
    }
}
