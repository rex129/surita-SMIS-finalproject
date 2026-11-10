<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authentication / role checks are handled by route middleware
    }

    public function rules(): array
    {
        // Shared by create and update; on update ignore the current record for uniqueness.
        $student = $this->route('student');
        $id = is_object($student) ? $student->id : $student;

        return [
            'student_number' => ['required', 'string', 'max:20', Rule::unique('students', 'student_number')->ignore($id)],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($id)],
            'course' => ['required', 'string', 'max:100'],
            'year_level' => ['required', 'integer', 'between:1,6'],
        ];
    }
}
