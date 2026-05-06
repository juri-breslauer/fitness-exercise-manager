<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExerciseController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return ExerciseResource::collection(
            Exercise::query()
                ->with(['category', 'primaryMuscles', 'secondaryMuscles', 'equipment'])
                ->where('status', 'published')
                ->when(
                    $request->query('category'),
                    fn ($query, string $category) => $query->whereRelation('category', 'slug', $category)
                )
                ->when(
                    $request->query('muscle'),
                    fn ($query, string $muscle) => $query->whereRelation('muscles', 'slug', $muscle)
                )
                ->when(
                    $request->query('equipment'),
                    fn ($query, string $equipment) => $query->whereRelation('equipment', 'slug', $equipment)
                )
                ->orderBy('name')
                ->get()
        );
    }

    public function show(Exercise $exercise): ExerciseResource
    {
        abort_if($exercise->status !== 'published', 404);

        return new ExerciseResource($exercise->load(['category', 'primaryMuscles', 'secondaryMuscles', 'equipment']));
    }
}
