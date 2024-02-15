<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Assuming you have 'Jewelry' and 'Home Decor' categories seeded already
        $jewelryCategory = Category::where('name', 'Jewelry')->first();
        $homeDecorCategory = Category::where('name', 'Home Decor')->first();

        $products = [
            ['name' => 'Silver Necklace', 'description' => 'A beautiful silver necklace.', 'price' => 49.99, 'category_id' => $jewelryCategory->id],
            ['name' => 'Elegant Vase', 'description' => 'A perfect addition to any room.', 'price' => 29.99, 'category_id' => $homeDecorCategory->id],
            // Add more products as needed
        ];

        foreach ($products as $product) {
            Product::create([
                // 'image' => $product['image'],
                'image' => 'https://fakeimg.pl/640x360',
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'category_id' => $product['category_id'],
                'quantity' => 10, // Default quantity
                'stock' => 10, // Default stock
            ]);
        }
    }
}