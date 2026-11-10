@extends('layouts.app')
@section('title', 'Subjects')
@section('content')
<x-data-table title="Subjects" :paginator="$subjects" :columns="5" :add-route="route('subjects.create')" add-label="Add Subject" search="Search subjects..." empty="No subjects found.">
    <x-slot:head>
        <tr><th>Code</th><th>Name</th><th>Units</th><th>Enrolled</th><th class="r">Actions</th></tr>
    </x-slot:head>
    @foreach ($subjects as $subject)
        <tr>
            <td>{{ $subject->code }}</td>
            <td>{{ $subject->name }}</td>
            <td>{{ $subject->units }}</td>
            <td>{{ $subject->enrollments_count }}</td>
            <td class="actions">
                <x-action-button type="view" :href="route('subjects.show', $subject)" title="View subject" />
                <x-action-button type="edit" :href="route('subjects.edit', $subject)" title="Edit subject" />
                @if (auth()->user()->isAdmin())
                <form method="POST" action="{{ route('subjects.destroy', $subject) }}" onsubmit="return confirm('Delete this subject and its enrollments?')">@csrf @method('DELETE')<x-action-button type="delete" title="Delete subject" /></form>
                @endif
            </td>
        </tr>
    @endforeach
</x-data-table>
@endsection
