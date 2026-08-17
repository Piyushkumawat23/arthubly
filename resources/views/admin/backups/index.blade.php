@extends('admin.layout.app')

@section('content')
<div class="content-header">
    <div class="container-fluid row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">System Backups</h1>
        </div>
        <div class="col-sm-6 text-end">
            <form action="{{ route('admin.backups.generate') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success"><i class="bi bi-database-down"></i> Generate New DB Backup</button>
            </form>
        </div>
    </div>
</div>

<div class="content">
    <div class="card">
        <div class="card-body p-0 table-responsive">
            <table class="table table-striped table-hover text-sm">
                <thead class="table-light">
                    <tr>
                        <th>File Name</th>
                        <th>File Size</th>
                        <th>Created At</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backups as $backup)
                    <tr>
                        <td class="fw-bold text-primary"><i class="bi bi-file-zip"></i> {{ $backup['name'] }}</td>
                        <td>{{ $backup['size'] }}</td>
                        <td>{{ $backup['date'] }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.backups.download', $backup['name']) }}" class="btn btn-sm btn-primary"><i class="bi bi-download"></i> Download</a>
                            
                            <form action="{{ route('admin.backups.destroy', $backup['name']) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this backup?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center p-4 text-muted">No backups found. Click generate to create one.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection