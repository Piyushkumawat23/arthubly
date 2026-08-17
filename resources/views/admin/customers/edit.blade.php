@extends('admin.layout.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Customer</h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="name">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email address</label>
                        <input type="email" name="email" class="form-control" value="{{ $customer->email }}" required>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Customer</button>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection