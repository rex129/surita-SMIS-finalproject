@extends('layouts.app')
@section('title', 'Enrollments')
@section('content')
<x-data-table title="Enrollments" :paginator="$students" :columns="6" :add-route="route('enrollments.create')" add-label="Enroll Student" search="Search student or subject..." empty="No enrollments found.">
    <x-slot:filters>
        <select name="status">
            <option value="">All statuses</option>
            @foreach (\App\Models\Enrollment::STATUSES as $st)<option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>@endforeach
        </select>
    </x-slot:filters>
    <x-slot:head>
        <tr><th>Student</th><th>Course / Year</th><th>Subjects</th><th>Units</th><th>Status</th><th class="r">Actions</th></tr>
    </x-slot:head>
    @foreach ($students as $student)
        <tr>
            <td><strong>{{ $student->full_name }}</strong><div class="muted">{{ $student->student_number }}</div></td>
            <td>{{ $student->course }} &middot; Year {{ $student->year_level }}</td>
            <td>{{ $student->enrollments_count }}</td>
            <td>{{ $student->active_units ?? 0 }}</td>
            <td>
                @if ($student->enrolled_count)<span class="badge enrolled" title="Enrolled">{{ $student->enrolled_count }} enrolled</span>@endif
                @if ($student->completed_count)<span class="badge completed" title="Completed">{{ $student->completed_count }} completed</span>@endif
                @if ($student->dropped_count)<span class="badge dropped" title="Dropped">{{ $student->dropped_count }} dropped</span>@endif
            </td>
            <td class="actions">
                <x-action-button type="view" :href="route('enrollments.student', $student)" title="View enrolled subjects" />
            </td>
        </tr>
    @endforeach
</x-data-table>
@endsection
