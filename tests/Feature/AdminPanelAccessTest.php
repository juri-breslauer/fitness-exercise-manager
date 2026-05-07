<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_panel_to_login(): void
    {
        $this->get('/admin')
            ->assertRedirect('/admin/login');
    }

    public function test_admin_login_route_is_available(): void
    {
        $this->get('/admin/login')
            ->assertOk();
    }

    public function test_non_admin_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_user_can_access_admin_panel(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }

    public function test_admin_user_can_access_filament_resource_pages(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $exercise = Exercise::factory()->for($category)->create();

        $this->actingAs($admin);

        $this->get('/admin/categories')->assertOk();
        $this->get('/admin/muscles')->assertOk();
        $this->get('/admin/equipment')->assertOk();
        $this->get('/admin/exercises')->assertOk();
        $this->get('/admin/exercises/'.$exercise->id.'/edit')->assertOk();
        $this->get('/admin/exercise-media')->assertOk();
    }

    public function test_admin_user_can_access_exercise_import_page(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin/import-exercises')
            ->assertOk();
    }
}
