<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCategory;

class SkuGeneratorService
{
    public static function generate(ProductCategory $category): string
    {
        $prefix = self::getPrefixByItemType($category->item_type);
        $nextNumber = self::getNextSequenceNumber($prefix);

        return sprintf('%s-%03d', $prefix, $nextNumber);
    }

    private static function getPrefixByItemType(string $itemType): string
    {
        return match ($itemType) {
            'part' => 'PART',
            'labor' => 'SRV',
            default => 'PROD',
        };
    }

    private static function getNextSequenceNumber(string $prefix): int
    {
        $lastProduct = Product::query()
            ->where('sku', 'like', $prefix . '-%')
            ->orderByRaw("CAST(SUBSTRING(sku, LENGTH(?) + 2) AS UNSIGNED) DESC", [$prefix])
            ->first();

        if (!$lastProduct) {
            return 1;
        }

        $lastNumber = (int) substr($lastProduct->sku, strlen($prefix) + 1);

        return $lastNumber + 1;
    }
}
