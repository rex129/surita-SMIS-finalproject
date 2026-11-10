@extends('layouts.app')
@section('title', 'Add Student')
@section('content')
<div class="card"><h1>Add Student</h1>
<form method="POST" action="{{ route('students.store') }}">@csrf @include('students._form', ['student' => null])</form></div>
@endsection
