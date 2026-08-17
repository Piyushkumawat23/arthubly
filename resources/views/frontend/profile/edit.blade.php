@extends('frontend.layout.arthubly')

@section('title', 'Edit Profile — Arthubly')

@section('content')
<section class="page active">
    <div class="wrap">
        <div class="crumbs">
            <a href="{{ url('/') }}">Home</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            <span class="cur">Edit Profile</span>
        </div>

        <div class="acct-layout">
            @include('frontend.partials.arthubly-account-nav', ['active' => 'edit'])

            <div class="acct-main">
                <div class="ac-head">
                    <h1>Edit Profile</h1>
                    <p>Update your name and email address below.</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success" style="margin-bottom:20px">{{ session('success') }}</div>
                @endif

                <div class="panel">
                    <div class="panel-head"><h4>Personal Information</h4></div>
                    <div class="panel-body">
                        <form method="post" action="{{ route('profile.update') }}" class="auth-form">
                            @csrf
                            @method('patch')

                            <div class="field">
                                <label for="edit-name">Full name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="edit-name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <div class="field">
                                <label for="edit-email">Email address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="edit-email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg">Save changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection