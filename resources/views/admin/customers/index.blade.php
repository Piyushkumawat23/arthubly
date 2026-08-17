@extends('admin.layout.app') {{-- Aapka main layout file name --}}

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Customer Management</h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Customer List</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">#ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Joined Date</th>
                            <th style="width: 150px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->created_at->format('d M, Y') }}</td>
                            <td>
                                @can('customers.edit')
                                <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan

                                @can('customers.delete')
                                <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
    </div>
</div>
@endsection