<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Muscle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxonomyEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_endpoint_returns_categories_ordered_by_name(): void
    {
        Category::factory()->create(['name' => 'Strength', 'slug' => 'strength']);
        Category::factory()->create(['name' => 'Cardio', 'slug' => 'cardio']);

        $this->getJson('/api/v1/categories')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Cardio')
            ->assertJsonPath('data.0.slug', 'cardio')
            ->assertJsonPath('data.1.name', 'Strength')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug'],
                ],
            ]);
    }

    public function test_muscles_endpoint_returns_muscles_ordered_by_name(): void
    {
        Muscle::factory()->create(['name' => 'Triceps', 'slug' => 'triceps']);
        Muscle::factory()->create(['name' => 'Biceps', 'slug' => 'biceps']);

        $this->getJson('/api/v1/muscles')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Biceps')
            ->assertJsonPath('data.0.slug', 'biceps')
            ->assertJsonPath('data.1.name', 'Triceps')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug'],
                ],
            ]);
    }

    public function test_equipment_endpoint_returns_equipment_ordered_by_name(): void
    {
        Equipment::factory()->create(['name' => 'Dumbbells', 'slug' => 'dumbbells']);
        Equipment::factory()->create(['name' => 'Barbell', 'slug' => 'barbell']);

        $this->getJson('/api/v1/equipment')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Barbell')
            ->assertJsonPath('data.0.slug', 'barbell')
            ->assertJsonPath('data.1.name', 'Dumbbells')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug'],
                ],
            ]);
    }
}
