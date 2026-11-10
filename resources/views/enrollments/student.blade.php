@extends('layouts.app')
@section('title', $student->full_name . ' - Enrollments')
@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <h1>{{ $student->full_name }}</h1>
            <div class="muted">{{ $student->student_number }} &middot; {{ $student->email }} &middot; {{ $student->course }}, Year {{ $student->year_level }}</div>
        </div>
        <div class="toolbar-right" style="display:flex;gap:.6rem;flex-wrap:wrap">
            <a class="btn secondary" href="{{ route('enrollments.index') }}">&lsaquo; Back</a>
            <a class="btn secondary" href="{{ route('students.show', $student) }}">Student profile</a>
            <a class="btn" href="{{ route('enrollments.create', ['student_id' => $student->id]) }}">+ Enroll in a subject</a>
        </div>
    </div>
    <div class="grid" style="margin-bottom:0">
        <div><div class="muted">Subjects</div><div class="stat">{{ $student->enrollments->count() }}</div></div>
        <div><div class="muted">Units (excluding dropped)</div><div class="stat">{{ $totalUnits }}</div></div>
        <div><div class="muted">Enrolled / Completed / Dropped</div>
            <div class="stat" style="font-size:1.4rem">
                {{ $student->enrollments->where('status', 'enrolled')->count() }} /
                {{ $student->enrollments->where('status', 'completed')->count() }} /
                {{ $student->enrollments->where('status', 'dropped')->count() }}
            </div></div>
    </div>
</div>

<div class="card">
    <h2>Enrolled subjects</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Code</th><th>Subject</th><th>Units</th><th>Term</th><th>Status</th><th>Grade</th><th>Enrolled on</th><th class="r">Actions</th></tr></thead>
            <tbody>
            @forelse ($student->enrollments as $e)
                <tr>
                    <td>{{ $e->subject->code }}</td>
                    <td>{{ $e->subject->name }}</td>
                    <td>{{ $e->subject->units }}</td>
                    <td>{{ $e->school_year }} &middot; {{ $e->semester }}</td>
                    <td><span class="badge {{ $e->status }}">{{ $e->status }}</span></td>
                    <td>{{ $e->grade ?? '-' }}</td>
                    <td>{{ $e->created_at?->format('M d, Y') }}</td>
                    <td class="actions">
                        <x-action-button type="edit" :href="route('enrollments.edit', $e)" title="Update enrollment" />
                        @if (auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('enrollments.destroy', $e) }}" onsubmit="return confirm('Remove this enrollment?')">@csrf @method('DELETE')<x-action-button type="delete" title="Remove enrollment" /></form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="empty">This student has no enrollments yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
