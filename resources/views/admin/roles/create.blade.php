@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    {{-- Page Header and Breadcrumb (Structured like Staffs page) --}}
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0">Roles</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                {{-- Assuming a route for roles index exists --}}
                <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                {{-- 'active' class used for the current page --}}
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>

    {{-- Create Role Form Card --}}
    {{-- Col-md-6 is used to keep the form centered and compact on large screens --}}
    <div class="col-md-6">
        <div class="card card-secondary card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Create Role</div>
            </div>
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    {{-- Role Name Field (Using form-group mb-3 for better spacing, like in Staffs) --}}
                    <div class="form-group mb-3">
                        <label for="name" class="col-form-label">Role Name</label>
                        <input class="form-control" type="text" name="name" id="name" placeholder="Enter Role Name" required />
                    </div>
                    {{-- You can add more role-specific fields here if needed in the future --}}
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    {{-- Added Cancel button for better navigation, consistent with Staffs form --}}
                    <a href="{{ route('roles.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection