<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Jewelry', 'description' => 'Elegant and stylish pieces to complement your look.'],
            ['name' => 'Home Decor', 'description' => 'Beautiful additions to make your space feel like home.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}