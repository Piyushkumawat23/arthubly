@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    {{-- Page Header and Breadcrumb --}}
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0">Menu Management</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menus</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Menu</li>
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

    {{-- Edit Menu Form Card --}}
    <div class="col-md-12">
        <div class="card card-secondary card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Edit Menu: {{ $menu->title }}</div>
            </div>
            
            <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="card-body">
                    
                    {{-- Row 1: Title & Slug --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="col-form-label">Title</label>
                            <input type="text" id="title" name="title" class="form-control" value="{{ $menu->title }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="slug" class="col-form-label">Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control" value="{{ $menu->slug }}" required>
                        </div>
                    </div>

                    {{-- Row 2: Parent Menu & URL --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="parent_id" class="col-form-label">Parent Menu (Optional)</label>
                            <select id="parent_id" name="parent_id" class="form-control">
                                <option value="">-- Main Menu (No Parent) --</option>
                                @foreach($parentMenus as $option)
                                    <option value="{{ $option['id'] }}" {{ $menu->parent_id == $option['id'] ? 'selected' : '' }}>
                                        {{ $option['title'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="url" class="col-form-label">URL</label>
                            <input type="text" id="url" name="url" class="form-control" value="{{ $menu->url }}" placeholder="e.g., /about-us or https://google.com">
                        </div>
                    </div>

                    {{-- Row 3: Order & Status --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="order" class="col-form-label">Order</label>
                            <input type="number" id="order" name="order" class="form-control" value="{{ $menu->order }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="col-form-label">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="1" {{ $menu->status ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$menu->status ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                </div>

                {{-- Card Footer for Buttons --}}
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Menu</button>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection