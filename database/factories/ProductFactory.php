<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Unit;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_category_id' => ProductCategory::factory(),
            'brand_id' => Brand::factory(),
            'unit_id' => Unit::factory(),
            'sku' => fake()->unique()->bothify('???-###'),
            'name' => fake()->word(),
            'type' => fake()->word(),
            'keyword' => fake()->word(),
            'compatibility' => fake()->word(),
            'size' => fake()->word(),
            'description' => fake()->sentence(),
        ];
    }
}
