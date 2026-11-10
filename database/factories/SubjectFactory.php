<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('???###')),
            'name' => fake()->unique()->words(3, true),
            'units' => fake()->numberBetween(1, 5),
        ];
    }
}
