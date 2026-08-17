@extends('admin.layout.app')

@section('content')
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Configure: {{ $gateway->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.payment-gateways.index') }}" class="btn btn-sm btn-default">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.payment-gateways.update', $gateway->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">

                        <div class="form-group">
                            <label>Mode</label>
                            <select name="mode" class="form-control">
                                <option value="test" {{ $gateway->mode === 'test' ? 'selected' : '' }}>Test / Sandbox</option>
                                <option value="live" {{ $gateway->mode === 'live' ? 'selected' : '' }}>Live / Production</option>
                            </select>
                        </div>

                        @forelse($gateway->fields as $key => $field)
                            <div class="form-group">
                                <label>{{ $field['label'] }}</label>
                                <input type="{{ $field['type'] ?? 'text' }}"
                                       name="credentials[{{ $key }}]"
                                       class="form-control"
                                       autocomplete="off"
                                       value="{{ old('credentials.'.$key, $gateway->credential($key)) }}">
                            </div>
                        @empty
                            <div class="alert alert-info mb-0">
                                Is method ke liye koi credentials nahi chahiye.
                            </div>
                        @endforelse

                        <div class="form-group mt-3">
                            <label>Customer Instructions (optional)</label>
                            <textarea name="instructions" class="form-control" rows="2"
                                      placeholder="e.g. Pay with cash upon delivery.">{{ old('instructions', $gateway->instructions) }}</textarea>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>

        </div>
    </section>
</div>
@endsection