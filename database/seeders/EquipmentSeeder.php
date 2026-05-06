<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['name' => 'Bodyweight', 'slug' => 'bodyweight'],
            ['name' => 'Dumbbells', 'slug' => 'dumbbells'],
            ['name' => 'Barbell', 'slug' => 'barbell'],
            ['name' => 'Kettlebell', 'slug' => 'kettlebell'],
            ['name' => 'Resistance Band', 'slug' => 'resistance-band'],
            ['name' => 'Cable Machine', 'slug' => 'cable-machine'],
            ['name' => 'Pull-up Bar', 'slug' => 'pull-up-bar'],
            ['name' => 'Bench', 'slug' => 'bench'],
            ['name' => 'Medicine Ball', 'slug' => 'medicine-ball'],
            ['name' => 'Treadmill', 'slug' => 'treadmill'],
            ['name' => 'Stationary Bike', 'slug' => 'stationary-bike'],
            ['name' => 'Rowing Machine', 'slug' => 'rowing-machine'],
        ])->each(fn (array $equipment) => Equipment::query()->firstOrCreate(
            ['slug' => $equipment['slug']],
            ['name' => $equipment['name']]
        ));
    }
}
