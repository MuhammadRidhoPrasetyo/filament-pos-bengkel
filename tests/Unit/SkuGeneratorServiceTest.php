<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\SkuGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkuGeneratorServiceTest extends TestCase
{
    use RefreshDatabase;
    public function test_generates_part_sku_with_correct_prefix(): void
    {
        $category = ProductCategory::factory()->create(['item_type' => 'part']);

        $sku = SkuGeneratorService::generate($category);

        $this->assertStringStartsWith('PART-', $sku);
        $this->assertEquals('PART-001', $sku);
    }

    public function test_generates_labor_sku_with_correct_prefix(): void
    {
        $category = ProductCategory::factory()->create(['item_type' => 'labor']);

        $sku = SkuGeneratorService::generate($category);

        $this->assertStringStartsWith('SRV-', $sku);
        $this->assertEquals('SRV-001', $sku);
    }

    public function test_generates_sequential_skus_for_same_type(): void
    {
        $category = ProductCategory::factory()->create(['item_type' => 'part']);

        $sku1 = SkuGeneratorService::generate($category);
        Product::factory()->create(['sku' => $sku1, 'product_category_id' => $category->id]);

        $sku2 = SkuGeneratorService::generate($category);

        $this->assertEquals('PART-001', $sku1);
        $this->assertEquals('PART-002', $sku2);
    }

    public function test_generates_sequential_skus_for_different_types(): void
    {
        $partCategory = ProductCategory::factory()->create(['item_type' => 'part']);
        $laborCategory = ProductCategory::factory()->create(['item_type' => 'labor']);

        $partSku1 = SkuGeneratorService::generate($partCategory);
        Product::factory()->create(['sku' => $partSku1, 'product_category_id' => $partCategory->id]);

        $laborSku1 = SkuGeneratorService::generate($laborCategory);
        Product::factory()->create(['sku' => $laborSku1, 'product_category_id' => $laborCategory->id]);

        $partSku2 = SkuGeneratorService::generate($partCategory);
        $laborSku2 = SkuGeneratorService::generate($laborCategory);

        $this->assertEquals('PART-001', $partSku1);
        $this->assertEquals('PART-002', $partSku2);
        $this->assertEquals('SRV-001', $laborSku1);
        $this->assertEquals('SRV-002', $laborSku2);
    }
}
