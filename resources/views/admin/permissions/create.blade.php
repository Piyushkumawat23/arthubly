@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    {{-- Page Header and Breadcrumb --}}
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0">Permissions</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>

    {{-- Main Content Row --}}
    <div class="row">
        
        {{-- Left Side: Create Permission Form --}}
        <div class="col-md-6">
            <div class="card card-secondary card-outline mb-4">
                <div class="card-header">
                    <div class="card-title">Create Permission</div>
                </div>
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        
                        {{-- Permission Name Field --}}
                        <div class="form-group mb-3">
                            <label for="name" class="col-form-label">Permission Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="e.g., View Categories" required>
                            <small class="text-muted">Any descriptive name related to the field.</small>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Module Field --}}
                        <div class="form-group mb-3">
                            <label for="module" class="col-form-label">Module</label>
                            <input type="text" name="module" id="module" value="{{ old('module', $permission->module ?? '') }}" class="form-control" placeholder="e.g., categories" required>
                            <small class="text-muted">Part before the dot (e.g., <b>categories</b> in categories.view)</small>
                        </div>
                        
                        {{-- Action Field --}}
                        <div class="form-group mb-3">
                            <label for="action" class="col-form-label">Action</label>
                            <input type="text" name="action" id="action" value="{{ old('action', $permission->action ?? '') }}" class="form-control" placeholder="e.g., view" required>
                            <small class="text-muted">Part after the dot (e.g., <b>view</b> in categories.view)</small>
                        </div>

                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Permission</button>
                        <a href="{{ route('permissions.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Side: Instructions / Help --}}
        <div class="col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-info-circle"></i> How to fill this form?
                    </h5>
                </div>
                <div class="card-body">
                    <p>Fill the details based on the Route Middleware logic:</p>
                    
                    <div class="alert alert-light border">
                        <strong>Example Route:</strong><br>
                        <code>Route::get(..)->middleware('can:categories.view');</code>
                    </div>

                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr class="bg-light">
                                <th>Field</th>
                                <th>Value to Enter</th>
                                <th>Logic</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Permission Name</strong></td>
                                <td>View Categories</td>
                                <td>Kuch bhi related naam likh sakte hain.</td>
                            </tr>
                            <tr>
                                <td><strong>Module</strong></td>
                                <td><code>categories</code></td>
                                <td>Middleware me <code>.</code> (dot) se pehle ka hissa.</td>
                            </tr>
                            <tr>
                                <td><strong>Action</strong></td>
                                <td><code>view</code></td>
                                <td>Middleware me <code>.</code> (dot) ke baad ka hissa.</td>
                            </tr>
                        </tbody>
                    </table>

                    <hr>

                    <div class="alert alert-light border">
                        <strong>Complex Example (Nested):</strong><br>
                        <code>middleware('can:admin.categories.add');</code>
                    </div>

                    <ul>
                        <li>
                            <strong>Module:</strong> <code>admin.categories</code> 
                            <br><span class="text-muted text-sm">(Last dot se pehle ka poora hissa)</span>
                        </li>
                        <li>
                            <strong>Action:</strong> <code>add</code> 
                            <br><span class="text-muted text-sm">(Last dot ke baad wala hissa)</span>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
        
    </div> {{-- End Row --}}
</div>
@endsection