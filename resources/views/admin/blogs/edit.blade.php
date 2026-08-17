@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    {{-- Page Header and Breadcrumb --}}
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0">Blogs</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}">Blogs</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
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

    {{-- Edit Blog Form Card --}}
    <div class="col-md-12">
        <div class="card card-secondary card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Edit Blog: {{ $blogs->title }}</div>
            </div>
            
            <form action="{{ route('admin.blogs.update', $blogs->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="card-body">
                    
                    {{-- Row 1: Title & Slug --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $blogs->title) }}" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $blogs->slug) }}">
                            @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 2: Category & Status --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select name="category_id" id="category_id" class="form-control">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $blogs->category_id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" {{ $blogs->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $blogs->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 3: Image Upload & Preview --}}
                    <div class="form-group mb-3">
                        <label for="image" class="form-label">Blog Image</label>
                        <input type="file" name="image" id="image" class="form-control">
                        
                        @if($blogs->image)
                            <div class="mt-2">
                                <label class="form-label d-block">Current Image:</label>
                                <img src="{{ asset('/' . $blogs->image) }}" class="img-thumbnail" width="150" alt="Blog Image">
                            </div>
                        @endif
                        
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Row 4: Content --}}
                    <div class="form-group mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" id="content" class="form-control" rows="6" required>{{ old('content', $blogs->content) }}</textarea>
                        @error('content')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                {{-- Card Footer for Buttons --}}
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Blog</button>
                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection