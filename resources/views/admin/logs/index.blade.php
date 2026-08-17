@extends('admin.layout.app')

@section('content')
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-3">
            <div class="col-sm-6">
                <h1 class="m-0 fw-bold fs-3 text-dark">Activity Logs</h1>
                <p class="text-muted mb-0 fs-6">Track and monitor all system actions and data changes.</p>
            </div>
            <div class="col-sm-6 text-end">
                @if(auth()->user()->role === 'admin')
                <form action="{{ route('admin.logs.clear') }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to clear ALL logs? This action cannot be undone.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger shadow-sm">
                        <i class="bi bi-trash3-fill me-1"></i> Clear All Logs
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom p-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="card-title m-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i> System History</h5>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.logs.index') }}" method="GET" class="d-flex justify-content-md-end align-items-center mb-0">
                            <label class="text-muted me-2 small fw-medium">Filter By:</label>
                            <select name="module" class="form-select form-select-sm w-auto shadow-none border-secondary-subtle me-2" onchange="this.form.submit()">
                                <option value="">All Modules</option>
                                <option value="Authentication" {{ request('module') == 'Authentication' ? 'selected' : '' }}>Logins</option>
                                <option value="Customers" {{ request('module') == 'Customers' ? 'selected' : '' }}>Customers</option>
                                <option value="Staffs" {{ request('module') == 'Staffs' ? 'selected' : '' }}>Staffs</option>
                                <option value="Discounts" {{ request('module') == 'Discounts' ? 'selected' : '' }}>Discounts</option>
                            </select>
                            @if(request('module') || request('action'))
                                <a href="{{ route('admin.logs.index') }}" class="btn btn-sm btn-light border text-muted" title="Clear Filters">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            
           <div class="card-body p-0 table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light text-muted small text-uppercase">
            <tr>
                <th class="ps-4" style="width: 8%">Log ID</th>
                <th style="width: 20%">User</th>
                <th style="width: 12%">Action</th>
                <th style="width: 15%">Module</th>
                <th style="width: 25%">Summary</th>
                <th style="width: 15%">Timestamp</th>
                <th style="width: 5%" class="text-center">View</th>
            </tr>
        </thead>
        <tbody class="border-top-0">
            @forelse($logs as $log)
            <tr>
                <td class="ps-4 text-muted">#{{ $log->id }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2 border" style="width: 35px; height: 35px;">
                            <i class="bi bi-person-fill text-secondary"></i>
                        </div>
                        <div class="lh-sm">
                            @if($log->user)
                                <span class="fw-bold text-dark d-block">{{ $log->user->name }}</span>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ strtoupper($log->user->role) }}</small>
                            @else
                                <span class="badge bg-secondary rounded-pill">System</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    @if($log->action == 'Create') 
                        <span class="badge bg-success rounded-pill px-3 fw-normal">Create</span>
                    @elseif($log->action == 'Update') 
                        <span class="badge bg-warning text-dark rounded-pill px-3 fw-normal">Update</span>
                    @elseif($log->action == 'Delete') 
                        <span class="badge bg-danger rounded-pill px-3 fw-normal">Delete</span>
                    @elseif($log->action == 'Login') 
                        <span class="badge bg-info text-dark rounded-pill px-3 fw-normal">Login</span>
                    @else 
                        <span class="badge bg-dark rounded-pill px-3 fw-normal">{{ $log->action }}</span> 
                    @endif
                </td>
                <td><span class="fw-medium text-dark">{{ $log->module }}</span></td>
                
                {{-- Smart Summary --}}
                <td>
                    @php 
                        $details = @json_decode($log->description, true); 
                    @endphp
                    
                    @if(is_array($details) && isset($details['new']))
                        <span class="text-primary bg-primary-subtle px-2 py-1 rounded small">
                            <i class="bi bi-pencil-square me-1"></i> {{ count($details['new']) }} field(s) modified
                        </span>
                    @else
                        <div class="text-truncate text-muted" style="max-width: 280px;" title="{{ $log->description }}">
                            {{ $log->description }}
                        </div>
                    @endif
                </td>
                
                <td>
                    <span class="d-block text-dark">{{ $log->created_at->format('d M, Y') }}</span>
                    <small class="text-muted">{{ $log->created_at->format('h:i A') }}</small>
                </td>
                
                {{-- View Button & Modal MUST be inside a valid TD --}}
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-light border text-primary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $log->id }}" title="View Details">
                        <i class="bi bi-eye"></i>
                    </button>

                    <div class="modal fade text-start" id="viewModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-light border-bottom-0 pb-3">
                                    <h5 class="modal-title fw-bold">
                                        Activity Details <span class="badge bg-secondary ms-2 rounded-pill">#{{ $log->id }}</span>
                                    </h5>
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4 pt-2">
                                    
                                    <div class="row g-3 mb-4 bg-light p-3 rounded-3 border">
                                        <div class="col-sm-6 col-md-3">
                                            <small class="text-muted d-block text-uppercase fw-semibold mb-1">Module</small>
                                            <span class="fw-medium text-dark">{{ $log->module }}</span>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <small class="text-muted d-block text-uppercase fw-semibold mb-1">Action</small>
                                            <span class="fw-medium text-dark">{{ $log->action }}</span>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <small class="text-muted d-block text-uppercase fw-semibold mb-1">IP Address</small>
                                            <span class="text-dark"><i class="bi bi-globe me-1"></i> {{ $log->ip_address }}</span>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <small class="text-muted d-block text-uppercase fw-semibold mb-1">Timestamp</small>
                                            <span class="text-dark">{{ $log->created_at->format('d M, Y h:i A') }}</span>
                                        </div>
                                    </div>

                                    <h6 class="fw-bold mb-3 text-dark">Data Changes / Description</h6>
                                    
                                    @if(is_array($details) && isset($details['old']) && isset($details['new']))
                                        <div class="table-responsive border rounded-3">
                                            <table class="table table-borderless table-hover mb-0 text-sm">
                                                <thead class="table-light border-bottom">
                                                    <tr>
                                                        <th style="width: 25%" class="text-muted">Field</th>
                                                        <th style="width: 37%" class="text-muted">Previous Value</th>
                                                        <th style="width: 38%" class="text-muted">New Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($details['new'] as $key => $newValue)
                                                    <tr class="border-bottom">
                                                        <td class="text-capitalize bg-light text-dark fw-medium border-end">
                                                            {{ str_replace('_', ' ', $key) }}
                                                        </td>
                                                        <td class="text-danger bg-danger-subtle bg-opacity-10">
                                                            <i class="bi bi-dash-circle me-1"></i> 
                                                            <del>{{ is_array($details['old'][$key] ?? null) ? json_encode($details['old'][$key]) : ($details['old'][$key] ?? 'N/A') }}</del>
                                                        </td>
                                                        <td class="text-success bg-success-subtle bg-opacity-10">
                                                            <i class="bi bi-check-circle me-1"></i> 
                                                            {{ is_array($newValue) ? json_encode($newValue) : $newValue }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-light border text-dark mb-0">
                                            <i class="bi bi-info-circle me-2 text-primary"></i> {{ $log->description }}
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer border-top-0 bg-light">
                                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                    No activity logs found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
            
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection