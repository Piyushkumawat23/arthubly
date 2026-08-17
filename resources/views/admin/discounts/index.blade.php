@extends('admin.layout.app')

@section('content')
<div class="content-header">
    <div class="container-fluid row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Discounts Management</h1></div>
        <div class="col-sm-6 text-end">
            @can('discounts.add')
            <a href="{{ route('admin.discounts.create') }}" class="btn btn-success">Add New Discount</a>
            @endcan
        </div>
    </div>
</div>

<div class="content">
    <div class="card">
        <div class="card-body p-0 table-responsive">
            <table class="table table-striped text-sm">
                <thead>
                    <tr>
                        <th>Campaign Name</th>
                        <th>Discount Value</th>
                        <th>Applied On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($discounts as $discount)
                    <tr>
                        <td>
                            <strong>{{ $discount->name }}</strong><br>
                            <small class="text-muted">{{ $discount->start_date }} to {{ $discount->end_date ?? 'No expiry' }}</small>
                        </td>
                        <td>
                            {{ $discount->discount_type == 'percentage' ? $discount->discount_amount.'%' : '₹'.$discount->discount_amount }}
                        </td>
                        <td>
                            @if($discount->apply_to_all)
                                <span class="badge bg-primary">All Products (Store-wide)</span>
                            @elseif($discount->category_id)
                                <span class="badge bg-info">Category: {{ $discount->category->name ?? 'N/A' }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $discount->products_count }} Specific Product(s)</span>
                            @endif
                        </td>
                        <td>
                            @if($discount->isValid())
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive/Expired</span>
                            @endif
                        </td>
                        <td>
                            @can('discounts.edit')
                            <a href="{{ route('admin.discounts.edit', $discount->id) }}" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('discounts.delete')
                            <form action="{{ route('admin.discounts.destroy', $discount->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this discount?')"><i class="bi bi-trash"></i></button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $discounts->links() }}</div>
    </div>
</div>
@endsection