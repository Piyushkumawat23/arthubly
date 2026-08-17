@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0">Roles</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Roles</li>
            </ol>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0"></h3>
        <a href="{{ route('roles.create') }}" class="btn btn-success">Create Role</a>
    </div>


    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h3 class="card-title">Role Name Table</h3>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-gray">
                        <tr>
                            <th style="width: 10px;">#</th>
                            <th>Role Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr class="align-middle">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ ucfirst($role->name) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">No Roles Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-end">
                    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection