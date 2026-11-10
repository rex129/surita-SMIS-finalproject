@extends('layouts.app')
@section('title', $subject->code)
@section('content')
<div class="card">
    <h1>{{ $subject->code }} - {{ $subject->name }}</h1>
    <p>{{ $subject->units }} units</p>
    <a class="btn" href="{{ route('subjects.edit', $subject) }}">Edit</a> <a class="btn secondary" href="{{ route('subjects.index') }}">Back</a>
</div>
<div class="card"><h2>Enrolled Students</h2>
    <table><thead><tr><th>Student</th><th>Term</th><th>Status</th><th>Grade</th></tr></thead><tbody>
    @forelse ($subject->enrollments as $e)
        <tr><td>{{ $e->student->full_name }}</td><td>{{ $e->school_year }} {{ $e->semester }}</td><td><span class="badge {{ $e->status }}">{{ $e->status }}</span></td><td>{{ $e->grade ?? '-' }}</td></tr>
    @empty <tr><td colspan="4" class="muted">No students enrolled.</td></tr> @endforelse
    </tbody></table>
</div>
@endsection
