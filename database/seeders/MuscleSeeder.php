<?php

namespace Database\Seeders;

use App\Models\Muscle;
use Illuminate\Database\Seeder;

class MuscleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['name' => 'Chest', 'slug' => 'chest'],
            ['name' => 'Back', 'slug' => 'back'],
            ['name' => 'Shoulders', 'slug' => 'shoulders'],
            ['name' => 'Biceps', 'slug' => 'biceps'],
            ['name' => 'Triceps', 'slug' => 'triceps'],
            ['name' => 'Forearms', 'slug' => 'forearms'],
            ['name' => 'Core', 'slug' => 'core'],
            ['name' => 'Glutes', 'slug' => 'glutes'],
            ['name' => 'Quadriceps', 'slug' => 'quadriceps'],
            ['name' => 'Hamstrings', 'slug' => 'hamstrings'],
            ['name' => 'Calves', 'slug' => 'calves'],
            ['name' => 'Full Body', 'slug' => 'full-body'],
        ])->each(fn (array $muscle) => Muscle::query()->firstOrCreate(
            ['slug' => $muscle['slug']],
            ['name' => $muscle['name']]
        ));
    }
}
