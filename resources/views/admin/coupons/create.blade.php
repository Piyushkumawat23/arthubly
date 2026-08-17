@extends('admin.layout.app') @section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Create Coupon</h1>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="card-body row">
                    <div class="form-group col-md-6 mb-3">
                        <label>Coupon Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase" placeholder="e.g. SUMMER50" required>
                    </div>
                    
                    <div class="form-group col-md-6 mb-3">
                        <label>Discount Type <span class="text-danger">*</span></label>
                        <select name="discount_type" class="form-control" required>
                            <option value="percentage">Percentage (%)</option>
                            <option value="flat">Flat Amount (₹)</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4 mb-3">
                        <label>Discount Amount/Value <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="discount_amount" class="form-control" required>
                    </div>

                    <div class="form-group col-md-4 mb-3">
                        <label>Usage Limit (Leave blank for unlimited)</label>
                        <input type="number" name="usage_limit" class="form-control" placeholder="e.g. 100">
                    </div>

                    <div class="form-group col-md-4 mb-3">
                        <label>Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save Coupon</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection