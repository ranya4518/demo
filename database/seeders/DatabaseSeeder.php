<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Category::factory(10)->create()->each(function ($category) {
            // For each category, create multiple products and associate them
            Product::factory(rand(1, 5))->create(['category_id' => $category->id]);
        });

    }
}
