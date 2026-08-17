@extends('admin.layout.app')

@section('content')
    <div class="container-fluid mb-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-dark shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user-edit"></i> Update Profile Information</h3>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('patch')

                        <div class="card-body" style="background-color: #f4f6f9;">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                                </div>
                            @endif

                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Account Role</label>
                                <input type="text" class="form-control text-capitalize" value="{{ $user->role }}"
                                    readonly style="background-color: #e9ecef; cursor: not-allowed;">
                                <small class="text-muted">Security rules ke mutabik aap apna role khud change nahi kar
                                    sakte.</small>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top text-right">
                            <button type="submit" class="btn btn-dark px-4"><i class="fas fa-save mr-1"></i> Save
                                Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-danger card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title text-danger"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h3>
                    </div>
                    <div class="card-body bg-white">
                        <h5>Delete Account</h5>
                        <p class="text-muted small">
                            Ek baar account delete hone par aapka saara data, products aur settings hamesha ke liye delete
                            ho jayengi. Kripya dhyan se aage badhein.
                        </p>

                        @if ($errors->userDeletion->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->userDeletion->all() as $error)
                                    <p class="mb-0"><i class="fas fa-times-circle"></i> {{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash-alt mr-1"></i> Delete My Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAccountModalLabel"><i class="fas fa-skull-crossbones mr-2"></i> Are
                        you absolutely sure?</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.profile.destroy') }}" method="POST">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <p class="font-weight-bold text-dark">Account delete karne ke liye apna current password enter
                            karein:</p>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="Current Password"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
