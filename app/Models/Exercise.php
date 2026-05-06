<?php

namespace App\Models;

use Database\Factories\ExerciseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
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
])]
class Exercise extends Model
{
    /** @use HasFactory<ExerciseFactory> */
    use HasFactory;

    public const MUSCLE_ROLE_PRIMARY = 'primary';

    public const MUSCLE_ROLE_SECONDARY = 'secondary';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'aliases' => 'array',
            'instructions' => 'array',
            'tips' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Category, Exercise>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsToMany<Muscle, Exercise>
     */
    public function muscles(): BelongsToMany
    {
        return $this->belongsToMany(Muscle::class)
            ->withPivot('role')
            ->withTimestamps()
            ->orderBy('name');
    }

    /**
     * @return BelongsToMany<Muscle, Exercise>
     */
    public function primaryMuscles(): BelongsToMany
    {
        return $this->muscles()->wherePivot('role', self::MUSCLE_ROLE_PRIMARY);
    }

    /**
     * @return BelongsToMany<Muscle, Exercise>
     */
    public function secondaryMuscles(): BelongsToMany
    {
        return $this->muscles()->wherePivot('role', self::MUSCLE_ROLE_SECONDARY);
    }

    /**
     * @return BelongsToMany<Equipment, Exercise>
     */
    public function equipment(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'exercise_equipment')
            ->withPivot('is_optional')
            ->withTimestamps()
            ->orderBy('name');
    }
}
