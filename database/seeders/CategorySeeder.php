<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kaos'],
            ['name' => 'Kemeja'],
            ['name' => 'Celana'],
            ['name' => 'Jaket'],
            ['name' => 'Dress'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}