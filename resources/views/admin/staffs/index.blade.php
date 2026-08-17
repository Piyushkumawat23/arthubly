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
                <li class="breadcrumb-item active" aria-current="page">Staffs</li>
            </ol>
        </div>
    </div>

    {{-- Create Button --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"></h3>
    <a href="{{ route('admin.staffs.create') }}" class="btn btn-success">Create Staff</a>
</div>

    {{-- Staff List Card --}}
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Staff List</h3>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 10px;">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffs as $staff)
                        <tr class="align-middle">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $staff->name }}</td>
                            <td>{{ $staff->email }}</td>
                            <td>{{ ucfirst($staff->role) }}</td>
                            <td>
                                <a href="{{ route('admin.staffs.edit', $staff->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                {{-- Delete Form (Optional, uncomment if needed) --}}
                                {{--
                                <form action="{{ route('staffs.destroy', $staff->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this staff member?')">Delete</button>
                                </form>
                                --}}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No Staff Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination (Placeholder for consistency) --}}
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