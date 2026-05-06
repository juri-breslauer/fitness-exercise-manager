<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
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
            'category_id' => $this->category_id,
            'slug' => $this->slug,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'aliases' => $this->aliases,
            'description' => $this->description,
            'instructions' => $this->instructions,
            'tips' => $this->tips,
            'difficulty' => $this->difficulty,
            'force' => $this->force,
            'mechanic' => $this->mechanic,
            'status' => $this->status,
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
