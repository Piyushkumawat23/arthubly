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
        <div class="cat-title-wrap"><div class="cat-icon"><i class="fas fa-folder-plus"></i></div><div><h1 class="cat-title">Add Category</h1><p class="cat-sub">Create a new product category with image, icon and description.</p></div></div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary cat-btn"><i class="fas fa-arrow-left mr-1"></i> Back to Categories</a>
    </div>
    @if(session('success'))<div class="alert alert-success cat-alert">{{ session('success') }}</div>@endif
    <div class="cat-form-layout">
        <div class="cat-form-card">
            <div class="cat-form-head"><h2 class="cat-card-title"><i class="fas fa-info-circle mr-1"></i> Category Information</h2></div>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="cat-form-body">
                    <div class="mb-3"><label for="name" class="cat-label">Category Name</label><input class="form-control cat-input" type="text" name="name" id="name" placeholder="Enter category name" required></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label for="image" class="cat-label">Category Image</label><input class="form-control cat-input cat-upload" type="file" name="image" id="image" accept="image/*"><small class="cat-help">Recommended: high quality JPG or PNG image.</small></div>
                        <div class="col-md-6 mb-3"><label for="icon" class="cat-label">Category Icon</label><input class="form-control cat-input cat-upload" type="file" name="icon" id="icon" accept="image/*"><small class="cat-help">Recommended: small PNG or SVG icon.</small></div>
                    </div>
                    <div class="mb-3 cat-editor"><label for="description" class="cat-label">Description</label><textarea class="form-control" name="description" id="description" rows="4" placeholder="Enter category description"></textarea></div>
                    <div class="mb-1"><label for="status" class="cat-label">Status</label><select name="status" id="status" class="form-control cat-input"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                </div>
                <div class="cat-form-foot"><a href="{{ route('admin.categories.index') }}" class="btn btn-light cat-btn">Cancel</a><button type="submit" class="btn btn-dark cat-btn"><i class="fas fa-save mr-1"></i> Save Category</button></div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
ClassicEditor.create(document.querySelector('#description'),{toolbar:['heading','|','bold','italic','link','bulletedList','numberedList','blockQuote','insertTable','undo','redo']}).catch(error=>console.error(error));
</script>
@endsection