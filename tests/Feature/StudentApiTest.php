<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StudentApiTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $o = []): array
    {
        return array_merge([
            'student_number' => '2026-0001', 'first_name' => 'Ana', 'last_name' => 'Cruz',
            'email' => 'ana@example.com', 'course' => 'BSIT', 'year_level' => 2,
        ], $o);
    }

    private function actAs(string $role = 'staff'): User
    {
        $user = User::factory()->create();
        $user->role = $role;
        $user->save();
        Sanctum::actingAs($user);

        return $user;
    }

    public function test_requires_authentication(): void
    {
        $this->getJson('/api/students')->assertUnauthorized();
    }

    public function test_token_login(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'password123'])
            ->assertOk()->assertJsonStructure(['token', 'token_type', 'role']);
        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'nope'])->assertUnauthorized();
    }

    public function test_index_and_show(): void
    {
        $this->actAs();
        $students = Student::factory()->count(3)->create();

        $this->getJson('/api/students')->assertOk()->assertJsonCount(3, 'data')->assertJsonStructure(['data', 'links', 'meta']);
        $this->getJson("/api/students/{$students[0]->id}")->assertOk()->assertJsonPath('data.id', $students[0]->id);
        $this->getJson('/api/students/9999')->assertNotFound()->assertJson(['message' => 'Resource not found.']);
    }

    public function test_store_returns_201_and_validates(): void
    {
        $this->actAs();

        $this->postJson('/api/students', $this->payload())->assertCreated()->assertJsonPath('data.email', 'ana@example.com');
        $this->postJson('/api/students', $this->payload())->assertUnprocessable()->assertJsonValidationErrors(['student_number', 'email']);
    }

    public function test_update(): void
    {
        $this->actAs();
        $student = Student::factory()->create();

        $this->putJson("/api/students/{$student->id}", $this->payload())->assertOk()->assertJsonPath('data.first_name', 'Ana');
    }

    public function test_delete_is_admin_only(): void
    {
        $student = Student::factory()->create();

        $this->actAs('staff');
        $this->deleteJson("/api/students/{$student->id}")->assertForbidden();

        $this->actAs('admin');
        $this->deleteJson("/api/students/{$student->id}")->assertNoContent();
        $this->assertModelMissing($student);
    }
}
