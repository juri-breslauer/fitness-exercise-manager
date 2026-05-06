<?php

namespace App\Models;

use Database\Factories\ExerciseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
