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
                <li class="breadcrumb-item active" aria-current="page">Blogs</li>
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
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-success">Add New Blog</a>
    </div>

    {{-- Blog List Card --}}
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Blog List</h3>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 10px;">#</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blogs as $blog)
                        <tr class="align-middle">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $blog->title }}</td>
                            <td>
                                {{-- Check if category exists before accessing name --}}
                                <span class="badge bg-info text-dark">{{ $blog->category->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if($blog->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                
                                <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this blog?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No Blogs Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination (Placeholder for consistency) --}}
            <div class="card-footer clearfix">
                {{-- अगर Pagination Links हैं तो: {{ $blogs->links() }} --}}
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