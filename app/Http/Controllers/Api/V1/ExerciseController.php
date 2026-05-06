<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ListExercisesRequest;
use App\Http\Resources\Api\V1\ExerciseResource;
use App\Models\Exercise;
use App\Queries\ExerciseQuery;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExerciseController extends Controller
{
    public function index(ListExercisesRequest $request, ExerciseQuery $exercises): AnonymousResourceCollection
    {
        return ExerciseResource::collection(
            $exercises->paginate($request->validated(), $request->perPage())
        );
    }

    public function show(Exercise $exercise): ExerciseResource
    {
        abort_if($exercise->status !== 'published', 404);

        return new ExerciseResource($exercise->load(['category', 'primaryMuscles', 'secondaryMuscles', 'equipment', 'media', 'primaryMedia']));
    }
}
