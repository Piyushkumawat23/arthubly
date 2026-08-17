@extends('auth.layout.app')
@section('title', 'Seller sign in')

@section('content')
    <div class="auth-admin-note"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z"/><path d="m9 12 2 2 4-4"/></svg> Seller access</div>
    <h1>Seller sign in</h1>
    <p class="sub">Sellers only. Use your seller credentials to manage your shop.</p>

    <form method="POST" action="{{ url('seller/login') }}" class="auth-form">
        @csrf
        <div class="field">
            <label for="email">Email address</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="seller@example.com">
            @error('email') <span class="fld-err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="password">Password</label>
            <div class="pw">
                <input id="password" type="password" name="password" class="form-control" required placeholder="••••••••">
                <button type="button" class="toggle-pw" aria-label="Show password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg></button>
            </div>
            @error('password') <span class="fld-err">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-lg auth-submit">Sign in as seller</button>
    </form>

    <p class="auth-foot"><a href="{{ route('login') }}">&larr; Customer sign in</a></p>
@endsection