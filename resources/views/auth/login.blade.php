@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="card auth">
    <div class="brand"><div class="logo"><svg class="i" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/></svg></div><div>SIMS<small>Student Information System</small></div></div>
    <h1>Welcome back</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" class="{{ $errors->has('email') ? 'invalid' : '' }}" required autofocus>
        @error('email')<div class="error">{{ $message }}</div>@enderror
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
        @error('password')<div class="error">{{ $message }}</div>@enderror
        <div class="row">
            <label style="margin:0;font-weight:400"><input type="checkbox" name="remember"> Remember me</label>
            <a href="{{ route('register') }}">Create account</a>
        </div>
        <p style="margin-bottom:0"><button class="btn" style="width:100%">Login</button></p>
    </form>
</div>
@endsection
