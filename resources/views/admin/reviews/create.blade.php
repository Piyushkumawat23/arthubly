@extends('admin.layout.app')

@section('content')

<style>
.review-form-page{--bg:#f6f7f9;--card:#fff;--border:#e6e8ed;--text:#171a21;--muted:#747b88;--primary:#111827;background:var(--bg);min-height:100vh;padding:24px}
.rf-head{display:flex;justify-content:space-between;align-items:flex-start;gap:20px;margin-bottom:20px}
.rf-heading{display:flex;align-items:center;gap:13px}.rf-icon{width:46px;height:46px;display:grid;place-items:center;border-radius:12px;background:var(--primary);color:#fff}
.rf-title{margin:0;font-size:24px;line-height:1.2;font-weight:750;color:var(--text)}.rf-sub{margin:5px 0 0;color:var(--muted);font-size:13px}
.rf-back{border-radius:9px!important;padding:9px 14px!important;font-size:12px!important;font-weight:600!important}
.rf-grid{display:grid;grid-template-columns:minmax(0,1fr) 285px;gap:20px;align-items:start}
.rf-card{background:var(--card);border:1px solid var(--border);border-radius:14px;box-shadow:0 3px 14px rgba(16,24,40,.035);overflow:hidden;margin-bottom:18px}
.rf-card-head{padding:16px 19px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:11px}
.rf-section-icon{width:32px;height:32px;display:grid;place-items:center;border-radius:8px;background:#f3f4f6;color:#374151;font-size:12px}
.rf-card-title{margin:0;font-size:15px;font-weight:700;color:var(--text)}.rf-card-desc{margin:2px 0 0;color:var(--muted);font-size:11px}
.rf-body{padding:19px}.rf-row{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;margin-bottom:16px}.rf-row:last-child{margin-bottom:0}
.rf-field label{display:block;color:#374151;font-size:12px;font-weight:700;margin-bottom:7px}.rf-required{color:#dc2626}
.rf-field .form-control,.rf-field select{height:43px;border:1px solid #dfe3e8;border-radius:8px;box-shadow:none;font-size:12px}
.rf-field textarea.form-control{height:auto;min-height:130px;resize:vertical;padding:12px;line-height:1.5}
.rf-readonly{background:#f8fafc!important;color:#6b7280}.rf-help{margin-top:6px;color:#9ca3af;font-size:10px}
.rf-side{position:sticky;top:20px}.rf-side-head{padding:15px 16px;border-bottom:1px solid var(--border);font-size:14px;font-weight:700;color:var(--text)}.rf-side-body{padding:16px}
.rf-summary{display:grid;gap:10px}.rf-summary-item{display:flex;justify-content:space-between;gap:10px;padding-bottom:10px;border-bottom:1px solid #eef0f3}.rf-summary-item:last-child{border-bottom:0;padding-bottom:0}.rf-summary-label{font-size:10px;color:#8a909c}.rf-summary-value{font-size:11px;font-weight:700;color:#374151;text-align:right}
.rf-save{width:100%;border-radius:9px!important;padding:10px!important;font-size:12px!important;font-weight:700!important}.rf-cancel{width:100%;margin-top:8px;border-radius:9px!important;padding:9px!important;font-size:12px;font-weight:600}
.rf-alert{border-radius:9px;font-size:12px;margin-bottom:18px}.rf-error-list{margin:0;padding-left:18px}
.rf-stars{display:flex;gap:5px;align-items:center}.rf-star{font-size:14px;color:#d1d5db}.rf-star.active{color:#f59e0b}
.rf-select2 .select2-container{width:100%!important}.select2-container .select2-selection--single{height:43px!important;border:1px solid #dfe3e8!important;border-radius:8px!important}.select2-container .select2-selection--single .select2-selection__rendered{line-height:41px!important;font-size:12px}.select2-container .select2-selection--single .select2-selection__arrow{height:41px!important}
@media(max-width:950px){.rf-grid{grid-template-columns:1fr}.rf-side{position:static}}
@media(max-width:650px){.review-form-page{padding:14px}.rf-head{flex-direction:column}.rf-title{font-size:20px}.rf-back{width:100%}.rf-row{grid-template-columns:1fr}.rf-body,.rf-card-head{padding:15px}}
</style>

<div class="review-form-page">
    <div class="rf-head">
        <div class="rf-heading">
            <div class="rf-icon"><i class="fas fa-star"></i></div>
            <div>
                <h1 class="rf-title">Add New Review</h1>
                <p class="rf-sub">Create a customer review and configure its visibility.</p>
            </div>
        </div>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-light border rf-back">
            <i class="fas fa-arrow-left mr-1"></i> Back to Reviews
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rf-alert">
            <strong><i class="fas fa-exclamation-circle mr-1"></i> Please fix the following:</strong>
            <ul class="rf-error-list mt-2 mb-0">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.reviews.store') }}" method="POST">
        @csrf
        <div class="rf-grid">
            <main>
                <section class="rf-card">
                    <div class="rf-card-head">
                        <div class="rf-section-icon"><i class="fas fa-comment-alt"></i></div>
                        <div><h2 class="rf-card-title">Review Details</h2><p class="rf-card-desc">Choose the product and customer for this review.</p></div>
                    </div>
                    <div class="rf-body">
                        <div class="rf-row">
                            <div class="rf-field rf-select2">
                                <label for="product_id">Select Product <span class="rf-required">*</span></label>
                                <select name="product_id" id="product_id" class="form-control select2" required>
                                    <option value="">-- Search Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="rf-field">
                                <label for="user_id">Select Customer/User <span class="rf-required">*</span></label>
                                <select name="user_id" id="user_id" class="form-control" required>
                                    <option value="">-- Choose User --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rf-card">
                    <div class="rf-card-head">
                        <div class="rf-section-icon"><i class="fas fa-sliders-h"></i></div>
                        <div><h2 class="rf-card-title">Review Settings</h2><p class="rf-card-desc">Set rating, approval and buyer verification.</p></div>
                    </div>
                    <div class="rf-body">
                        <div class="rf-row">
                            <div class="rf-field">
                                <label for="rating">Rating <span class="rf-required">*</span></label>
                                <select name="rating" id="rating" class="form-control" required>
                                    <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 Stars - Excellent</option>
                                    <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 Stars - Very Good</option>
                                    <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 Stars - Good</option>
                                    <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 Stars - Fair</option>
                                    <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 Star - Poor</option>
                                </select>
                            </div>
                            <div class="rf-field">
                                <label for="status">Approval Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Approved (Visible)</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Pending (Hidden)</option>
                                </select>
                            </div>
                        </div>
                        <div class="rf-row">
                            <div class="rf-field">
                                <label for="is_verified">Verified Buyer?</label>
                                <select name="is_verified" id="is_verified" class="form-control">
                                    <option value="1" {{ old('is_verified') == '1' ? 'selected' : '' }}>Yes, Verified</option>
                                    <option value="0" {{ old('is_verified') == '0' ? 'selected' : '' }}>No, Unverified</option>
                                </select>
                            </div>
                            <div class="rf-field">
                                <label>Rating Preview</label>
                                <div class="form-control" style="height:43px;display:flex;align-items:center;background:#fafbfc;">
                                    <span id="ratingPreview" class="rf-stars"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rf-card">
                    <div class="rf-card-head">
                        <div class="rf-section-icon"><i class="fas fa-pen"></i></div>
                        <div><h2 class="rf-card-title">Customer Comment</h2><p class="rf-card-desc">Write the review content that will be displayed to customers.</p></div>
                    </div>
                    <div class="rf-body">
                        <div class="rf-field">
                            <label for="comment">Comment</label>
                            <textarea name="comment" id="comment" rows="5" class="form-control" placeholder="Write review comment here...">{{ old('comment') }}</textarea>
                        </div>
                    </div>
                </section>
            </main>

            <aside class="rf-side">
                <div class="rf-card">
                    <div class="rf-side-head">Review Summary</div>
                    <div class="rf-side-body">
                        <div class="rf-summary">
                            <div class="rf-summary-item"><span class="rf-summary-label">Type</span><span class="rf-summary-value">Customer Review</span></div>
                            <div class="rf-summary-item"><span class="rf-summary-label">Approval</span><span class="rf-summary-value" id="statusSummary">Approved</span></div>
                            <div class="rf-summary-item"><span class="rf-summary-label">Buyer</span><span class="rf-summary-value" id="verifiedSummary">Verified</span></div>
                        </div>
                    </div>
                </div>
                <div class="rf-card">
                    <div class="rf-side-head">Publish Review</div>
                    <div class="rf-side-body">
                        <button type="submit" class="btn btn-dark rf-save"><i class="fas fa-save mr-1"></i> Save Review</button>
                        <button type="reset" class="btn btn-light border rf-cancel"><i class="fas fa-undo mr-1"></i> Reset</button>
                    </div>
                </div>
            </aside>
        </div>
    </form>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function(){
    $('.select2').select2({placeholder:'Type product name...',allowClear:true});
    function updatePreview(){
        let rating=parseInt($('#rating').val()||0), html='';
        for(let i=1;i<=5;i++) html+='<i class="fas fa-star rf-star '+(i<=rating?'active':'')+'"></i>';
        $('#ratingPreview').html(html);
        $('#statusSummary').text($('#status').val()==1?'Approved':'Pending');
        $('#verifiedSummary').text($('#is_verified').val()==1?'Verified':'Unverified');
    }
    $('#rating,#status,#is_verified').on('change',updatePreview); updatePreview();
});
</script>
@endsection