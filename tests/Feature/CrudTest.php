<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $u = User::factory()->create();
        $u->role = User::ROLE_ADMIN;
        $u->save();

        return $u;
    }

    private function staff(): User
    {
        return User::factory()->create();
    }

    private function studentData(array $o = []): array
    {
        return array_merge([
            'student_number' => '2026-0001', 'first_name' => 'Ana', 'last_name' => 'Cruz',
            'email' => 'ana@example.com', 'course' => 'BSIT', 'year_level' => 2,
        ], $o);
    }

    public function test_staff_can_create_and_update_student(): void
    {
        $this->actingAs($this->staff())->post('/students', $this->studentData())->assertRedirect();
        $student = Student::firstOrFail();

        $this->put("/students/{$student->id}", $this->studentData(['first_name' => 'Anna']))->assertRedirect();
        $this->assertSame('Anna', $student->fresh()->first_name);
    }

    public function test_student_validation(): void
    {
        Student::factory()->create(['student_number' => '2026-0001', 'email' => 'ana@example.com']);

        $this->actingAs($this->staff())->post('/students', $this->studentData(['year_level' => 99, 'first_name' => '']))
            ->assertSessionHasErrors(['student_number', 'email', 'first_name', 'year_level']);
    }

    public function test_update_allows_keeping_own_unique_values(): void
    {
        $student = Student::factory()->create();

        $this->actingAs($this->staff())
            ->put("/students/{$student->id}", $this->studentData(['student_number' => $student->student_number, 'email' => $student->email]))
            ->assertSessionHasNoErrors();
    }

    public function test_only_admin_can_delete(): void
    {
        $student = Student::factory()->create();

        $this->actingAs($this->staff())->delete("/students/{$student->id}")->assertForbidden();
        $this->assertModelExists($student);

        $this->actingAs($this->admin())->delete("/students/{$student->id}")->assertRedirect('/students');
        $this->assertModelMissing($student);
    }

    public function test_subject_crud(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/subjects', ['code' => 'IT101', 'name' => 'Intro', 'units' => 3])->assertRedirect();
        $subject = Subject::firstOrFail();

        $this->put("/subjects/{$subject->id}", ['code' => 'IT101', 'name' => 'Intro Updated', 'units' => 3])->assertRedirect();
        $this->assertSame('Intro Updated', $subject->fresh()->name);

        $this->post('/subjects', ['code' => 'IT101', 'name' => 'Dup', 'units' => 3])->assertSessionHasErrors('code');

        $this->delete("/subjects/{$subject->id}")->assertRedirect('/subjects');
        $this->assertModelMissing($subject);
    }

    public function test_enrollment_lifecycle_and_duplicate_prevention(): void
    {
        $admin = $this->admin();
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $data = ['student_id' => $student->id, 'subject_id' => $subject->id, 'school_year' => '2025-2026', 'semester' => '1st', 'status' => 'enrolled'];

        $this->actingAs($admin)->post('/enrollments', $data)->assertRedirect(route('enrollments.student', $student));
        $this->assertDatabaseCount('enrollments', 1);

        $this->post('/enrollments', $data)->assertSessionHasErrors('subject_id');

        $enrollment = Enrollment::first();
        $this->put("/enrollments/{$enrollment->id}", array_merge($data, ['grade' => 1.5, 'status' => 'completed']))->assertRedirect(route('enrollments.student', $student));
        $this->assertSame('completed', $enrollment->fresh()->status);

        $this->delete("/enrollments/{$enrollment->id}")->assertRedirect('/enrollments');
        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_enrollment_validation(): void
    {
        $this->actingAs($this->staff())->post('/enrollments', ['school_year' => '2025', 'semester' => 'x', 'status' => 'y', 'grade' => 9])
            ->assertSessionHasErrors(['student_id', 'subject_id', 'school_year', 'semester', 'status', 'grade']);
    }

    public function test_deleting_student_cascades_enrollments(): void
    {
        $enrollment = Enrollment::factory()->create();

        $this->actingAs($this->admin())->delete("/students/{$enrollment->student_id}");

        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_dashboard_renders_statistics(): void
    {
        Enrollment::factory()->count(3)->create();

        $this->actingAs($this->staff())->get('/dashboard')->assertOk()->assertSee('Enrollments');
    }

    public function test_enrollments_list_has_one_row_per_student_and_detail_shows_subjects(): void
    {
        $student = Student::factory()->create(['first_name' => 'Freeda', 'last_name' => 'Rogahn']);
        $codes = ['IT101', 'IT102', 'IT201'];
        foreach ($codes as $code) {
            Enrollment::factory()->create(['student_id' => $student->id, 'subject_id' => Subject::factory()->create(['code' => $code])->id]);
        }
        Enrollment::factory()->create(); // another student

        $response = $this->actingAs($this->staff())->get('/enrollments')->assertOk();
        $this->assertSame(1, substr_count($response->getContent(), 'Freeda Rogahn'));
        $this->assertSame(2, $response->viewData('students')->total());

        $detail = $this->get(route('enrollments.student', $student))->assertOk();
        foreach ($codes as $code) {
            $detail->assertSee($code);
        }
    }

    public function test_enroll_form_can_prefill_student(): void
    {
        $student = Student::factory()->create();

        $this->actingAs($this->staff())->get('/enrollments/create?student_id='.$student->id)->assertOk()
            ->assertSeeInOrder(['value="'.$student->id.'"', 'selected']);
    }

    public function test_removing_last_enrollment_returns_to_list(): void
    {
        $enrollment = Enrollment::factory()->create();

        $this->actingAs($this->admin())->delete("/enrollments/{$enrollment->id}")->assertRedirect('/enrollments');
    }
}
