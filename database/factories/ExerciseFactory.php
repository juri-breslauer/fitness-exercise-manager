<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Exercise>
 */
class ExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'slug' => Str::slug($name),
            'name' => $name,
            'display_name' => fake()->optional()->words(3, true),
            'aliases' => fake()->optional()->randomElements([
                'press',
                'push',
                'squat',
                'row',
                'hinge',
            ], 2),
            'description' => fake()->optional()->paragraph(),
            'instructions' => fake()->optional()->randomElements([
                'Set your starting position.',
                'Brace your core.',
                'Move through a controlled range of motion.',
                'Return to the starting position.',
            ], 3),
            'tips' => fake()->optional()->randomElements([
                'Keep the tempo controlled.',
                'Avoid locking out aggressively.',
                'Stop if form breaks down.',
            ], 2),
            'difficulty' => fake()->optional()->randomElement(['beginner', 'intermediate', 'expert']),
            'force' => fake()->optional()->randomElement(['push', 'pull', 'static']),
            'mechanic' => fake()->optional()->randomElement(['compound', 'isolation']),
            'status' => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => [
            'status' => 'draft',
        ]);
    }
}
