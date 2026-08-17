@extends('auth.layout.app')
@section('title', 'Sign in')

@section('content')
    <h1>Welcome back</h1>
    <p class="sub">Sign in to track orders, save favourites, and check out faster.</p>

    @if (session('status'))
        <div class="auth-status"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg><span>{{ session('status') }}</span></div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf
        <div class="field">
            <label for="email">Email address</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
            @error('email') <span class="fld-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="password">Password</label>
            <div class="pw">
                <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                <button type="button" class="toggle-pw" aria-label="Show password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg></button>
            </div>
            @error('password') <span class="fld-err">{{ $message }}</span> @enderror
        </div>
        <div class="auth-row">
            <label class="auth-check"><input type="checkbox" name="remember" id="remember_me"> Remember me</label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
            @endif
        </div>
        <button type="submit" class="btn btn-primary btn-lg auth-submit">Sign in</button>
    </form>

    <p class="auth-foot">New to Arthubly? <a href="{{ route('register') }}">Create an account</a></p>
@endsection
