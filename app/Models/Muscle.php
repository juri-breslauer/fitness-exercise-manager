<?php

namespace App\Models;

use Database\Factories\MuscleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug'])]
class Muscle extends Model
{
    /** @use HasFactory<MuscleFactory> */
    use HasFactory;
}
