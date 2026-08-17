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

    {{-- Create Menu Form Card --}}
    <div class="col-md-12">
        <div class="card card-secondary card-outline mb-4">
            <div class="card-header">
                <div class="card-title">Add New Menu</div>
            </div>
            
            <form action="{{ route('admin.menus.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    
                    {{-- Row 1: Title & Slug --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="col-form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Menu Title" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="slug" class="col-form-label">Slug / Link</label>
                            <input type="text" name="slug" id="slug" class="form-control" placeholder="e.g. 'about-us'" required>
                            <small class="form-text text-muted">Enter page slug or link.</small>
                        </div>
                    </div>

                    {{-- Row 2: Parent Menu --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="parent_id" class="col-form-label">Parent Menu (Optional)</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="">-- Main Menu (Root) --</option>
                                @if(isset($parentMenus) && count($parentMenus) > 0)
                                    @foreach($parentMenus as $option)
                                        <option value="{{ $option['id'] }}">
                                            {{ $option['title'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        {{-- Status field (optional, agar aap add karna chahein to yahan aa sakta hai) --}}
                    </div>

                </div>

                {{-- Card Footer for Buttons --}}
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Save Menu</button>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection