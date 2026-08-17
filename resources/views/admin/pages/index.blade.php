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
                <li class="breadcrumb-item active" aria-current="page">Pages</li>
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

    {{-- Create Button Section --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0"></h3>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-success">Create New Page</a>
    </div>

    {{-- Pages List Card --}}
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Page List</h3>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 10px;">#</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th style="width: 250px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $page)
                        <tr class="align-middle">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $page->title }}</td>
                            <td>{{ $page->slug }}</td>
                            <td>
                                {{-- Status Badge --}}
                                @if(strtolower($page->status) == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($page->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                
                                {{-- Delete Form with File Checkbox --}}
                                <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" style="display:inline-block; margin-left: 5px;">
                                    @csrf
                                    @method('DELETE')
                                    
                                    {{-- Checkbox for deleting file --}}
                                    <div class="form-check form-check-inline" style="margin-right: 5px;">
                                        <input class="form-check-input" type="checkbox" name="delete_file" value="1" id="del_file_{{ $page->id }}">
                                        <label class="form-check-label" for="del_file_{{ $page->id }}" style="font-size: 12px; cursor: pointer;">
                                            Del File
                                        </label>
                                    </div>

                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this page? This action cannot be undone.')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No Pages Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination (Placeholder) --}}
            <div class="card-footer clearfix">
                {{-- {{ $pages->links() }} --}}
                <ul class="pagination pagination-sm m-0 float-end">
                    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection