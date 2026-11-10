@extends('layouts.app')
@section('title', 'Enroll Student')
@section('content')
<div class="card"><h1>Enroll Student</h1>
<form method="POST" action="{{ route('enrollments.store') }}">@csrf @include('enrollments._form')</form></div>
@endsection
