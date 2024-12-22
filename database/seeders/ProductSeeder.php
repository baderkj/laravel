<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Store;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Product 1',
            'price' => 100,
            'type'=>'food',
            'quantity'=>66,
            'store_id'=>1,
            'image' => 'images/products/product1.jpg', // Static image path
        ]);

        Product::create([
            'name' => 'Product 2',
            'price' => 150,
            'type'=>'food',
            'quantity'=>66,
            'store_id'=>1,
            'image' => 'images/products/product2.jpg', // Static image path
        ]);
    }
}
