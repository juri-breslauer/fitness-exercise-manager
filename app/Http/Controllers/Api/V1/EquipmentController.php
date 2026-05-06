<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\EquipmentResource;
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
