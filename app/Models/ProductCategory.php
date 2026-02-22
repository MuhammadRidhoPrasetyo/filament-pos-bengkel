<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'pricing_mode',
        'item_type',
        'parent_id',
    ];

    public function isService(): bool
    {
        return $this->item_type === 'labor';
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    protected static function booted()
    {
        static::saving(function (ProductCategory $category) {
            // Prevent setting itself as parent
            if ($category->parent_id && $category->id && $category->parent_id == $category->id) {
                throw ValidationException::withMessages(['parent_id' => 'Kategori tidak boleh menjadi parent dari dirinya sendiri.']);
            }

            // Prevent cycles: traverse ancestors and ensure none equals current id
            if ($category->parent_id && $category->id) {
                $parent = $category->parent()->first();
                while ($parent) {
                    if ($parent->id == $category->id) {
                        throw ValidationException::withMessages(['parent_id' => 'Parent tidak valid (menciptakan siklus).']);
                    }
                    $parent = $parent->parent()->first();
                }
            }
        });
    }

    public function getChildrenNamesAttribute(): string
    {
        return $this->children->pluck('name')->implode(', ');
    }
}
