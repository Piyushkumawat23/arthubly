@extends('admin.layout.app')
@section('content')
<div class="container-fluid"><div class="card card-dark">
    <div class="card-header"><h3 class="card-title">Edit Color</h3></div>
    <form action="{{ route('admin.colors.update', $color->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="form-group"><label>Color Name</label><input type="text" name="name" value="{{ $color->name }}" class="form-control" required></div>
            <div class="form-group"><label>Status</label><select name="status" class="form-control"><option value="active" {{ $color->status=='active'?'selected':'' }}>Active</option><option value="inactive" {{ $color->status=='inactive'?'selected':'' }}>Inactive</option></select></div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-dark">Update</button></div>
    </form>
</div></div>
@endsection