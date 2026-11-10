<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::search($request->query('q'))
            ->when($request->query('course'), fn ($q, $c) => $q->where('course', $c))
            ->orderBy('last_name')
            ->paginate(min((int) $request->query('per_page', 15), 100))
            ->withQueryString();

        return StudentResource::collection($students);
    }

    public function show(Student $student): StudentResource
    {
        return new StudentResource($student->load('enrollments.subject'));
    }

    public function store(StoreStudentRequest $request): JsonResponse
    {
        $student = Student::create($request->validated());

        return (new StudentResource($student))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateStudentRequest $request, Student $student): StudentResource
    {
        $student->update($request->validated());

        return new StudentResource($student);
    }

    public function destroy(Student $student): Response
    {
        $student->delete();

        return response()->noContent();
    }
}
