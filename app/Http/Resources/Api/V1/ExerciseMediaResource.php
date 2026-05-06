<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseMediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'url' => $this->url,
            'disk' => $this->disk,
            'path' => $this->path,
            'source' => $this->source,
            'position' => $this->position,
            'is_primary' => $this->is_primary,
            'metadata' => $this->metadata,
        ];
    }
}
