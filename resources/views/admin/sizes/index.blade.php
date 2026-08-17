@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Sizes List</h3>
            <a href="{{ route('admin.sizes.create') }}" class="btn btn-primary btn-sm float-right">Add Size</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Size Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sizes as $size)
                    <tr>
                        <td>{{ $size->id }}</td>
                        <td>{{ $size->name }}</td>
                        <td>
                            @if($size->status == 'active')
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.sizes.edit', $size->id) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.sizes.destroy', $size->id) }}" method="POST" style="display:inline;">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this size?');"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="mt-3">
                {{ $sizes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection