<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = new User(['name' => 'Administrator', 'email' => 'admin@sims.test', 'password' => 'password']);
        $admin->role = User::ROLE_ADMIN;
        $admin->save();

        User::create(['name' => 'Staff User', 'email' => 'staff@sims.test', 'password' => 'password']);

        $subjects = collect([
            ['IT101', 'Introduction to Computing', 3],
            ['IT102', 'Programming Fundamentals', 3],
            ['IT201', 'Web Development with Laravel', 3],
            ['IT202', 'Database Management Systems', 3],
            ['IT301', 'Software Engineering', 3],
            ['GE101', 'Understanding the Self', 3],
        ])->map(fn ($s) => Subject::create(['code' => $s[0], 'name' => $s[1], 'units' => $s[2]]));

        foreach (Student::factory()->count(20)->create() as $student) {
            foreach ($subjects->random(3) as $subject) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'school_year' => '2025-2026',
                    'semester' => '1st',
                    'status' => fake()->randomElement(['enrolled', 'enrolled', 'completed', 'dropped']),
                ]);
            }
        }
    }
}
