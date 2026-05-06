<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\EquipmentController;
use App\Http\Controllers\Api\V1\ExerciseController;
use App\Http\Controllers\Api\V1\MuscleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('categories', CategoryController::class);
    Route::get('muscles', MuscleController::class);
    Route::get('equipment', EquipmentController::class);
    Route::get('exercises', [ExerciseController::class, 'index']);
    Route::get('exercises/{exercise:slug}', [ExerciseController::class, 'show']);
});
