@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title">Edit Brand</h3>
        </div>

        <form action="{{ route('admin.brands.update', $brand->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="card-body">

                {{-- Brand Name --}}
                <div class="form-group">
                    <label>Brand Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $brand->name) }}"
                           required>
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description"
                              class="form-control"
                              rows="4">{{ old('description', $brand->description) }}</textarea>
                </div>

                {{-- Current Image --}}
                <div class="form-group">
                    <label>Current Image</label>

                    @if($brand->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $brand->image) }}"
                                 alt="{{ $brand->name }}"
                                 style="width: 120px; height: 120px; object-fit: contain; border: 1px solid #ddd; padding: 5px;">
                        </div>
                    @else
                        <p class="text-muted">No image uploaded.</p>
                    @endif
                </div>

                {{-- New Image --}}
                <div class="form-group">
                    <label>Change Image</label>
                    <input type="file"
                           name="image"
                           class="form-control"
                           accept="image/*">

                    <small class="text-muted">
                        Leave empty if you don't want to change the current image.
                    </small>
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label>Status</label>

                    <select name="status" class="form-control">
                        <option value="active"
                            {{ old('status', $brand->status) == 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ old('status', $brand->status) == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-dark">
                    Update
                </button>

                <a href="{{ route('admin.brands.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
