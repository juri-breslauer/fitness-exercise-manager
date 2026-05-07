<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Exercise;
use App\Models\ExerciseMedia;
use App\Models\Muscle;
use App\Policies\CategoryPolicy;
use App\Policies\EquipmentPolicy;
use App\Policies\ExerciseMediaPolicy;
use App\Policies\ExercisePolicy;
use App\Policies\MusclePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Muscle::class, MusclePolicy::class);
        Gate::policy(Equipment::class, EquipmentPolicy::class);
        Gate::policy(Exercise::class, ExercisePolicy::class);
        Gate::policy(ExerciseMedia::class, ExerciseMediaPolicy::class);
    }
}
