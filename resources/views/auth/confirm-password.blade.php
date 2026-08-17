@extends('auth.layout.app')
@section('title', 'Confirm password')

@section('content')
    <div class="auth-admin-note"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V8a5 5 0 0 1 10 0v3"/></svg> Secure area</div>
    <h1>Confirm your password</h1>
    <p class="sub">This is a secure area of the application. Please confirm your password before continuing.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
        @csrf
        <div class="field">
            <label for="password">Password</label>
            <div class="pw">
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="••••••••">
                <button type="button" class="toggle-pw" aria-label="Show password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg></button>
            </div>
            @error('password') <span class="fld-err">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-lg auth-submit">Confirm</button>
    </form>
@endsection
