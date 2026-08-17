@extends('admin.layout.app')
@section('content')

<style>
.cat-page{--bg:#f6f7f9;--card:#fff;--border:#e6e8ed;--text:#171a21;--muted:#747b88;background:var(--bg);min-height:100vh;padding:24px}
.cat-head{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:20px}.cat-title-wrap{display:flex;align-items:center;gap:13px}.cat-icon{width:46px;height:46px;border-radius:12px;background:#111827;color:#fff;display:grid;place-items:center}.cat-title{margin:0;color:var(--text);font-size:24px;font-weight:750}.cat-sub{margin:5px 0 0;color:var(--muted);font-size:12px}
.cat-btn{border-radius:8px!important;font-size:11px!important;font-weight:650!important;padding:9px 13px!important}.cat-alert{border-radius:9px;font-size:12px}
.cat-card{background:var(--card);border:1px solid var(--border);border-radius:14px;box-shadow:0 3px 14px rgba(16,24,40,.035);overflow:hidden}.cat-card-head{padding:14px 17px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}.cat-card-title{margin:0;font-size:13px;font-weight:700;color:var(--text)}
.cat-table-wrap{overflow-x:auto}.cat-table{width:100%;min-width:760px;margin:0;font-size:11px}.cat-table thead th{padding:12px;background:#f8fafc;color:#68707d;border:0;border-bottom:1px solid var(--border);font-size:9px;text-transform:uppercase;letter-spacing:.035em;white-space:nowrap}.cat-table tbody td{padding:13px 12px;border:0;border-bottom:1px solid #eef0f3;vertical-align:middle;color:#4b5563}.cat-table tbody tr:last-child td{border-bottom:0}.cat-table tbody tr:hover{background:#fbfcfd}
.cat-num{color:#8a909c;font-size:10px;font-weight:700}.cat-name{font-weight:700;color:#171a21}.cat-thumb{width:48px;height:48px;border-radius:8px;object-fit:cover;border:1px solid #e6e8ed;background:#f8fafc}.cat-icon-thumb{width:42px;height:42px;border-radius:8px;object-fit:cover;border:1px solid #e6e8ed;background:#f8fafc}.cat-placeholder{display:inline-grid;place-items:center;width:48px;height:48px;border-radius:8px;background:#f8fafc;border:1px dashed #d9dde4;color:#a0a6b0;font-size:9px}.cat-status{display:inline-flex;padding:5px 9px;border-radius:999px;font-size:9px;font-weight:700}.cat-active{background:#ecfdf3;color:#15803d}.cat-inactive{background:#f1f5f9;color:#64748b}.cat-action{border-radius:7px!important;padding:6px 10px!important;font-size:10px!important;font-weight:600!important}
.cat-empty{padding:48px!important;text-align:center;color:#9ca3af}.cat-empty i{font-size:27px;color:#c4c9d1;margin-bottom:8px}.cat-footer{padding:13px 17px;border-top:1px solid var(--border);display:flex;justify-content:flex-end}
.cat-form-layout{max-width:1000px}.cat-form-card{background:#fff;border:1px solid var(--border);border-radius:14px;box-shadow:0 3px 14px rgba(16,24,40,.035);overflow:hidden}.cat-form-head{padding:16px 19px;border-bottom:1px solid var(--border)}.cat-form-body{padding:20px}.cat-label{display:block;margin-bottom:7px;color:#4b5563;font-size:10px;font-weight:700}.cat-input{height:40px!important;border:1px solid #dfe3e8!important;border-radius:8px!important;box-shadow:none!important;font-size:11px!important}.cat-input:focus{border-color:#9ca3af!important}.cat-help{display:block;margin-top:5px;color:#9298a2;font-size:9px}.cat-upload{height:auto!important;padding:9px 10px!important}.cat-preview{margin-top:9px;padding:8px;background:#f8fafc;border:1px solid #edf0f3;border-radius:8px;display:inline-block}.cat-preview img{display:block;max-width:110px;max-height:85px;object-fit:contain;border-radius:5px}.cat-preview.icon img{max-width:60px;max-height:60px}.cat-form-foot{padding:14px 19px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:8px}.cat-editor .ck-editor__editable{min-height:190px}.cat-editor .ck.ck-toolbar{border-color:#dfe3e8;border-radius:8px 8px 0 0}.cat-editor .ck-editor__editable{border-color:#dfe3e8!important;border-radius:0 0 8px 8px!important;font-size:12px}.cat-editor .ck.ck-editor{width:100%}
@media(max-width:767px){.cat-page{padding:14px}.cat-head{align-items:flex-start;flex-direction:column}.cat-title{font-size:20px}.cat-btn{width:100%}.cat-form-body{padding:15px}}
</style>

<div class="cat-page">
    <div class="cat-head">
        <div class="cat-title-wrap">
            <div class="cat-icon"><i class="fas fa-layer-group"></i></div>
            <div><h1 class="cat-title">Categories</h1><p class="cat-sub">Manage your product categories, images and status.</p></div>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-dark cat-btn"><i class="fas fa-plus mr-1"></i> Add Category</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show cat-alert" role="alert">
            <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="cat-card">
        <div class="cat-card-head">
            <h2 class="cat-card-title"><i class="fas fa-list mr-1"></i> Category List</h2>
            <span style="font-size:10px;color:#8a909c;">{{ $categories->count() }} shown</span>
        </div>
        <div class="cat-table-wrap">
            <table class="table cat-table">
                <thead><tr><th>#</th><th>Icon</th><th>Image</th><th>Name</th><th>Status</th><th class="text-right">Actions</th></tr></thead>
                <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td><span class="cat-num">{{ $loop->iteration }}</span></td>
                        <td>
                            @if($category->icon)
                                <img src="{{ asset('public/uploads/categories/icons/' . $category->icon) }}" alt="Icon" class="cat-icon-thumb">
                            @else <span style="color:#a0a6b0;">—</span> @endif
                        </td>
                        <td>
                            @if($category->image)
                                <img src="{{ asset('public/uploads/categories/' . $category->image) }}" alt="{{ $category->name }}" class="cat-thumb">
                            @else <span class="cat-placeholder">No Image</span> @endif
                        </td>
                        <td><span class="cat-name">{{ $category->name }}</span></td>
                        <td>
                            @if($category->status)<span class="cat-status cat-active">Active</span>
                            @else<span class="cat-status cat-inactive">Inactive</span>@endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline-primary cat-action"><i class="fas fa-edit mr-1"></i>Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger cat-action ml-1"><i class="fas fa-trash mr-1"></i>Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="cat-empty"><i class="fas fa-folder-open d-block"></i>No Categories Found</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="cat-footer">
            <ul class="pagination pagination-sm m-0">
                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection