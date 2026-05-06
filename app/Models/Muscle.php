<?php

namespace App\Models;

use Database\Factories\MuscleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'slug'])]
class Muscle extends Model
{
    /** @use HasFactory<MuscleFactory> */
    use HasFactory;

    /**
     * @return BelongsToMany<Exercise, Muscle>
     */
    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class)
            ->withPivot('role')
            ->withTimestamps();
    }
}
