<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'pricing_mode' => fake()->randomElement(['fixed', 'variable']),
            'item_type' => fake()->randomElement(['part', 'labor']),
            'parent_id' => null,
        ];
    }
}
