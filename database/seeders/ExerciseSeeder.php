<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Exercise;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $strength = Category::query()->where('slug', 'strength')->firstOrFail();
        $cardio = Category::query()->where('slug', 'cardio')->firstOrFail();
        $mobility = Category::query()->where('slug', 'mobility')->firstOrFail();

        collect([
            [
                'category_id' => $strength->id,
                'slug' => 'push-up',
                'name' => 'Push Up',
                'display_name' => 'Push-Up',
                'aliases' => ['press up'],
                'description' => 'A bodyweight upper-body pushing exercise.',
                'instructions' => [
                    'Start in a high plank position.',
                    'Lower your chest toward the floor.',
                    'Press back to the starting position.',
                ],
                'tips' => [
                    'Keep your body in a straight line.',
                    'Control the lowering phase.',
                ],
                'difficulty' => 'beginner',
                'force' => 'push',
                'mechanic' => 'compound',
                'status' => 'published',
            ],
            [
                'category_id' => $strength->id,
                'slug' => 'bodyweight-squat',
                'name' => 'Bodyweight Squat',
                'display_name' => null,
                'aliases' => ['air squat'],
                'description' => 'A lower-body squat pattern performed without external load.',
                'instructions' => [
                    'Stand with feet around shoulder width.',
                    'Sit your hips down and back.',
                    'Drive through your feet to stand tall.',
                ],
                'tips' => [
                    'Keep your chest tall.',
                    'Track knees in line with toes.',
                ],
                'difficulty' => 'beginner',
                'force' => 'push',
                'mechanic' => 'compound',
                'status' => 'published',
            ],
            [
                'category_id' => $cardio->id,
                'slug' => 'jumping-jack',
                'name' => 'Jumping Jack',
                'display_name' => null,
                'aliases' => null,
                'description' => 'A simple full-body conditioning movement.',
                'instructions' => [
                    'Start standing tall.',
                    'Jump feet out while raising your arms overhead.',
                    'Jump back to the starting position.',
                ],
                'tips' => [
                    'Land softly.',
                    'Keep a steady rhythm.',
                ],
                'difficulty' => 'beginner',
                'force' => 'push',
                'mechanic' => 'compound',
                'status' => 'published',
            ],
            [
                'category_id' => $mobility->id,
                'slug' => 'worlds-greatest-stretch',
                'name' => 'Worlds Greatest Stretch',
                'display_name' => "World's Greatest Stretch",
                'aliases' => null,
                'description' => 'A mobility drill for hips, hamstrings, and thoracic rotation.',
                'instructions' => [
                    'Step into a long lunge.',
                    'Place both hands inside the front foot.',
                    'Rotate the inside arm toward the ceiling.',
                ],
                'tips' => [
                    'Move slowly through each position.',
                    'Keep breathing throughout the stretch.',
                ],
                'difficulty' => 'intermediate',
                'force' => 'static',
                'mechanic' => null,
                'status' => 'published',
            ],
        ])->each(fn (array $exercise) => Exercise::query()->updateOrCreate(
            ['slug' => $exercise['slug']],
            $exercise
        ));
    }
}
