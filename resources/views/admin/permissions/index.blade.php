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
                <li class="breadcrumb-item active" aria-current="page">Permissions</li>
            </ol>
        </div>
    </div>

    {{-- Success Message and Add Button --}}
    <div class="row mb-3">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Add Permission Button aligned right --}}
            @can('permissions.add')
                <a href="{{ route('permissions.create') }}" class="btn btn-success float-end">Add Permission</a>
            @endcan
        </div>
    </div>

    {{-- Permissions Table Card --}}
    <div class="card card-secondary card-outline mb-4">
        <div class="card-header">
            <div class="card-title">List of Permissions</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Module</th>
                            <th>Action</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissiones as $permission)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $permission->module }}</td>
                                <td>{{ $permission->action }}</td>
                                <td>{{ $permission->name }}</td>
                                {{-- Status Column with Toggle Button --}}
                                <td>
                                    @can('permissions.edit')
                                        <button 
                                            class="btn btn-sm toggle-status-btn" 
                                            data-id="{{ $permission->id }}" 
                                            data-is-active="{{ $permission->is_active }}"
                                            data-url="{{ route('permissions.toggle_status', $permission->id) }}"
                                            id="status-btn-{{ $permission->id }}"
                                            style="background: none; border: none; padding: 0;"
                                            >
                                            {!! $permission->is_active ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-danger">Inactive</span>' !!}
                                        </button>
                                    @else
                                        {!! $permission->is_active ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-danger">Inactive</span>' !!}
                                    @endcan
                                </td>
                                {{-- Actions Column --}}
                                <td>
                                    @can('permissions.edit')
                                        <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    @endcan
                                    @can('permissions.delete')
                                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            {{-- NOTE: confirm() is bad practice in embedded environments, but kept here to maintain user's original functionality flow. --}}
                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this permission?')" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No Permission Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-end">
                    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
            </div>
    </div>
</div>

{{-- JavaScript for Status Toggle using jQuery --}}
{{-- Make sure you have the necessary meta tag for CSRF token in your admin layout --}}
<script>
    // Load jQuery if not already loaded by the layout
    // This line is often redundant if the admin layout already includes jQuery, but included for completeness.
    if (typeof jQuery == 'undefined') {
        document.write('<script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>');
    }
</script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            // Assumes a meta tag for the CSRF token exists in the main layout
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
        }
    });

    $(document).on('click', '.toggle-status-btn', function() {
        var button = $(this);
        var url = button.data('url');

        $.ajax({
            url: url,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    var isActive = response.is_active;
                    var newBadge = isActive 
                        ? '<span class="badge text-bg-success">Active</span>' 
                        : '<span class="badge text-bg-danger">Inactive</span>';

                    button.html(newBadge);
                    button.attr('data-is-active', isActive ? 1 : 0);
                    // Optional: Show a temporary success toast/message here instead of console.log
                }
            },
            error: function(xhr, status, error) {
                // Changed alert() to console.error() for better practice
                console.error("AJAX Error: Failed to toggle status.", {status: status, error: error, response: xhr.responseText});
                // Optional: Revert button state or show a temporary error message
            }
        });
    });
});
</script>
@endsection