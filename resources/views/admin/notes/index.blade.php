@extends('admin.layout.app') {{-- Apne layout ka naam yahan likhein --}}

@section('content')
<div class="container-fluid pt-3">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Short Notes</h3>
            <a href="{{ route('admin.notes.create') }}" class="btn btn-primary btn-sm ms-auto">Add New Note</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th>Title</th>
                            <th width="15%">Created At</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notes as $note)
                            <tr>
                                <td>{{ $note->id }}</td>
                                <td>{{ $note->title }}</td>
                                <td>{{ $note->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.notes.edit', $note->id) }}" class="btn btn-info btn-sm">Edit</a>
                                    <form action="{{ route('admin.notes.destroy', $note->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this note?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No notes found. Create your first note!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection