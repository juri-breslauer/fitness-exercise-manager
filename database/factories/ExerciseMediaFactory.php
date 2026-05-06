<?php

namespace Database\Factories;

use App\Models\Exercise;
use App\Models\ExerciseMedia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExerciseMedia>
 */
class ExerciseMediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exercise_id' => Exercise::factory(),
            'type' => fake()->randomElement([
                ExerciseMedia::TYPE_IMAGE,
                ExerciseMedia::TYPE_GIF,
                ExerciseMedia::TYPE_VIDEO,
            ]),
            'url' => fake()->imageUrl(width: 1200, height: 800),
            'disk' => null,
            'path' => null,
            'source' => fake()->optional()->url(),
            'position' => fake()->numberBetween(0, 5),
            'is_primary' => false,
            'metadata' => fake()->optional()->randomElement([
                ['alt' => fake()->sentence(4)],
                ['width' => 1200, 'height' => 800],
            ]),
        ];
    }

    public function primary(): static
    {
        return $this->state(fn (): array => [
            'is_primary' => true,
            'position' => 0,
        ]);
    }
}
