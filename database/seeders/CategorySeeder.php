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
        Category::create(['name' => 'A']);
        Category::create(['name' => 'AM']);
        Category::create(['name' => 'B']);
        Category::create(['name' => 'C']);
        Category::create(['name' => 'D']);
        Category::create(['name' => 'T']);
        Category::create(['name' => 'A1']);
        Category::create(['name' => 'A2']);
        Category::create(['name' => 'B1']);
        Category::create(['name' => 'C1']);
        Category::create(['name' => 'D1']);
        Category::create(['name' => 'PT']);
    }
}
