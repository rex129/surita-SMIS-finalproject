@extends('layouts.app')
@section('title', 'Update Enrollment')
@section('content')
<div class="card"><h1>Update Enrollment</h1>
<form method="POST" action="{{ route('enrollments.update', $enrollment) }}">@csrf @method('PUT') @include('enrollments._form')</form></div>
@endsection
