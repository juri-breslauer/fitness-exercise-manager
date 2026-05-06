<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Exercise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExerciseEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_exercises_endpoint_returns_published_exercises_ordered_by_name(): void
    {
        $category = Category::factory()->create(['name' => 'Strength', 'slug' => 'strength']);

        Exercise::factory()->for($category)->create(['name' => 'Push Up', 'slug' => 'push-up']);
        Exercise::factory()->for($category)->create(['name' => 'Bench Press', 'slug' => 'bench-press']);
        Exercise::factory()->for($category)->draft()->create(['name' => 'Draft Lift', 'slug' => 'draft-lift']);

        $this->getJson('/api/v1/exercises')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.name', 'Bench Press')
            ->assertJsonPath('data.0.slug', 'bench-press')
            ->assertJsonPath('data.0.category.slug', 'strength')
            ->assertJsonPath('data.1.name', 'Push Up')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'category_id',
                        'slug',
                        'name',
                        'display_name',
                        'aliases',
                        'description',
                        'instructions',
                        'tips',
                        'difficulty',
                        'force',
                        'mechanic',
                        'status',
                        'category' => ['id', 'name', 'slug'],
                    ],
                ],
            ]);
    }

    public function test_exercises_endpoint_filters_by_category_slug(): void
    {
        $strength = Category::factory()->create(['name' => 'Strength', 'slug' => 'strength']);
        $cardio = Category::factory()->create(['name' => 'Cardio', 'slug' => 'cardio']);

        Exercise::factory()->for($strength)->create(['name' => 'Push Up', 'slug' => 'push-up']);
        Exercise::factory()->for($cardio)->create(['name' => 'Jumping Jack', 'slug' => 'jumping-jack']);

        $this->getJson('/api/v1/exercises?category=strength')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'push-up')
            ->assertJsonPath('data.0.category.slug', 'strength');
    }

    public function test_exercise_show_endpoint_returns_exercise_by_slug(): void
    {
        $category = Category::factory()->create(['name' => 'Strength', 'slug' => 'strength']);
        $exercise = Exercise::factory()->for($category)->create([
            'slug' => 'push-up',
            'name' => 'Push Up',
            'display_name' => 'Push-Up',
            'aliases' => ['press up'],
            'instructions' => ['Start in a high plank.', 'Lower under control.'],
            'tips' => ['Keep your body straight.'],
            'difficulty' => 'beginner',
            'force' => 'push',
            'mechanic' => 'compound',
        ]);

        $this->getJson('/api/v1/exercises/'.$exercise->slug)
            ->assertOk()
            ->assertJsonPath('data.slug', 'push-up')
            ->assertJsonPath('data.display_name', 'Push-Up')
            ->assertJsonPath('data.aliases.0', 'press up')
            ->assertJsonPath('data.instructions.1', 'Lower under control.')
            ->assertJsonPath('data.tips.0', 'Keep your body straight.')
            ->assertJsonPath('data.difficulty', 'beginner')
            ->assertJsonPath('data.force', 'push')
            ->assertJsonPath('data.mechanic', 'compound')
            ->assertJsonPath('data.category.slug', 'strength');
    }

    public function test_exercise_show_endpoint_does_not_return_draft_exercises(): void
    {
        $exercise = Exercise::factory()->draft()->create(['slug' => 'draft-lift']);

        $this->getJson('/api/v1/exercises/'.$exercise->slug)
            ->assertNotFound();
    }
}
