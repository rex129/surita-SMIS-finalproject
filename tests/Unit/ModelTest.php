<?php

namespace Tests\Unit;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    public function test_student_full_name_attribute(): void
    {
        $student = new Student(['first_name' => 'Ana', 'last_name' => 'Cruz']);

        $this->assertSame('Ana Cruz', $student->full_name);
    }

    public function test_is_admin_checks_role(): void
    {
        $user = new User();
        $user->role = User::ROLE_ADMIN;
        $this->assertTrue($user->isAdmin());

        $user->role = User::ROLE_STAFF;
        $this->assertFalse($user->isAdmin());
    }

    public function test_role_is_not_mass_assignable(): void
    {
        $user = new User(['name' => 'X', 'email' => 'x@example.com', 'role' => 'admin']);

        $this->assertNull($user->role);
    }

    public function test_enrollment_allowed_values(): void
    {
        $this->assertSame(['enrolled', 'completed', 'dropped'], Enrollment::STATUSES);
        $this->assertContains('1st', Enrollment::SEMESTERS);
    }
}
