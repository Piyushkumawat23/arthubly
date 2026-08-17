@extends('admin.layout.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Coupons Management</h1>
                </div>
                <div class="col-sm-6 text-end">
                    @can('coupons.add')
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">Add New Coupon</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped text-sm">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Discount</th>
                                <th>Usage Limit</th>
                                <th>Used</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($coupons as $coupon)
                                <tr>
                                    <td><strong>{{ $coupon->code }}</strong></td>
                                    <td>
                                        {{ $coupon->discount_type == 'percentage' ? $coupon->discount_amount . '%' : '₹' . $coupon->discount_amount }}
                                    </td>
                                    <td>{{ $coupon->usage_limit ?? 'Unlimited' }}</td>
                                    <td>{{ $coupon->used_count }}</td>
                                    <td>{{ $coupon->expiry_date ? \Carbon\Carbon::parse($coupon->expiry_date)->format('d M Y') : 'No Expiry' }}
                                    </td>
                                    <td>
                                        @if ($coupon->isValid())
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Expired/Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @can('coupons.edit')
                                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                                class="btn btn-sm btn-info"><i class="bi bi-pencil"></i></a>
                                        @endcan
                                        @can('coupons.delete')
                                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this coupon?')"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">{{ $coupons->links() }}</div>
            </div>
        </div>
    </div>
@endsection
