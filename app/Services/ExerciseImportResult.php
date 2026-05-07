<?php

namespace App\Services;

class ExerciseImportResult
{
    /**
     * @param  array<int, array{row: int|null, message: string}>  $errors
     */
    public function __construct(
        public bool $dryRun = false,
        public int $createdExercises = 0,
        public int $updatedExercises = 0,
        public int $createdCategories = 0,
        public int $createdMuscles = 0,
        public int $createdEquipment = 0,
        public int $skippedRows = 0,
        public array $errors = [],
    ) {}

    public function addError(?int $row, string $message): void
    {
        $this->errors[] = [
            'row' => $row,
            'message' => $message,
        ];
    }

    /**
     * @return array{
     *     dry_run: bool,
     *     created_exercises: int,
     *     updated_exercises: int,
     *     created_categories: int,
     *     created_muscles: int,
     *     created_equipment: int,
     *     skipped_rows: int,
     *     errors: array<int, array{row: int|null, message: string}>
     * }
     */
    public function toArray(): array
    {
        return [
            'dry_run' => $this->dryRun,
            'created_exercises' => $this->createdExercises,
            'updated_exercises' => $this->updatedExercises,
            'created_categories' => $this->createdCategories,
            'created_muscles' => $this->createdMuscles,
            'created_equipment' => $this->createdEquipment,
            'skipped_rows' => $this->skippedRows,
            'errors' => $this->errors,
        ];
    }
}
