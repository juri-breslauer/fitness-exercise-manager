<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Exercise;
use App\Models\Muscle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JsonException;

class ExerciseImportService
{
    /**
     * @return array<string, array<string, string>>
     */
    protected function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'aliases' => ['nullable', 'array'],
            'aliases.*' => ['string', 'max:255'],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'array'],
            'instructions.*' => ['string'],
            'tips' => ['nullable', 'array'],
            'tips.*' => ['string'],
            'difficulty' => ['nullable', 'string', 'max:255'],
            'force' => ['nullable', 'string', 'max:255'],
            'mechanic' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'primary_muscles' => ['required', 'array', 'min:1'],
            'primary_muscles.*' => ['string', 'max:255'],
            'secondary_muscles' => ['nullable', 'array'],
            'secondary_muscles.*' => ['string', 'max:255'],
            'equipment' => ['nullable', 'array'],
            'equipment.*' => ['string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function importJson(string $json, bool $dryRun = false): ExerciseImportResult
    {
        $result = new ExerciseImportResult(dryRun: $dryRun);

        try {
            $rows = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            $result->addError(null, 'Invalid JSON: '.$exception->getMessage());
            $result->skippedRows++;

            return $result;
        }

        if (! is_array($rows) || ! array_is_list($rows)) {
            $result->addError(null, 'Import file must contain a JSON array of exercise objects.');
            $result->skippedRows++;

            return $result;
        }

        $validRows = $this->validatedRows($rows, $result);

        if ($dryRun) {
            $this->preview($validRows, $result);

            return $result;
        }

        DB::transaction(fn () => $this->persist($validRows, $result));

        return $result;
    }

    /**
     * @param  array<int, mixed>  $rows
     * @return array<int, array<string, mixed>>
     */
    protected function validatedRows(array $rows, ExerciseImportResult $result): array
    {
        $validRows = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 1;

            if (! is_array($row)) {
                $result->skippedRows++;
                $result->addError($rowNumber, 'Row must be an object.');

                continue;
            }

            $validator = Validator::make($row, $this->rules());

            if ($validator->fails()) {
                $result->skippedRows++;
                $result->addError($rowNumber, $validator->errors()->first() ?? 'Row is invalid.');

                continue;
            }

            $validRows[] = $this->normalizeRow($validator->validated());
        }

        return $validRows;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array{
     *     slug: string,
     *     name: string,
     *     display_name: string|null,
     *     aliases: array<int, string>|null,
     *     description: string|null,
     *     instructions: array<int, string>|null,
     *     tips: array<int, string>|null,
     *     difficulty: string|null,
     *     force: string|null,
     *     mechanic: string|null,
     *     status: string,
     *     category: string,
     *     primary_muscles: array<int, string>,
     *     secondary_muscles: array<int, string>,
     *     equipment: array<int, string>
     * }
     */
    protected function normalizeRow(array $row): array
    {
        return [
            'slug' => $this->slug($row['slug']),
            'name' => trim($row['name']),
            'display_name' => $this->nullableString($row['display_name'] ?? null),
            'aliases' => $this->stringList($row['aliases'] ?? []),
            'description' => $this->nullableString($row['description'] ?? null),
            'instructions' => $this->stringList($row['instructions'] ?? []),
            'tips' => $this->stringList($row['tips'] ?? []),
            'difficulty' => $this->nullableString($row['difficulty'] ?? null),
            'force' => $this->nullableString($row['force'] ?? null),
            'mechanic' => $this->nullableString($row['mechanic'] ?? null),
            'status' => $this->nullableString($row['status'] ?? null) ?? 'published',
            'category' => $this->taxonomyValue($row['category']),
            'primary_muscles' => $this->taxonomyList($row['primary_muscles']),
            'secondary_muscles' => $this->taxonomyList($row['secondary_muscles'] ?? []),
            'equipment' => $this->taxonomyList($row['equipment'] ?? []),
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    protected function preview(array $rows, ExerciseImportResult $result): void
    {
        $seenCategories = [];
        $seenMuscles = [];
        $seenEquipment = [];
        $seenExercises = [];

        foreach ($rows as $row) {
            $this->countTaxonomyPreview(Category::class, $row['category'], $seenCategories, $result->createdCategories);

            foreach ([...$row['primary_muscles'], ...$row['secondary_muscles']] as $muscle) {
                $this->countTaxonomyPreview(Muscle::class, $muscle, $seenMuscles, $result->createdMuscles);
            }

            foreach ($row['equipment'] as $equipment) {
                $this->countTaxonomyPreview(Equipment::class, $equipment, $seenEquipment, $result->createdEquipment);
            }

            if (isset($seenExercises[$row['slug']])) {
                $result->updatedExercises++;
            } elseif (Exercise::query()->where('slug', $row['slug'])->exists()) {
                $result->updatedExercises++;
            } else {
                $result->createdExercises++;
            }

            $seenExercises[$row['slug']] = true;
        }
    }

    /**
     * @param  class-string<Category|Muscle|Equipment>  $modelClass
     * @param  array<string, bool>  $seen
     */
    protected function countTaxonomyPreview(string $modelClass, string $value, array &$seen, int &$counter): void
    {
        $slug = $this->slug($value);

        if (isset($seen[$slug])) {
            return;
        }

        if (! $modelClass::query()->where('slug', $slug)->exists()) {
            $counter++;
        }

        $seen[$slug] = true;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    protected function persist(array $rows, ExerciseImportResult $result): void
    {
        foreach ($rows as $row) {
            $category = $this->upsertTaxonomy(Category::class, $row['category'], $result->createdCategories);

            $exercise = Exercise::query()->updateOrCreate(
                ['slug' => $row['slug']],
                [
                    'category_id' => $category->id,
                    'name' => $row['name'],
                    'display_name' => $row['display_name'],
                    'aliases' => $row['aliases'],
                    'description' => $row['description'],
                    'instructions' => $row['instructions'],
                    'tips' => $row['tips'],
                    'difficulty' => $row['difficulty'],
                    'force' => $row['force'],
                    'mechanic' => $row['mechanic'],
                    'status' => $row['status'],
                ],
            );

            if ($exercise->wasRecentlyCreated) {
                $result->createdExercises++;
            } else {
                $result->updatedExercises++;
            }

            $primaryMuscles = $this->upsertTaxonomyList(Muscle::class, $row['primary_muscles'], $result->createdMuscles);
            $secondaryMuscles = $this->upsertTaxonomyList(Muscle::class, $row['secondary_muscles'], $result->createdMuscles);
            $equipment = $this->upsertTaxonomyList(Equipment::class, $row['equipment'], $result->createdEquipment);

            $this->syncMuscles($exercise, $primaryMuscles, $secondaryMuscles);
            $this->syncEquipment($exercise, $equipment);
        }
    }

    /**
     * @param  class-string<Category|Muscle|Equipment>  $modelClass
     */
    protected function upsertTaxonomy(string $modelClass, string $value, int &$createdCounter): Category|Muscle|Equipment
    {
        $model = $modelClass::query()->firstOrCreate(
            ['slug' => $this->slug($value)],
            ['name' => $this->nameFromTaxonomyValue($value)],
        );

        if ($model->wasRecentlyCreated) {
            $createdCounter++;
        }

        return $model;
    }

    /**
     * @param  class-string<Muscle|Equipment>  $modelClass
     * @param  array<int, string>  $values
     * @return array<int, Muscle|Equipment>
     */
    protected function upsertTaxonomyList(string $modelClass, array $values, int &$createdCounter): array
    {
        $models = [];

        foreach ($values as $value) {
            $models[] = $this->upsertTaxonomy($modelClass, $value, $createdCounter);
        }

        return $models;
    }

    /**
     * @param  array<int, Muscle|Equipment>  $primaryMuscles
     * @param  array<int, Muscle|Equipment>  $secondaryMuscles
     */
    protected function syncMuscles(Exercise $exercise, array $primaryMuscles, array $secondaryMuscles): void
    {
        DB::table('exercise_muscle')->where('exercise_id', $exercise->id)->delete();

        $rows = [];

        foreach ($primaryMuscles as $muscle) {
            $rows[] = [
                'exercise_id' => $exercise->id,
                'muscle_id' => $muscle->id,
                'role' => Exercise::MUSCLE_ROLE_PRIMARY,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($secondaryMuscles as $muscle) {
            $rows[] = [
                'exercise_id' => $exercise->id,
                'muscle_id' => $muscle->id,
                'role' => Exercise::MUSCLE_ROLE_SECONDARY,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($rows !== []) {
            DB::table('exercise_muscle')->insertOrIgnore($rows);
        }
    }

    /**
     * @param  array<int, Muscle|Equipment>  $equipment
     */
    protected function syncEquipment(Exercise $exercise, array $equipment): void
    {
        DB::table('exercise_equipment')->where('exercise_id', $exercise->id)->delete();

        $rows = array_map(fn (Equipment $item): array => [
            'exercise_id' => $exercise->id,
            'equipment_id' => $item->id,
            'is_optional' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ], $equipment);

        if ($rows !== []) {
            DB::table('exercise_equipment')->insertOrIgnore($rows);
        }
    }

    protected function slug(string $value): string
    {
        return Str::slug($value);
    }

    protected function nameFromTaxonomyValue(string $value): string
    {
        return Str::of($value)
            ->replace(['-', '_'], ' ')
            ->squish()
            ->title()
            ->toString();
    }

    protected function taxonomyValue(string $value): string
    {
        return Str::of($value)->squish()->lower()->toString();
    }

    /**
     * @param  array<int, string>  $values
     * @return array<int, string>
     */
    protected function taxonomyList(array $values): array
    {
        return collect($values)
            ->map(fn (string $value): string => $this->taxonomyValue($value))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function nullableString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }

    /**
     * @param  array<int, string>  $values
     * @return array<int, string>|null
     */
    protected function stringList(array $values): ?array
    {
        $items = collect($values)
            ->map(fn (string $value): string => trim($value))
            ->filter()
            ->values()
            ->all();

        return $items === [] ? null : $items;
    }
}
