<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_number' => $this->student_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'course' => $this->course,
            'year_level' => $this->year_level,
            'enrollments' => $this->whenLoaded('enrollments', fn () => $this->enrollments->map(fn ($e) => [
                'id' => $e->id,
                'subject_code' => $e->subject?->code,
                'subject_name' => $e->subject?->name,
                'school_year' => $e->school_year,
                'semester' => $e->semester,
                'status' => $e->status,
                'grade' => $e->grade,
            ])),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
