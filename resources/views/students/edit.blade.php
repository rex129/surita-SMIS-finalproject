@extends('layouts.app')
@section('title', 'Edit Student')
@section('content')
<div class="card"><h1>Edit Student</h1>
<form method="POST" action="{{ route('students.update', $student) }}">@csrf @method('PUT') @include('students._form')</form></div>
@endsection
