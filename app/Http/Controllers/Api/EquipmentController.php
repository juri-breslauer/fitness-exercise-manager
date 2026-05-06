<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EquipmentController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        return EquipmentResource::collection(
            Equipment::query()
                ->orderBy('name')
                ->get()
        );
    }
}
