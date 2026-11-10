@extends('layouts.app')
@section('title', 'Edit Subject')
@section('content')
<div class="card"><h1>Edit Subject</h1>
<form method="POST" action="{{ route('subjects.update', $subject) }}">@csrf @method('PUT') @include('subjects._form')</form></div>
@endsection
