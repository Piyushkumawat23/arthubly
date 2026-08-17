@extends('admin.layout.app')

@section('content')
<style>
.brands-page{background:#f6f7f9;min-height:100vh;padding:24px}
.brands-head{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:20px}
.brands-title-wrap{display:flex;align-items:center;gap:13px}
.brands-icon{width:46px;height:46px;border-radius:12px;background:#111827;color:#fff;display:grid;place-items:center}
.brands-title{margin:0;font-size:23px;font-weight:750;color:#171a21}
.brands-sub{margin:4px 0 0;color:#7b8390;font-size:11px}
.brands-card{background:#fff;border:1px solid #e6e8ed;border-radius:14px;box-shadow:0 3px 14px rgba(16,24,40,.035);overflow:hidden}
.brands-card-head{padding:15px 18px;border-bottom:1px solid #e8eaee;display:flex;align-items:center;justify-content:space-between}
.brands-card-title{margin:0;font-size:13px;font-weight:700;color:#171a21}
.brands-btn{border-radius:8px!important;font-size:11px!important;font-weight:650!important;padding:9px 13px!important}
.brands-table{width:100%;margin:0;font-size:11px}
.brands-table thead th{padding:12px;background:#f8fafc;border:0;border-bottom:1px solid #e6e8ed;color:#68707d;text-transform:uppercase;font-size:9px;letter-spacing:.03em;white-space:nowrap}
.brands-table tbody td{padding:13px 12px;border:0;border-bottom:1px solid #eef0f3;vertical-align:middle;color:#4b5563}
.brands-table tbody tr:last-child td{border-bottom:0}
.brands-table tbody tr:hover{background:#fbfcfd}
.brand-id{font-size:10px;color:#8a909c;font-weight:700}
.brand-name{font-weight:700;color:#252a34}
.brand-img{width:48px;height:48px;border-radius:8px;object-fit:contain;border:1px solid #e6e8ed;background:#f8fafc;padding:4px}
.brand-placeholder{display:inline-grid;place-items:center;width:48px;height:48px;border-radius:8px;border:1px dashed #d8dde5;background:#f8fafc;color:#a0a6b0;font-size:9px}
.brand-status{display:inline-flex;padding:5px 9px;border-radius:999px;font-size:9px;font-weight:700}
.brand-active{background:#ecfdf3;color:#15803d}
.brand-inactive{background:#f1f5f9;color:#64748b}
.brand-action{border-radius:7px!important;padding:6px 10px!important;font-size:10px!important;font-weight:600!important}
.brands-empty{padding:50px!important;text-align:center;color:#9ca3af}
.brands-empty i{font-size:27px;color:#c4c9d1;display:block;margin-bottom:8px}
@media(max-width:767px){.brands-page{padding:14px}.brands-head{flex-direction:column;align-items:flex-start}.brands-head .brands-btn{width:100%}.brands-table{min-width:700px}}
</style>

<div class="brands-page">
    <div class="brands-head">
        <div class="brands-title-wrap">
            <div class="brands-icon"><i class="fas fa-tags"></i></div>
            <div>
                <h1 class="brands-title">Brands</h1>
                <p class="brands-sub">Manage your product brands, images and status.</p>
            </div>
        </div>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-dark brands-btn">
            <i class="fas fa-plus mr-1"></i> Add Brand
        </a>
    </div>

    <div class="brands-card">
        <div class="brands-card-head">
            <h2 class="brands-card-title"><i class="fas fa-list mr-1"></i> Brands List</h2>
            <span style="font-size:10px;color:#8a909c;">{{ $brands->count() }} shown</span>
        </div>

        <div class="table-responsive">
            <table class="table brands-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($brands as $brand)
                    <tr>
                        <td><span class="brand-id">#{{ $brand->id }}</span></td>

                        <td>
                            @if($brand->image)
                                <img src="{{ asset('public/uploads/brands/'.$brand->image) }}"
                                     alt="{{ $brand->name }}"
                                     class="brand-img">
                            @else
                                <span class="brand-placeholder">No Image</span>
                            @endif
                        </td>

                        <td>
                            <span class="brand-name">{{ $brand->name }}</span>
                        </td>

                        <td>
                            @if(strtolower($brand->status) == 'active')
                                <span class="brand-status brand-active">
                                    Active
                                </span>
                            @else
                                <span class="brand-status brand-inactive">
                                    {{ ucfirst($brand->status) }}
                                </span>
                            @endif
                        </td>

                        <td class="text-right">
                            <a href="{{ route('admin.brands.edit', $brand->id) }}"
                               class="btn btn-outline-primary brand-action">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>

                            <form action="{{ route('admin.brands.destroy', $brand->id) }}"
                                  method="POST"
                                  style="display:inline-block"
                                  onsubmit="return confirm('Are you sure you want to delete this brand?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-outline-danger brand-action ml-1">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="brands-empty">
                            <i class="fas fa-tags"></i>
                            No Brands Found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection