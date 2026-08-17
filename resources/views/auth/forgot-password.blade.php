@extends('auth.layout.app')
@section('title', 'Forgot password')

@section('content')
    <h1>Forgot password?</h1>
    <p class="sub">No problem. Enter your email and we'll send you a link to reset it.</p>

    @if (session('status'))
        <div class="auth-status"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg><span>{{ session('status') }}</span></div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf
        <div class="field">
            <label for="email">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus placeholder="you@example.com">
            @error('email') <span class="fld-err">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-lg auth-submit">Send reset link</button>
    </form>

    <p class="auth-foot"><a href="{{ route('login') }}">&larr; Back to sign in</a></p>
@endsection
