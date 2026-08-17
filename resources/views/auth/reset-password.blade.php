@extends('auth.layout.app')
@section('title', 'Reset password')

@section('content')
    <h1>Set a new password</h1>
    <p class="sub">Choose a strong password you don't use anywhere else.</p>

    <form method="POST" action="{{ route('password.store') }}" class="auth-form">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="field">
            <label for="email">Email address</label>
            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email') <span class="fld-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="password">New password</label>
            <div class="pw">
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password" placeholder="New password">
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
        <button type="submit" class="btn btn-primary btn-lg auth-submit" style="margin-top:6px">Reset password</button>
    </form>
@endsection
