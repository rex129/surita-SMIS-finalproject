@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<h1>Dashboard</h1>
<div class="grid">
    <div class="card stat-card"><div class="stat-icon"><svg class="i" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/></svg></div><div><div class="stat">{{ $totalStudents }}</div><div class="muted">Students</div></div></div>
    <div class="card stat-card"><div class="stat-icon green"><svg class="i" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5z"/></svg></div><div><div class="stat">{{ $totalSubjects }}</div><div class="muted">Subjects</div></div></div>
    <div class="card stat-card"><div class="stat-icon amber"><svg class="i" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div><div><div class="stat">{{ $totalEnrollments }}</div><div class="muted">Enrollments</div></div></div>
    <div class="card stat-card"><div class="stat-icon pink"><svg class="i" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></div><div><div class="stat">{{ $totalUsers }}</div><div class="muted">System users</div></div></div>
</div>
<div class="grid">
    <div class="card"><h2>Students by Year Level</h2>
        <table><tbody>@forelse ($studentsByYear as $year => $total)<tr><td>Year {{ $year }}</td><td style="text-align:right"><strong>{{ $total }}</strong></td></tr>@empty<tr><td class="muted">No data</td></tr>@endforelse</tbody></table></div>
    <div class="card"><h2>Students by Course</h2>
        <table><tbody>@forelse ($studentsByCourse as $course => $total)<tr><td>{{ $course }}</td><td style="text-align:right"><strong>{{ $total }}</strong></td></tr>@empty<tr><td class="muted">No data</td></tr>@endforelse</tbody></table></div>
    <div class="card"><h2>Enrollments by Status</h2>
        <table><tbody>@forelse ($enrollmentsByStatus as $status => $total)<tr><td><span class="badge {{ $status }}">{{ $status }}</span></td><td style="text-align:right"><strong>{{ $total }}</strong></td></tr>@empty<tr><td class="muted">No data</td></tr>@endforelse</tbody></table></div>
</div>
<div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(320px,1fr))">
    <div class="card"><h2>Most Enrolled Subjects</h2>
        <table><tbody>@forelse ($popularSubjects as $s)<tr><td>{{ $s->code }} - {{ $s->name }}</td><td style="text-align:right"><strong>{{ $s->enrollments_count }}</strong></td></tr>@empty<tr><td class="muted">No data</td></tr>@endforelse</tbody></table></div>
    <div class="card"><h2>Recent Enrollments</h2>
        <table><tbody>@forelse ($recentEnrollments as $e)<tr><td>{{ $e->student->full_name }}</td><td style="text-align:right"><span class="badge">{{ $e->subject->code }}</span></td></tr>@empty<tr><td class="muted">No data</td></tr>@endforelse</tbody></table></div>
</div>
@endsection
