@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="card auth">
    <div class="brand"><div class="logo"><svg class="i" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/></svg></div><div>SIMS<small>Student Information System</small></div></div>
    <h1>Create your account</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label for="name">Name</label>
        <input id="name" name="name" value="{{ old('name') }}" class="{{ $errors->has('name') ? 'invalid' : '' }}" required autofocus>
        @error('name')<div class="error">{{ $message }}</div>@enderror
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" class="{{ $errors->has('email') ? 'invalid' : '' }}" required>
        @error('email')<div class="error">{{ $message }}</div>@enderror
        <label for="password">Password (min 8 characters)</label>
        <input id="password" type="password" name="password" required>
        @error('password')<div class="error">{{ $message }}</div>@enderror
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>
        <p style="margin-bottom:0"><button class="btn" style="width:100%">Register</button></p>
        <div class="hint">Already have an account? <a href="{{ route('login') }}">Login</a></div>
    </form>
</div>
@endsection
