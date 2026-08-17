@extends('auth.layout.app')
@section('title', 'Verify email')

@section('content')
    <div class="auth-admin-note"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z" fill="none"/><path d="m4 6 8 6 8-6"/><rect x="4" y="5" width="16" height="14" rx="2"/></svg> One more step</div>
    <h1>Verify your email</h1>
    <p class="sub">Thanks for signing up! Please verify your email address by clicking the link we just sent you. Didn't get it? We'll gladly send another.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-status"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg><span>A new verification link has been sent to your email address.</span></div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="auth-form">
        @csrf
        <button type="submit" class="btn btn-primary btn-lg auth-submit">Resend verification email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-ghost" style="width:100%;justify-content:center;height:48px;margin-top:12px;color:var(--madder);border-color:var(--line-2)">Log out</button>
    </form>
@endsection
