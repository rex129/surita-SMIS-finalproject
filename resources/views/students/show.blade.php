@extends('layouts.app')
@section('title', $student->full_name)
@section('content')
<div class="card">
    <h1>{{ $student->full_name }}</h1>
    <p>No. {{ $student->student_number }} &middot; {{ $student->email }} &middot; {{ $student->course }}, Year {{ $student->year_level }}</p>
    <a class="btn" href="{{ route('students.edit', $student) }}">Edit</a> <a class="btn secondary" href="{{ route('students.index') }}">Back</a>
</div>
<div class="card"><h2>Enrollments</h2>
    <table><thead><tr><th>Subject</th><th>Term</th><th>Status</th><th>Grade</th></tr></thead><tbody>
    @forelse ($student->enrollments as $e)
        <tr><td>{{ $e->subject->code }} - {{ $e->subject->name }}</td><td>{{ $e->school_year }} {{ $e->semester }}</td><td><span class="badge {{ $e->status }}">{{ $e->status }}</span></td><td>{{ $e->grade ?? '-' }}</td></tr>
    @empty <tr><td colspan="4" class="muted">Not enrolled in any subject.</td></tr> @endforelse
    </tbody></table>
</div>
@endsection
