@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    {{-- Page Header and Breadcrumb --}}
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0">Pages</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
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

    {{-- Edit Page Form Card --}}
    <div class="col-md-12">
        <div class="card card-secondary card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Edit Page: {{ $page->title }}</div>
            </div>
            
            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="card-body">
                    
                    {{-- Row 1: Title & Slug --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="col-form-label">Title</label>
                            <input type="text" id="title" name="title" class="form-control" value="{{ $page->title }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="slug" class="col-form-label">Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control" value="{{ $page->slug }}" required>
                        </div>
                    </div>

                    {{-- Row 2: Status (Placed in half width for better look) --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="col-form-label">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active" {{ $page->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $page->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    {{-- Content Field --}}
                    <div class="form-group mb-3">
                        <label for="content" class="col-form-label">Content</label>
                        <textarea id="content" name="content" class="form-control" rows="8" required>{{ $page->content }}</textarea>
                    </div>

                </div>

                {{-- Card Footer for Buttons --}}
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Page</button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection