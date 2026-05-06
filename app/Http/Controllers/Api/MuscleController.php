<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MuscleResource;
use App\Models\Muscle;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MuscleController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        return MuscleResource::collection(
            Muscle::query()
                ->orderBy('name')
                ->get()
        );
    }
}
