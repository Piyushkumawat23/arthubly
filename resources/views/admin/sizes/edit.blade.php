@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title">Edit Size: {{ $size->name }}</h3>
        </div>
        <form action="{{ route('admin.sizes.update', $size->id) }}" method="POST">
            @csrf 
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Size Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $size->name) }}" class="form-control" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="active" {{ (old('status', $size->status) == 'active') ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ (old('status', $size->status) == 'inactive') ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-dark">Update Size</button>
                <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection