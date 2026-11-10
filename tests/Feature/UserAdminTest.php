<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $u = User::factory()->create();
        $u->role = User::ROLE_ADMIN;
        $u->save();

        return $u;
    }

    public function test_staff_cannot_access_user_management(): void
    {
        $this->actingAs(User::factory()->create())->get('/users')->assertForbidden();
    }

    public function test_admin_can_list_and_change_roles(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($this->admin())->get('/users')->assertOk()->assertSee($staff->email);
        $this->put("/users/{$staff->id}", ['role' => 'admin'])->assertSessionHas('success');
        $this->assertTrue($staff->fresh()->isAdmin());
    }

    public function test_role_must_be_valid_and_admin_cannot_change_or_delete_self(): void
    {
        $admin = $this->admin();
        $staff = User::factory()->create();

        $this->actingAs($admin)->put("/users/{$staff->id}", ['role' => 'superuser'])->assertSessionHasErrors('role');
        $this->put("/users/{$admin->id}", ['role' => 'staff'])->assertSessionHasErrors('role');
        $this->assertTrue($admin->fresh()->isAdmin());

        $this->delete("/users/{$admin->id}")->assertSessionHasErrors('role');
        $this->assertModelExists($admin);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($this->admin())->delete("/users/{$staff->id}")->assertSessionHas('success');
        $this->assertModelMissing($staff);
    }

    public function test_web_login_is_rate_limited(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->post('/login', ['email' => 'x@example.com', 'password' => 'bad']);
        }

        $this->post('/login', ['email' => 'x@example.com', 'password' => 'bad'])->assertStatus(429);
    }
}
