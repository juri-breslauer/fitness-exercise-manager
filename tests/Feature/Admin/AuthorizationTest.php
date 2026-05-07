<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Exercise;
use App\Models\ExerciseMedia;
use App\Models\Muscle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin')
            ->assertRedirect('/admin/login');

        $this->get('/admin/profile')
            ->assertRedirect('/admin/login');
    }

    public function test_non_admin_user_cannot_access_admin_panel_or_resources(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $exercise = Exercise::factory()->for($category)->create();

        $this->actingAs($user);

        $this->get('/admin')->assertForbidden();
        $this->get('/admin/profile')->assertForbidden();
        $this->get('/admin/categories')->assertForbidden();
        $this->get('/admin/exercises/'.$exercise->id.'/edit')->assertForbidden();
        $this->get('/admin/import-exercises')->assertForbidden();
    }

    public function test_admin_user_can_access_admin_panel_and_catalog_resources(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $exercise = Exercise::factory()->for($category)->create();
        $media = ExerciseMedia::factory()->for($exercise)->create();

        $this->actingAs($admin);

        $this->get('/admin')->assertOk();
        $this->get('/admin/profile')->assertOk();
        $this->get('/admin/categories')->assertOk();
        $this->get('/admin/categories/create')->assertOk();
        $this->get('/admin/categories/'.$category->id.'/edit')->assertOk();
        $this->get('/admin/muscles')->assertOk();
        $this->get('/admin/equipment')->assertOk();
        $this->get('/admin/exercises')->assertOk();
        $this->get('/admin/exercises/create')->assertOk();
        $this->get('/admin/exercises/'.$exercise->id.'/edit')->assertOk();
        $this->get('/admin/exercise-media')->assertOk();
        $this->get('/admin/exercise-media/'.$media->id.'/edit')->assertOk();
        $this->get('/admin/import-exercises')->assertOk();
    }

    public function test_catalog_policies_allow_only_admin_users_to_manage_models(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $muscle = Muscle::factory()->create();
        $equipment = Equipment::factory()->create();
        $exercise = Exercise::factory()->for($category)->create();
        $media = ExerciseMedia::factory()->for($exercise)->create();

        $models = [
            Category::class => $category,
            Muscle::class => $muscle,
            Equipment::class => $equipment,
            Exercise::class => $exercise,
            ExerciseMedia::class => $media,
        ];

        foreach ($models as $modelClass => $record) {
            $this->assertFalse(Gate::forUser($user)->allows('viewAny', $modelClass));
            $this->assertFalse(Gate::forUser($user)->allows('create', $modelClass));
            $this->assertFalse(Gate::forUser($user)->allows('update', $record));
            $this->assertFalse(Gate::forUser($user)->allows('delete', $record));

            $this->assertTrue(Gate::forUser($admin)->allows('viewAny', $modelClass));
            $this->assertTrue(Gate::forUser($admin)->allows('create', $modelClass));
            $this->assertTrue(Gate::forUser($admin)->allows('update', $record));
            $this->assertTrue(Gate::forUser($admin)->allows('delete', $record));
        }
    }

    public function test_public_api_v1_remains_unauthenticated_and_read_only(): void
    {
        Category::factory()->create(['name' => 'Strength', 'slug' => 'strength']);

        $this->getJson('/api/v1/categories')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'strength');

        $this->postJson('/api/v1/categories', [
            'name' => 'Mobility',
            'slug' => 'mobility',
        ])->assertMethodNotAllowed();
    }
}
