@extends('admin.layout.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Edit Coupon</h1>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="card-body row">
                    <div class="form-group col-md-6 mb-3">
                        <label>Coupon Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase" value="{{ $coupon->code }}" required>
                    </div>
                    
                    <div class="form-group col-md-6 mb-3">
                        <label>Discount Type <span class="text-danger">*</span></label>
                        <select name="discount_type" class="form-control" required>
                            <option value="percentage" {{ $coupon->discount_type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="flat" {{ $coupon->discount_type == 'flat' ? 'selected' : '' }}>Flat Amount (₹)</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label>Discount Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="discount_amount" value="{{ $coupon->discount_amount }}" class="form-control" required>
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label>Usage Limit</label>
                        <input type="number" name="usage_limit" value="{{ $coupon->usage_limit }}" class="form-control">
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label>Expiry Date</label>
                        <input type="date" name="expiry_date" value="{{ $coupon->expiry_date }}" class="form-control">
                    </div>

                    <div class="form-group col-md-3 mb-3">
                        <label>Status</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ $coupon->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$coupon->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Coupon</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection