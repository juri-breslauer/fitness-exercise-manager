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
        collect([
            ['name' => 'Strength', 'slug' => 'strength'],
            ['name' => 'Cardio', 'slug' => 'cardio'],
            ['name' => 'Mobility', 'slug' => 'mobility'],
            ['name' => 'Flexibility', 'slug' => 'flexibility'],
            ['name' => 'Balance', 'slug' => 'balance'],
        ])->each(fn (array $category) => Category::query()->firstOrCreate(
            ['slug' => $category['slug']],
            ['name' => $category['name']]
        ));
    }
}
