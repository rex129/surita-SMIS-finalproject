<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'totalStudents' => Student::count(),
            'totalSubjects' => Subject::count(),
            'totalEnrollments' => Enrollment::count(),
            'totalUsers' => User::count(),
            'studentsByYear' => Student::selectRaw('year_level, count(*) as total')->groupBy('year_level')->orderBy('year_level')->pluck('total', 'year_level'),
            'studentsByCourse' => Student::selectRaw('course, count(*) as total')->groupBy('course')->orderByDesc('total')->pluck('total', 'course'),
            'enrollmentsByStatus' => Enrollment::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'popularSubjects' => Subject::withCount('enrollments')->orderByDesc('enrollments_count')->limit(5)->get(),
            'recentEnrollments' => Enrollment::with(['student', 'subject'])->latest()->limit(5)->get(),
        ]);
    }
}
