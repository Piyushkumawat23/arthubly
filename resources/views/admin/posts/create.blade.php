@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    {{-- Page Header and Breadcrumb --}}
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0">Posts</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>

    {{-- Flash Message Section --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Create Post Form Card --}}
    <div class="col-md-12">
        <div class="card card-secondary card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Create New Post</div>
            </div>
            
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    
                    {{-- Row 1: Title & Slug --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="col-form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter Post Title" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="slug" class="col-form-label">Slug (Optional)</label>
                            <input type="text" name="slug" id="slug" class="form-control" placeholder="URL Slug">
                            <small class="form-text text-muted">Leave empty to auto-generate from title.</small>
                        </div>
                    </div>

                    {{-- Row 2: Category & Status --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="col-form-label">Category</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="col-form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    {{-- Image Upload --}}
                    <div class="form-group mb-3">
                        <label for="image" class="col-form-label">Post Image (Optional)</label>
                        <input type="file" name="image" id="image" class="form-control">
                        <small class="form-text text-muted">Select an image to upload.</small>
                    </div>

                    {{-- Content Field --}}
                    <div class="form-group mb-3">
                        <label for="content" class="col-form-label">Content</label>
                        <textarea name="content" id="content" class="form-control" rows="6" placeholder="Write your post content here..." required></textarea>
                    </div>

                </div>

                {{-- Card Footer for Buttons --}}
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Save Post</button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection