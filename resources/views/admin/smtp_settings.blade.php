@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    {{-- Standard Page Header and Breadcrumb --}}
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0">SMTP Settings</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">SMTP Settings</li>
            </ol>
        </div>
    </div>

    <div class="row">
        {{-- Flash Message Section --}}
        @if (session('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif

        <!-- Left Column: SMTP Settings Form (col-md-7 to fill space left by col-md-5) -->
        <div class="col-md-7">
            <div class="card card-secondary card-outline mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0 card-title">SMTP Configuration</h5>
                </div>
                
                <form action="{{ route('admin.smtp.update') }}" method="POST">
                    @csrf
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                {{-- Mailer Field --}}
                                <div class="form-group mb-3">
                                    <label for="mailer" class="form-label fw-bold">Mailer</label>
                                    <input type="text" name="mailer" id="mailer" value="{{ old('mailer', $smtp->mailer ?? '') }}"
                                        class="form-control" required>
                                </div>
                                {{-- Host Field --}}
                                <div class="form-group mb-3">
                                    <label for="host" class="form-label fw-bold">Host</label>
                                    <input type="text" name="host" id="host" value="{{ old('host', $smtp->host ?? '') }}"
                                        class="form-control" required>
                                </div>
                                {{-- Port Field --}}
                                <div class="form-group mb-3">
                                    <label for="port" class="form-label fw-bold">Port</label>
                                    <input type="number" name="port" id="port" value="{{ old('port', $smtp->port ?? '') }}"
                                        class="form-control" required>
                                </div>
                                {{-- Username Field --}}
                                <div class="form-group mb-3">
                                    <label for="username" class="form-label fw-bold">Username</label>
                                    <input type="text" name="username" id="username" value="{{ old('username', $smtp->username ?? '') }}"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{-- App Password Field --}}
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label fw-bold">App Password</label>
                                    <input type="text" name="password" id="password" value="{{ old('password', $smtp->password ?? '') }}"
                                        class="form-control" required>
                                </div>
                                {{-- Encryption Field --}}
                                <div class="form-group mb-3">
                                    <label for="encryption" class="form-label fw-bold">Encryption</label>
                                    <select name="encryption" id="encryption" class="form-control">
                                        <option value="tls" {{ ($smtp->encryption ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ ($smtp->encryption ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    </select>
                                </div>
                                {{-- From Address Field --}}
                                <div class="form-group mb-3">
                                    <label for="from_address" class="form-label fw-bold">From Address</label>
                                    <input type="email" name="from_address" id="from_address"
                                        value="{{ old('from_address', $smtp->from_address ?? '') }}"
                                        class="form-control" required>
                                </div>
                                {{-- From Name Field --}}
                                <div class="form-group mb-3">
                                    <label for="from_name" class="form-label fw-bold">From Name</label>
                                    <input type="text" name="from_name" id="from_name" value="{{ old('from_name', $smtp->from_name ?? '') }}"
                                        class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-end">Save Configuration</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Test SMTP Configuration Form and Instructions -->
        <div class="col-md-5">
            <!-- Test SMTP Configuration Form (Top) -->
            <div class="card card-outline mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 card-title">Test SMTP Configuration</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.smtp.test') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="test_email" class="form-label fw-bold">Test Email Address</label>
                            <input type="email" name="test_email" id="test_email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Send Test Email</button>
                    </form>
                </div>
            </div>

            <!-- Instructions for SMTP Configuration (Bottom) -->
            <div class="card card-outline mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 card-title text-danger">Instructions</h5>
                </div>
                <div class="card-body p-3">
                    <p class="text-danger fw-bold">⚠ Please be careful when configuring SMTP. Incorrect settings may cause issues.</p>

                    <h6 class="text-secondary fw-bold">General Tips</h6>
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item">If SMTP fails, try setting **Mailer** to `sendmail`.</li>
                        <li class="list-group-item">Use Port **587** for TLS and **465** for SSL.</li>
                        <li class="list-group-item">If using Gmail, you must use an **App Password**, not your main account password.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection