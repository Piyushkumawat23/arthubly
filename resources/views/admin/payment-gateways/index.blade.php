@extends('admin.layout.app')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Gateways</h3>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th style="width:60px">#</th>
                                <th>Gateway</th>
                                <th>Mode</th>
                                <th>Status</th>
                                <th class="text-center" style="width:150px">Enable / Disable</th>
                                <th class="text-right" style="width:120px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gateways as $gateway)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $gateway->name }}</strong></td>
                                    <td>
                                        <span class="badge {{ $gateway->mode === 'live' ? 'badge-primary' : 'badge-warning' }}">
                                            {{ ucfirst($gateway->mode) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span id="status-badge-{{ $gateway->id }}"
                                              class="badge {{ $gateway->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $gateway->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox"
       class="custom-control-input gateway-toggle"
       id="toggle-{{ $gateway->id }}"
       data-id="{{ $gateway->id }}"
       data-url="{{ route('admin.payment-gateways.toggle_status', $gateway->id) }}"
       {{ $gateway->is_active ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="toggle-{{ $gateway->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.payment-gateways.edit', $gateway->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-cog"></i> Configure
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>

{{-- AJAX toggle. Agar aapke layout me @stack('scripts') hai to ise wahan move kar dena. --}}
<script>
document.querySelectorAll('.gateway-toggle').forEach(function (el) {
    el.addEventListener('change', function () {
        const id    = this.dataset.id;
        const url   = this.dataset.url;        // route() se aaya — prefix-proof
        const cb    = this;
        const token = "{{ csrf_token() }}";     // meta tag ki dependency hata di

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(r => {
            if (!r.ok) throw new Error('HTTP ' + r.status);   // asli error pakdega
            return r.json();
        })
        .then(data => {
            const badge = document.getElementById('status-badge-' + id);
            badge.textContent = data.is_active ? 'Active' : 'Inactive';
            badge.className   = 'badge ' + (data.is_active ? 'badge-success' : 'badge-secondary');
        })
        .catch(err => {
            cb.checked = !cb.checked;
            alert('Toggle failed: ' + err.message);   // ab generic ki jagah exact error
        });
    });
});
</script>
@endsection