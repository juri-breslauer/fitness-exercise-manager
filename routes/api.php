<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EquipmentController;
use App\Http\Controllers\Api\MuscleController;
use Illuminate\Support\Facades\Route;

Route::get('categories', CategoryController::class);
Route::get('muscles', MuscleController::class);
Route::get('equipment', EquipmentController::class);
