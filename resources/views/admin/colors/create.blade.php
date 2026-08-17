@extends('admin.layout.app')
@section('content')
<div class="container-fluid"><div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Add Color</h3></div>
    <form action="{{ route('admin.colors.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group"><label>Color Name</label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group"><label>Status</label><select name="status" class="form-control"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
</div></div>
@endsection