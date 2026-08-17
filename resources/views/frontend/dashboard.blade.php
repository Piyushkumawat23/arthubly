@extends('frontend.layout.arthubly')

@section('title', 'Dashboard — Arthubly')

@section('content')
@php
    $ordersCount = 0;
    try { $ordersCount = \App\Models\Order::where('user_id', auth()->id())->count(); } catch (\Throwable $e) { $ordersCount = 0; }
    $cartCount = session('cart') ? count(session('cart')) : 0;
@endphp
<section class="page active">
    <div class="wrap">
        <div class="crumbs"><a href="{{ url('/') }}">Home</a><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><span class="cur">Dashboard</span></div>
        <div class="acct-layout">
            @include('frontend.partials.arthubly-account-nav', ['active' => 'dashboard'])
            <div class="acct-main">
                <div class="ac-head"><h1>Welcome back, {{ \Illuminate\Support\Str::words(auth()->user()->name ?? 'there', 1, '') }}</h1><p>Here's what's happening with your account.</p></div>

                <div class="acct-cards">
                    <div class="ac-stat">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
                        <b>{{ $ordersCount }}</b><small>Total orders</small>
                    </div>
                    <div class="ac-stat">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.6 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/></svg>
                        <b>{{ $cartCount }}</b><small>Items in bag</small>
                    </div>
                    <div class="ac-stat">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 20s-7-4.3-7-9.3A3.7 3.7 0 0 1 12 8a3.7 3.7 0 0 1 7 2.7C19 15.7 12 20 12 20z"/></svg>
                        <b>Saved</b><small>Wishlist</small>
                    </div>
                    <div class="ac-stat">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                        <b style="color:var(--celadon)">Verified</b><small>Account status</small>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-head"><h4>Account</h4></div>
                    <div class="panel-body">
                        <p class="kv"><b>Name:</b> {{ auth()->user()->name }}</p>
                        <p class="kv" style="margin-bottom:16px"><b>Email:</b> {{ auth()->user()->email }}</p>
                        <div style="display:flex;gap:10px;flex-wrap:wrap">
                            <a href="{{ route('customer.orders') }}" class="btn btn-primary">View orders</a>
                            <a href="{{ route('cart.index') }}" class="btn btn-ghost">Go to bag</a>
                            <a href="{{ route('profile.edit') }}" class="btn btn-ghost">Edit profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
