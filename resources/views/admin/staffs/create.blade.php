@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    {{-- Page Header and Breadcrumb --}}
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0">Staffs</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('staffs.index') }}">Staffs</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>

    {{-- Create Staff Form Card (Using Role's Card Layout) --}}
    <div class="col-md-6">
        <div class="card card-secondary card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Create Staff</div>
            </div>
            <form action="{{ route('staffs.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    {{-- Name Field --}}
                    <div class="form-group mb-3">
                        <label for="name" class="col-form-label">Name</label>
                        <input class="form-control" type="text" name="name" id="name" placeholder="Enter Staff Name" required />
                    </div>

                    {{-- Email Field --}}
                    <div class="form-group mb-3">
                        <label for="email" class="col-form-label">Email</label>
                        <input class="form-control" type="email" name="email" id="email" placeholder="Enter Email" required />
                    </div>

                    {{-- Password Field --}}
                    <div class="form-group mb-3">
                        <label for="password" class="col-form-label">Password</label>
                        <input class="form-control" type="password" name="password" id="password" placeholder="Enter Password" required />
                    </div>

                    {{-- Role Dropdown --}}
                    <div class="form-group">
                        <label for="role" class="col-form-label">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="">-- Select Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Card Footer for Button --}}
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Create Staff</button>
                    <a href="{{ route('staffs.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection