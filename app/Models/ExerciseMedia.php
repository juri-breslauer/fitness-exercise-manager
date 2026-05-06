<?php

namespace App\Models;

use Database\Factories\ExerciseMediaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'exercise_id',
    'type',
    'url',
    'disk',
    'path',
    'source',
    'position',
    'is_primary',
    'metadata',
])]
class ExerciseMedia extends Model
{
    /** @use HasFactory<ExerciseMediaFactory> */
    use HasFactory;

    public const TYPE_IMAGE = 'image';

    public const TYPE_GIF = 'gif';

    public const TYPE_VIDEO = 'video';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'metadata' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Exercise, ExerciseMedia>
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
