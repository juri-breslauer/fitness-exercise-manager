<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        return CategoryResource::collection(
            Category::query()
                ->orderBy('name')
                ->get()
        );
    }
}
