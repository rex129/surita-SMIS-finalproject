@extends('layouts.app')
@section('title', 'Students')
@section('content')
<x-data-table title="Students" :paginator="$students" :columns="7" :add-route="route('students.create')" add-label="Add Student" search="Search students..." empty="No students found.">
    <x-slot:head>
        <tr><th>Number</th><th>Name</th><th>Email</th><th>Course</th><th>Year</th><th>Subjects</th><th class="r">Actions</th></tr>
    </x-slot:head>
    @foreach ($students as $student)
        <tr>
            <td>{{ $student->student_number }}</td>
            <td>{{ $student->full_name }}</td>
            <td>{{ $student->email }}</td>
            <td>{{ $student->course }}</td>
            <td>{{ $student->year_level }}</td>
            <td>{{ $student->enrollments_count }}</td>
            <td class="actions">
                <x-action-button type="view" :href="route('students.show', $student)" title="View student" />
                <x-action-button type="edit" :href="route('students.edit', $student)" title="Edit student" />
                @if (auth()->user()->isAdmin())
                <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Delete this student and their enrollments?')">@csrf @method('DELETE')<x-action-button type="delete" title="Delete student" /></form>
                @endif
            </td>
        </tr>
    @endforeach
</x-data-table>
@endsection
