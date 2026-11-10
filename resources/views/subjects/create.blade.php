@extends('layouts.app')
@section('title', 'Add Subject')
@section('content')
<div class="card"><h1>Add Subject</h1>
<form method="POST" action="{{ route('subjects.store') }}">@csrf @include('subjects._form', ['subject' => null])</form></div>
@endsection
