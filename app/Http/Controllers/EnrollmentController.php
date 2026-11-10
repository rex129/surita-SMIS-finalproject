<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * One row per student who has enrollments (not one row per subject).
     */
    public function index(Request $request)
    {
        $term = $request->query('q');
        $status = $request->query('status');

        $students = Student::query()
            ->whereHas('enrollments', fn ($q) => $q->when($status, fn ($w) => $w->where('status', $status)))
            ->when($term, fn ($q) => $q->where(fn ($w) => $w->search($term)
                ->orWhereHas('subjects', fn ($s) => $s->where('code', 'like', "%{$term}%")->orWhere('name', 'like', "%{$term}%"))))
            ->withCount([
                'enrollments',
                'enrollments as enrolled_count' => fn ($q) => $q->where('status', 'enrolled'),
                'enrollments as completed_count' => fn ($q) => $q->where('status', 'completed'),
                'enrollments as dropped_count' => fn ($q) => $q->where('status', 'dropped'),
            ])
            ->withSum(['subjects as active_units' => fn ($q) => $q->where('enrollments.status', '!=', 'dropped')], 'units')
            ->orderBy('last_name')->orderBy('first_name')
            ->paginate(7)->withQueryString();

        return view('enrollments.index', compact('students'));
    }

    /**
     * A student's enrolled subjects and enrollment details.
     */
    public function student(Student $student)
    {
        $student->load(['enrollments' => fn ($q) => $q->orderByDesc('school_year')->orderBy('semester'), 'enrollments.subject']);

        $active = $student->enrollments->where('status', '!=', 'dropped');
        $totalUnits = $active->sum(fn ($e) => $e->subject->units);

        return view('enrollments.student', compact('student', 'totalUnits'));
    }

    public function create(Request $request)
    {
        $enrollment = new Enrollment(['status' => 'enrolled', 'student_id' => $request->query('student_id')]);

        return view('enrollments.create', $this->formData($enrollment));
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $enrollment = Enrollment::create($request->validated());

        return redirect()->route('enrollments.student', $enrollment->student_id)->with('success', 'Student enrolled.');
    }

    public function edit(Enrollment $enrollment)
    {
        return view('enrollments.edit', $this->formData($enrollment));
    }

    public function update(UpdateEnrollmentRequest $request, Enrollment $enrollment)
    {
        $enrollment->update($request->validated());

        return redirect()->route('enrollments.student', $enrollment->student_id)->with('success', 'Enrollment updated.');
    }

    public function destroy(Enrollment $enrollment)
    {
        $studentId = $enrollment->student_id;
        $enrollment->delete();

        // Go back to the student's page, or the list when they have no enrollments left.
        $route = Enrollment::where('student_id', $studentId)->exists()
            ? redirect()->route('enrollments.student', $studentId)
            : redirect()->route('enrollments.index');

        return $route->with('success', 'Enrollment removed.');
    }

    private function formData(Enrollment $enrollment): array
    {
        return [
            'enrollment' => $enrollment,
            'students' => Student::orderBy('last_name')->get(),
            'subjects' => Subject::orderBy('code')->get(),
        ];
    }
}
