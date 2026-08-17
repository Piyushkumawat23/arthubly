@extends('frontend.layout.arthubly')

@section('title', 'Profile — Arthubly')

@section('content')
<section class="page active">
    <div class="wrap">
        <div class="crumbs"><a href="{{ url('/') }}">Home</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><span class="cur">Profile</span></div>
        <div class="acct-layout">
            @include('frontend.partials.arthubly-account-nav', ['active' => 'profile'])
            <div class="acct-main">
                <div class="ac-head"><h1>Profile settings</h1><p>Update your account details.</p></div>
                <div class="panel">
                    <div class="panel-body">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')
                            <div class="profile-form">
                                <div class="field"><label>Full name</label><input type="text" name="name" value="{{ $user->name }}" required></div>
                                <div class="field"><label>Email address</label><input type="email" name="email" value="{{ $user->email }}" required></div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg" style="margin-top:20px">Save changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
