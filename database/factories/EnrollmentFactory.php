<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'subject_id' => Subject::factory(),
            'school_year' => '2025-2026',
            'semester' => fake()->randomElement(['1st', '2nd']),
            'status' => 'enrolled',
            'grade' => null,
        ];
    }
}
