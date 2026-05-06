<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Exercise;
use App\Models\Muscle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExerciseEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_exercises_endpoint_returns_published_exercises_ordered_by_name(): void
    {
        $category = Category::factory()->create(['name' => 'Strength', 'slug' => 'strength']);
        $chest = Muscle::factory()->create(['name' => 'Chest', 'slug' => 'chest']);
        $triceps = Muscle::factory()->create(['name' => 'Triceps', 'slug' => 'triceps']);
        $floor = Equipment::factory()->create(['name' => 'Floor', 'slug' => 'floor']);

        $pushUp = Exercise::factory()->for($category)->create(['name' => 'Push Up', 'slug' => 'push-up']);
        $pushUp->primaryMuscles()->attach($chest, ['role' => Exercise::MUSCLE_ROLE_PRIMARY]);
        $pushUp->secondaryMuscles()->attach($triceps, ['role' => Exercise::MUSCLE_ROLE_SECONDARY]);
        $pushUp->equipment()->attach($floor, ['is_optional' => false]);

        Exercise::factory()->for($category)->create(['name' => 'Bench Press', 'slug' => 'bench-press']);
        Exercise::factory()->for($category)->draft()->create(['name' => 'Draft Lift', 'slug' => 'draft-lift']);

        $this->getJson('/api/v1/exercises')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.name', 'Bench Press')
            ->assertJsonPath('data.0.slug', 'bench-press')
            ->assertJsonPath('data.0.category.slug', 'strength')
            ->assertJsonPath('data.1.name', 'Push Up')
            ->assertJsonPath('data.1.primary_muscles.0.slug', 'chest')
            ->assertJsonPath('data.1.primary_muscles.0.role', Exercise::MUSCLE_ROLE_PRIMARY)
            ->assertJsonPath('data.1.secondary_muscles.0.slug', 'triceps')
            ->assertJsonPath('data.1.secondary_muscles.0.role', Exercise::MUSCLE_ROLE_SECONDARY)
            ->assertJsonPath('data.1.equipment.0.slug', 'floor')
            ->assertJsonPath('data.1.equipment.0.is_optional', false)
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
                        'primary_muscles' => [
                            '*' => ['id', 'name', 'slug', 'role'],
                        ],
                        'secondary_muscles' => [
                            '*' => ['id', 'name', 'slug', 'role'],
                        ],
                        'equipment' => [
                            '*' => ['id', 'name', 'slug', 'is_optional'],
                        ],
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

    public function test_exercises_endpoint_filters_by_muscle_slug(): void
    {
        $category = Category::factory()->create(['name' => 'Strength', 'slug' => 'strength']);
        $biceps = Muscle::factory()->create(['name' => 'Biceps', 'slug' => 'biceps']);
        $chest = Muscle::factory()->create(['name' => 'Chest', 'slug' => 'chest']);

        $curl = Exercise::factory()->for($category)->create(['name' => 'Curl', 'slug' => 'curl']);
        $curl->primaryMuscles()->attach($biceps, ['role' => Exercise::MUSCLE_ROLE_PRIMARY]);

        $pushUp = Exercise::factory()->for($category)->create(['name' => 'Push Up', 'slug' => 'push-up']);
        $pushUp->primaryMuscles()->attach($chest, ['role' => Exercise::MUSCLE_ROLE_PRIMARY]);

        $this->getJson('/api/v1/exercises?muscle=biceps')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'curl')
            ->assertJsonPath('data.0.primary_muscles.0.slug', 'biceps');
    }

    public function test_exercises_endpoint_filters_by_equipment_slug(): void
    {
        $category = Category::factory()->create(['name' => 'Strength', 'slug' => 'strength']);
        $dumbbell = Equipment::factory()->create(['name' => 'Dumbbell', 'slug' => 'dumbbell']);
        $barbell = Equipment::factory()->create(['name' => 'Barbell', 'slug' => 'barbell']);

        $gobletSquat = Exercise::factory()->for($category)->create(['name' => 'Goblet Squat', 'slug' => 'goblet-squat']);
        $gobletSquat->equipment()->attach($dumbbell, ['is_optional' => false]);

        $backSquat = Exercise::factory()->for($category)->create(['name' => 'Back Squat', 'slug' => 'back-squat']);
        $backSquat->equipment()->attach($barbell, ['is_optional' => false]);

        $this->getJson('/api/v1/exercises?equipment=dumbbell')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'goblet-squat')
            ->assertJsonPath('data.0.equipment.0.slug', 'dumbbell')
            ->assertJsonPath('data.0.equipment.0.is_optional', false);
    }

    public function test_exercise_show_endpoint_returns_exercise_by_slug(): void
    {
        $category = Category::factory()->create(['name' => 'Strength', 'slug' => 'strength']);
        $chest = Muscle::factory()->create(['name' => 'Chest', 'slug' => 'chest']);
        $triceps = Muscle::factory()->create(['name' => 'Triceps', 'slug' => 'triceps']);
        $bench = Equipment::factory()->create(['name' => 'Bench', 'slug' => 'bench']);
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
        $exercise->primaryMuscles()->attach($chest, ['role' => Exercise::MUSCLE_ROLE_PRIMARY]);
        $exercise->secondaryMuscles()->attach($triceps, ['role' => Exercise::MUSCLE_ROLE_SECONDARY]);
        $exercise->equipment()->attach($bench, ['is_optional' => true]);

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
            ->assertJsonPath('data.category.slug', 'strength')
            ->assertJsonPath('data.primary_muscles.0.slug', 'chest')
            ->assertJsonPath('data.primary_muscles.0.role', Exercise::MUSCLE_ROLE_PRIMARY)
            ->assertJsonPath('data.secondary_muscles.0.slug', 'triceps')
            ->assertJsonPath('data.secondary_muscles.0.role', Exercise::MUSCLE_ROLE_SECONDARY)
            ->assertJsonPath('data.equipment.0.slug', 'bench')
            ->assertJsonPath('data.equipment.0.is_optional', true);
    }

    public function test_exercise_show_endpoint_does_not_return_draft_exercises(): void
    {
        $exercise = Exercise::factory()->draft()->create(['slug' => 'draft-lift']);

        $this->getJson('/api/v1/exercises/'.$exercise->slug)
            ->assertNotFound();
    }
}
