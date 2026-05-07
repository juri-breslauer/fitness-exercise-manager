<?php

namespace Tests\Feature\Services;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Exercise;
use App\Models\Muscle;
use App\Services\ExerciseImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExerciseImportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_imports_exercises_taxonomy_and_relationships(): void
    {
        $result = $this->service()->importJson($this->fixture());

        $this->assertSame([
            'dry_run' => false,
            'created_exercises' => 2,
            'updated_exercises' => 0,
            'created_categories' => 1,
            'created_muscles' => 6,
            'created_equipment' => 1,
            'skipped_rows' => 0,
            'errors' => [],
        ], $result->toArray());

        $this->assertDatabaseHas('categories', [
            'slug' => 'strength',
            'name' => 'Strength',
        ]);

        $this->assertDatabaseHas('equipment', [
            'slug' => 'body-only',
            'name' => 'Body Only',
        ]);

        $this->assertDatabaseHas('exercises', [
            'slug' => 'push-up',
            'name' => 'Push Up',
            'difficulty' => 'beginner',
            'force' => 'push',
            'mechanic' => 'compound',
        ]);

        $pushUp = Exercise::query()->where('slug', 'push-up')->firstOrFail();
        $chest = Muscle::query()->where('slug', 'chest')->firstOrFail();
        $shoulders = Muscle::query()->where('slug', 'shoulders')->firstOrFail();
        $bodyOnly = Equipment::query()->where('slug', 'body-only')->firstOrFail();

        $this->assertDatabaseHas('exercise_muscle', [
            'exercise_id' => $pushUp->id,
            'muscle_id' => $chest->id,
            'role' => Exercise::MUSCLE_ROLE_PRIMARY,
        ]);

        $this->assertDatabaseHas('exercise_muscle', [
            'exercise_id' => $pushUp->id,
            'muscle_id' => $shoulders->id,
            'role' => Exercise::MUSCLE_ROLE_SECONDARY,
        ]);

        $this->assertDatabaseHas('exercise_equipment', [
            'exercise_id' => $pushUp->id,
            'equipment_id' => $bodyOnly->id,
            'is_optional' => false,
        ]);
    }

    public function test_it_is_idempotent_on_repeated_imports(): void
    {
        $this->service()->importJson($this->fixture());
        $secondResult = $this->service()->importJson($this->fixture());

        $this->assertSame(0, $secondResult->createdExercises);
        $this->assertSame(2, $secondResult->updatedExercises);
        $this->assertSame(0, $secondResult->createdCategories);
        $this->assertSame(0, $secondResult->createdMuscles);
        $this->assertSame(0, $secondResult->createdEquipment);

        $this->assertSame(2, Exercise::query()->count());
        $this->assertSame(1, Category::query()->count());
        $this->assertSame(6, Muscle::query()->count());
        $this->assertSame(1, Equipment::query()->count());
        $this->assertSame(6, DB::table('exercise_muscle')->count());
        $this->assertSame(2, DB::table('exercise_equipment')->count());
    }

    public function test_it_supports_dry_run_without_writing_records(): void
    {
        $result = $this->service()->importJson($this->fixture(), dryRun: true);

        $this->assertTrue($result->dryRun);
        $this->assertSame(2, $result->createdExercises);
        $this->assertSame(1, $result->createdCategories);
        $this->assertSame(6, $result->createdMuscles);
        $this->assertSame(1, $result->createdEquipment);

        $this->assertSame(0, Exercise::query()->count());
        $this->assertSame(0, Category::query()->count());
        $this->assertSame(0, Muscle::query()->count());
        $this->assertSame(0, Equipment::query()->count());
    }

    public function test_it_skips_invalid_rows_and_imports_valid_rows(): void
    {
        $payload = json_encode([
            json_decode($this->fixture(), true)[0],
            [
                'slug' => 'missing-taxonomy',
                'name' => 'Missing Taxonomy',
            ],
        ], JSON_THROW_ON_ERROR);

        $result = $this->service()->importJson($payload);

        $this->assertSame(1, $result->createdExercises);
        $this->assertSame(1, $result->skippedRows);
        $this->assertCount(1, $result->errors);
        $this->assertSame(2, $result->errors[0]['row']);
        $this->assertSame(1, Exercise::query()->count());
    }

    protected function service(): ExerciseImportService
    {
        return new ExerciseImportService;
    }

    protected function fixture(): string
    {
        return (string) file_get_contents(base_path('tests/Fixtures/exercises.json'));
    }
}
