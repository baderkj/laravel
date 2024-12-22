<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Store;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>fake()->word(),
            'type'=>fake()->word(),
            'price'=>fake()->numberBetween(1,100),
            'quantity'=>fake()->numberBetween(1,50),
            'image'=>'images/products/product'.fake()->numberBetween(1,11).'.jpg',
            'description'=>fake()->text(),
            'store_id'=>Store::factory()
        ];
    }
}
