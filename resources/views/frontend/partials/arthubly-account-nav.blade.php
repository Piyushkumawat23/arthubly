{{-- Usage: @include('frontend.partials.arthubly-account-nav', ['active' => 'orders']) --}}
@php $active = $active ?? ''; @endphp
<aside class="acct-side">
    <div class="who">
        <span class="av">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
        <div>
            <b>{{ auth()->user()->name ?? 'My account' }}</b>
            <small>{{ auth()->user()->email ?? '' }}</small>
        </div>
    </div>
    <nav>
        <a href="{{ route('dashboard') }}" class="{{ $active === 'dashboard' ? 'on' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
            Dashboard
        </a>
        <a href="{{ route('customer.orders') }}" class="{{ $active === 'orders' ? 'on' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
            My Orders
        </a>
        <a href="{{ route('customer.returns') }}" class="{{ $active === 'returns' ? 'on' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 14 4 9l5-5"/><path d="M4 9h11a5 5 0 0 1 0 10h-1"/></svg>
            My Returns
        </a>
        <a href="{{ route('wishlist.index') }}" class="{{ $active === 'wishlist' ? 'on' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 20s-7-4.3-7-9.3A3.7 3.7 0 0 1 12 8a3.7 3.7 0 0 1 7 2.7C19 15.7 12 20 12 20z"/></svg>
            Wishlist
        </a>
        <a href="{{ route('profile.edit') }}" class="{{ $active === 'profile' ? 'on' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
            Profile
        </a>
        <form method="POST" action="{{ route('logout') }}" style="border-top:1px solid var(--line);margin-top:6px">
            @csrf
            <button type="submit" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:12px;padding:11px 14px;font-size:14px;font-weight:500;color:var(--madder);font-family:var(--font-b)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5M21 12H9"/></svg>
                Logout
            </button>
        </form>
    </nav>
</aside>
