@extends('admin.layout.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Colors List</h3>
            <a href="{{ route('admin.colors.create') }}" class="btn btn-primary btn-sm float-right">Add Color</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead><tr><th>ID</th><th>Name</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    @foreach($colors as $color)
                    <tr>
                        <td>{{ $color->id }}</td><td>{{ $color->name }}</td><td>{{ ucfirst($color->status) }}</td>
                        <td>
                            <a href="{{ route('admin.colors.edit', $color->id) }}" class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('admin.colors.destroy', $color->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE') <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection