<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Account') — Arthubly</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,500&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/frontend/css/arthubly.css') }}">
</head>
<body>
<div class="auth-wrap">
    {{-- ===== Brand panel ===== --}}
    <aside class="auth-brand">
        <div class="grain-lite"></div>
        <a class="ab-logo" href="{{ url('/') }}">
            <span class="mark"><svg viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:44px;height:44px"><rect width="44" height="44" rx="12" fill="#20263A"/><path d="M12 34c1.9-6.6 5-13.9 9.7-22.4 .8-1.4 2.9-1 3 .6 0 .6-.3 1.2-.7 1.9C19.6 21.7 16.9 28 15.2 34.4c-.5 1.8-3.7 1.4-3.2-.4z" fill="#E5C888"/><path d="M21.2 12.6c4 6.6 7.2 13.4 9.5 21 .5 1.8-2.4 2.6-3 .9-2.2-6.2-5-11.9-8.4-17.6-1-1.7 1-3 1.9-1.3z" fill="#C9973A"/><path d="M17 27c3.8-1.1 7.4-1.1 11.2 0 1.5.4 1.1 2.7-.5 2.5-3.4-.4-6.8-.4-10.2 0-1.6.2-2-2.1-.5-2.5z" fill="#B14237"/><circle cx="32.5" cy="12.6" r="1.9" fill="#5E7355"/></svg></span>
            <span class="name">Art<b>hubly</b></span>
        </a>
        <div class="ab-mid">
            <span class="eyebrow">The maker's marketplace</span>
            <h2>Handmade,<br>with a <em>name</em> behind it</h2>
            <p>Join a community that values craft over mass production — where every piece is signed by the artisan who made it.</p>
        </div>
        <div class="ab-trust">
            <div class="abt"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z"/><path d="m9 12 2 2 4-4"/></svg> Every artisan verified</div>
            <div class="abt"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V8a5 5 0 0 1 10 0v3"/></svg> Secure, encrypted checkout</div>
            <div class="abt"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.8 6.6a5 5 0 0 0-7.1 0L12 8.3l-1.7-1.7a5 5 0 1 0-7.1 7.1L12 22l8.8-8.3a5 5 0 0 0 0-7.1z"/></svg> Fair prices, set by makers</div>
        </div>
    </aside>

    {{-- ===== Form panel ===== --}}
    <main class="auth-panel">
        <div class="auth-card">
            <div class="auth-mobilebar">
                <a class="ab-logo" href="{{ url('/') }}" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;color:var(--ink)">
                    <span class="mark"><svg viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:38px;height:38px"><rect width="44" height="44" rx="12" fill="#20263A"/><path d="M12 34c1.9-6.6 5-13.9 9.7-22.4 .8-1.4 2.9-1 3 .6 0 .6-.3 1.2-.7 1.9C19.6 21.7 16.9 28 15.2 34.4c-.5 1.8-3.7 1.4-3.2-.4z" fill="#E5C888"/><path d="M21.2 12.6c4 6.6 7.2 13.4 9.5 21 .5 1.8-2.4 2.6-3 .9-2.2-6.2-5-11.9-8.4-17.6-1-1.7 1-3 1.9-1.3z" fill="#C9973A"/><circle cx="32.5" cy="12.6" r="1.9" fill="#5E7355"/></svg></span>
                    <span class="name">Art<b>hubly</b></span>
                </a>
            </div>
            <a class="auth-back" href="{{ url('/') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg> Back to store</a>
            @yield('content')
        </div>
    </main>
</div>

<script>
    // password show/hide
    document.addEventListener('click', function(e){
        var t = e.target.closest('.toggle-pw'); if(!t) return;
        var inp = t.parentElement.querySelector('input');
        if(!inp) return;
        var show = inp.type === 'password';
        inp.type = show ? 'text' : 'password';
        t.innerHTML = show
            ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/><path d="m3 3 18 18"/></svg>'
            : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>';
    });
</script>
</body>
</html>
