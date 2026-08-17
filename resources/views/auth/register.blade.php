@extends('auth.layout.app')
@section('title', 'Create account')

@section('content')
    <h1>Create your account</h1>
    <p class="sub">Join Arthubly to discover one-of-a-kind pieces from independent makers.</p>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf
        <div class="field">
            <label for="name">Full name</label>
            <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Your name">
            @error('name') <span class="fld-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="email">Email address</label>
            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com">
            @error('email') <span class="fld-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="password">Password</label>
            <div class="pw">
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password" placeholder="Create a password">
                <button type="button" class="toggle-pw" aria-label="Show password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg></button>
            </div>
            @error('password') <span class="fld-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="password_confirmation">Confirm password</label>
            <div class="pw">
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password" placeholder="Repeat password">
                <button type="button" class="toggle-pw" aria-label="Show password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg></button>
            </div>
            @error('password_confirmation') <span class="fld-err">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-lg auth-submit" style="margin-top:6px">Create account</button>
    </form>

    <p class="auth-foot">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
@endsection
