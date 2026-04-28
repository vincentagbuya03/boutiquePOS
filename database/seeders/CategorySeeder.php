<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Shirts',
            'description' => 'T-shirts and formal shirts'
        ]);

        Category::create([
            'name' => 'Pants',
            'description' => 'Jeans and formal pants'
        ]);

        Category::create([
            'name' => 'Shoes',
            'description' => 'All types of shoes'
        ]);

        Category::create([
            'name' => 'Accessories',
            'description' => 'Bags, belts, and other accessories'
        ]);
    }
}
