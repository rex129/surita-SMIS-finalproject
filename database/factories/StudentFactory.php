<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_number' => fake()->unique()->numerify('2026-####'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'course' => fake()->randomElement(['BSIT', 'BSCS', 'BSIS', 'BSCpE']),
            'year_level' => fake()->numberBetween(1, 4),
        ];
    }
}
