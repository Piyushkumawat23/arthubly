@extends('admin.layout.app')

@section('content')
<style>
    .product-create-page {
        --pc-bg: #f6f7f9;
        --pc-card: #fff;
        --pc-border: #e6e8ed;
        --pc-text: #171a21;
        --pc-muted: #747b88;
        --pc-primary: #111827;
        background: var(--pc-bg);
        min-height: 100vh;
        padding: 24px;
    }

    .pc-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 22px;
    }

    .pc-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .pc-heading-icon {
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        background: var(--pc-primary);
        color: #fff;
        border-radius: 12px;
    }

    .pc-title {
        margin: 0;
        font-size: 24px;
        font-weight: 750;
        color: var(--pc-text);
    }

    .pc-subtitle {
        margin: 5px 0 0;
        color: var(--pc-muted);
        font-size: 13px;
    }

    .pc-header-actions {
        display: flex;
        gap: 9px;
    }

    .pc-header-actions .btn {
        border-radius: 9px;
        font-weight: 600;
        padding: 9px 15px;
    }

    .pc-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 285px;
        gap: 20px;
        align-items: start;
    }

    .pc-main {
        min-width: 0;
    }

    .pc-card {
        background: var(--pc-card);
        border: 1px solid var(--pc-border);
        border-radius: 14px;
        margin-bottom: 18px;
        box-shadow: 0 3px 14px rgba(16,24,40,.035);
        overflow: hidden;
    }

    .pc-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 19px;
        border-bottom: 1px solid var(--pc-border);
    }

    .pc-card-title-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pc-section-no {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: #f3f4f6;
        color: #111827;
        font-size: 11px;
        font-weight: 700;
    }

    .pc-card-title {
        margin: 0;
        color: var(--pc-text);
        font-size: 15px;
        font-weight: 700;
    }

    .pc-card-description {
        margin: 2px 0 0;
        color: var(--pc-muted);
        font-size: 11px;
    }

    .pc-card-body {
        padding: 19px;
    }

    .pc-card label {
        color: #374151;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .pc-card .form-group {
        margin-bottom: 16px;
    }

    .pc-card .form-control,
    .pc-card select {
        min-height: 42px;
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        box-shadow: none !important;
        font-size: 13px;
    }

    .pc-card textarea.form-control {
        min-height: auto;
    }

    .pc-required {
        color: #dc2626;
    }

    .pc-help {
        color: #9ca3af;
        font-size: 10px;
        font-weight: 400;
    }

    .pc-media-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .pc-upload-box {
        border: 1px dashed #cfd5dd;
        background: #fafbfc;
        border-radius: 11px;
        padding: 14px;
    }

    .pc-upload-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: #fff;
        border: 1px solid var(--pc-border);
        color: #4b5563;
        margin-bottom: 10px;
    }

    .pc-upload-box .form-control {
        background: #fff;
        padding: 7px 9px;
    }

    .pc-variation-wrap {
        overflow-x: auto;
    }

    .pc-variation-table {
        min-width: 800px;
        margin-bottom: 0;
    }

    .pc-variation-table thead th {
        background: #f8fafc;
        color: #69707c;
        border-bottom: 1px solid var(--pc-border);
        padding: 11px 9px;
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: .025em;
        white-space: nowrap;
    }

    .pc-variation-table td {
        padding: 9px 7px;
        vertical-align: middle;
    }

    .pc-variation-table .form-control {
        min-height: 38px;
        font-size: 12px;
    }

    .pc-add-btn {
        border-radius: 8px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
    }

    .pc-seo-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px;
    }

    .pc-side {
        position: sticky;
        top: 20px;
    }

    .pc-side-card {
        background: #fff;
        border: 1px solid var(--pc-border);
        border-radius: 14px;
        margin-bottom: 18px;
        overflow: hidden;
        box-shadow: 0 3px 14px rgba(16,24,40,.035);
    }

    .pc-side-title {
        padding: 15px 16px;
        border-bottom: 1px solid var(--pc-border);
        color: #171a21;
        font-size: 14px;
        font-weight: 700;
    }

    .pc-side-body {
        padding: 16px;
    }

    .pc-side-nav a {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #596170;
        font-size: 12px;
        text-decoration: none;
        padding: 9px 10px;
        border-radius: 7px;
        margin-bottom: 3px;
    }

    .pc-side-nav a:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .pc-side-nav i {
        width: 16px;
        text-align: center;
    }

    .pc-submit {
        width: 100%;
        border-radius: 9px !important;
        padding: 10px !important;
        font-weight: 700 !important;
    }

    .pc-cancel {
        width: 100%;
        margin-top: 8px;
        border-radius: 9px !important;
        padding: 9px !important;
        font-size: 12px;
        font-weight: 600;
    }

    .pc-switches {
        background: #f8fafc;
        border: 1px solid var(--pc-border);
        border-radius: 10px;
        padding: 13px;
    }

    .pc-switches .custom-control {
        margin-bottom: 10px;
    }

    .pc-switches .custom-control:last-child {
        margin-bottom: 0;
    }

    @media (max-width: 1100px) {
        .pc-layout {
            grid-template-columns: 1fr;
        }

        .pc-side {
            position: static;
        }
    }

    @media (max-width: 800px) {
        .pc-media-grid,
        .pc-seo-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 650px) {
        .product-create-page {
            padding: 14px;
        }

        .pc-header {
            flex-direction: column;
        }

        .pc-title {
            font-size: 20px;
        }

        .pc-header-actions {
            width: 100%;
        }

        .pc-header-actions .btn {
            flex: 1;
        }

        .pc-card-body,
        .pc-card-header {
            padding: 15px;
        }
    }
</style>

<div class="product-create-page">
    <div class="pc-header">
        <div class="pc-heading">
            <div class="pc-heading-icon">
                <i class="fas fa-plus"></i>
            </div>
            <div>
                <h1 class="pc-title">Add New Product</h1>
                <p class="pc-subtitle">Create a product with pricing, media, variations and SEO details.</p>
            </div>
        </div>

        <div class="pc-header-actions">
            <a href="{{ route('admin.products.index') }}" class="btn btn-light border">
                <i class="fas fa-arrow-left mr-1"></i> Back to Products
            </a>
            <button type="submit" form="product-create-form" class="btn btn-dark">
                <i class="fas fa-check mr-1"></i> Create Product
            </button>
        </div>
    </div>

    <form id="product-create-form" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="pc-layout">
            <main class="pc-main">

                {{-- BASIC INFORMATION --}}
                <section class="pc-card" id="basic-information">
                    <div class="pc-card-header">
                        <div class="pc-card-title-wrap">
                            <span class="pc-section-no">01</span>
                            <div>
                                <h2 class="pc-card-title">Basic Information</h2>
                                <p class="pc-card-description">Set the product identity, category and related products.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pc-card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Product Name <span class="pc-required">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter product name" value="{{ old('name') }}" required>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Slug <span class="pc-help">Leave empty to auto-generate</span></label>
                                    <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="product-url-slug">
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>SKU <span class="pc-help">Leave empty to auto-generate</span></label>
                                    <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" placeholder="SKU-001">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="product_description" class="form-control">{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category <span class="pc-required">*</span></label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Brand</label>
                                    <select name="brand_id" class="form-control">
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label>Similar / Related Products</label>
                                    <select name="related_products[]" class="form-control select2" multiple="multiple">
                                        @foreach ($allProducts as $p)
                                            @php
                                                $isSelected = isset($product) && $product->relatedProducts->contains($p->id) ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $p->id }}" {{ $isSelected }}>
                                                {{ $p->name }} ({{ $p->sku }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- PRICING --}}
                <section class="pc-card" id="pricing-stock">
                    <div class="pc-card-header">
                        <div class="pc-card-title-wrap">
                            <span class="pc-section-no">02</span>
                            <div>
                                <h2 class="pc-card-title">Pricing, Stock & Shipping</h2>
                                <p class="pc-card-description">Configure price, inventory limits and shipping information.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pc-card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label>Regular Price (₹) <span class="pc-required">*</span></label>
                                    <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price') }}" required>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label>Sale Price (₹)</label>
                                    <input type="number" name="sale_price" class="form-control" step="0.01" value="{{ old('sale_price') }}">
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-4">
                                <div class="form-group">
                                    <label>Stock Qty <span class="pc-required">*</span></label>
                                    <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" required>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-4">
                                <div class="form-group">
                                    <label>Min Qty</label>
                                    <input type="number" name="min_order_qty" class="form-control" value="{{ old('min_order_qty', 1) }}">
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-4">
                                <div class="form-group">
                                    <label>Max Qty</label>
                                    <input type="number" name="max_order_qty" class="form-control" value="{{ old('max_order_qty') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-3">
                                <div class="form-group mb-md-0">
                                    <label>Color</label>
                                    <input type="text" name="color" class="form-control" value="{{ old('color') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-md-0">
                                    <label>Size</label>
                                    <input type="text" name="size" class="form-control" value="{{ old('size') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-md-0">
                                    <label>Weight</label>
                                    <input type="number" name="weight" step="0.01" class="form-control" value="{{ old('weight') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label>Shipping Cost (₹)</label>
                                    <input type="number" name="shipping_cost" step="0.01" class="form-control" value="{{ old('shipping_cost', 0) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- MEDIA --}}
                <section class="pc-card" id="media-images">
                    <div class="pc-card-header">
                        <div class="pc-card-title-wrap">
                            <span class="pc-section-no">03</span>
                            <div>
                                <h2 class="pc-card-title">Media & Images</h2>
                                <p class="pc-card-description">Upload the main product image, gallery images and video.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pc-card-body">
                        <div class="pc-media-grid">
                            <div class="pc-upload-box">
                                <div class="pc-upload-icon"><i class="fas fa-image"></i></div>
                                <label>Thumbnail Image <span class="pc-help">Main</span></label>
                                <input type="file" name="thumbnail_image" class="form-control" accept="image/*">
                            </div>

                            <div class="pc-upload-box">
                                <div class="pc-upload-icon"><i class="fas fa-images"></i></div>
                                <label>Gallery Images <span class="pc-help">Multiple</span></label>
                                <input type="file" name="gallery_images[]" class="form-control" multiple accept="image/*">
                            </div>

                            <div class="pc-upload-box">
                                <div class="pc-upload-icon"><i class="fab fa-youtube"></i></div>
                                <label>Video URL <span class="pc-help">YouTube</span></label>
                                <input type="url" name="video_url" class="form-control" value="{{ old('video_url') }}" placeholder="https://youtube.com/...">
                            </div>
                        </div>
                    </div>
                </section>

                {{-- VARIATIONS --}}
                <section class="pc-card" id="variations">
                    <div class="pc-card-header">
                        <div class="pc-card-title-wrap">
                            <span class="pc-section-no">04</span>
                            <div>
                                <h2 class="pc-card-title">Product Variations</h2>
                                <p class="pc-card-description">Create size and color combinations with their own SKU, price and stock.</p>
                            </div>
                        </div>

                        <button type="button" class="btn btn-dark pc-add-btn" id="add_variation_btn">
                            <i class="fas fa-plus mr-1"></i> Add Variation
                        </button>
                    </div>

                    <div class="pc-card-body p-0">
                        <div class="pc-variation-wrap">
                            <table class="table table-bordered text-center pc-variation-table" id="variation_table">
                                <thead>
                                    <tr>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>SKU</th>
                                        <th>Price (₹)</th>
                                        <th>Stock Qty</th>
                                        <th width="75">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="variation_color[]" class="form-control">
                                                <option value="">Select Color</option>
                                                @foreach($colors as $color)
                                                    <option value="{{ $color->name }}">{{ $color->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <select name="variation_size[]" class="form-control">
                                                <option value="">Select Size</option>
                                                @foreach($sizes as $size)
                                                    <option value="{{ $size->name }}">{{ $size->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input type="text" name="variation_sku[]" class="form-control" placeholder="Auto / Custom">
                                        </td>

                                        <td>
                                            <input type="number" name="variation_price[]" step="0.01" class="form-control" placeholder="Price">
                                        </td>

                                        <td>
                                            <input type="number" name="variation_stock[]" class="form-control" placeholder="Qty">
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-row" title="Remove">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {{-- SEO --}}
                <section class="pc-card" id="seo">
                    <div class="pc-card-header">
                        <div class="pc-card-title-wrap">
                            <span class="pc-section-no">05</span>
                            <div>
                                <h2 class="pc-card-title">SEO Meta Tags</h2>
                                <p class="pc-card-description">Add search engine metadata for the product page.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pc-card-body">
                        <div class="pc-seo-grid">
                            <div class="form-group mb-0">
                                <label>Meta Title</label>
                                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
                            </div>

                            <div class="form-group mb-0">
                                <label>Meta Keywords</label>
                                <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}">
                            </div>

                            <div class="form-group mb-0">
                                <label>Meta Description</label>
                                <textarea name="meta_description" id="product_meta_description" class="form-control">{{ old('meta_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- STATUS --}}
                @if(auth()->user()->role !== 'seller')
                <section class="pc-card" id="visibility">
                    <div class="pc-card-header">
                        <div class="pc-card-title-wrap">
                            <span class="pc-section-no">06</span>
                            <div>
                                <h2 class="pc-card-title">Status & Visibility</h2>
                                <p class="pc-card-description">Control product status and storefront badges.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pc-card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="form-group mb-md-0">
                                    <label>Status <span class="pc-required">*</span></label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="pc-switches">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" name="is_trending" id="is_trending" value="1" {{ old('is_trending') ? 'checked' : '' }}>
                                        <label for="is_trending" class="custom-control-label">Is Trending</label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" name="is_new_arrival" id="is_new_arrival" value="1" {{ old('is_new_arrival') ? 'checked' : '' }}>
                                        <label for="is_new_arrival" class="custom-control-label">New Arrival</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @endif
            </main>

            {{-- RIGHT SIDEBAR --}}
            <aside class="pc-side">
                <div class="pc-side-card">
                    <div class="pc-side-title">Create Product</div>
                    <div class="pc-side-body">
                        <button type="submit" class="btn btn-dark pc-submit">
                            <i class="fas fa-check mr-1"></i> Submit Product
                        </button>

                        <a href="{{ route('admin.products.index') }}" class="btn btn-light border pc-cancel">
                            Cancel
                        </a>
                    </div>
                </div>

                <div class="pc-side-card">
                    <div class="pc-side-title">Quick Navigation</div>
                    <div class="pc-side-body pc-side-nav">
                        <a href="#basic-information"><i class="fas fa-info-circle"></i> Basic Information</a>
                        <a href="#pricing-stock"><i class="fas fa-tags"></i> Pricing & Stock</a>
                        <a href="#media-images"><i class="fas fa-images"></i> Media & Images</a>
                        <a href="#variations"><i class="fas fa-layer-group"></i> Variations</a>
                        <a href="#seo"><i class="fas fa-search"></i> SEO Meta Tags</a>
                        @if(auth()->user()->role !== 'seller')
                            <a href="#visibility"><i class="fas fa-eye"></i> Status & Visibility</a>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editorConfig = {
        toolbar: [
            'heading', '|',
            'bold', 'italic', 'underline', 'strikethrough', '|',
            'link', 'bulletedList', 'numberedList', '|',
            'blockQuote', 'insertTable', '|',
            'undo', 'redo'
        ]
    };

    ['#product_description', '#product_meta_description'].forEach(function (selector) {
        const element = document.querySelector(selector);

        if (!element) return;

        ClassicEditor.create(element, editorConfig)
            .catch(function (error) {
                console.error('CKEditor initialization failed:', error);
            });
    });
});
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let colorOptions = '<option value="">Select Color</option>';
    @foreach ($colors as $color)
        colorOptions += '<option value="{{ $color->name }}">{{ $color->name }}</option>';
    @endforeach

    let sizeOptions = '<option value="">Select Size</option>';
    @foreach ($sizes as $size)
        sizeOptions += '<option value="{{ $size->name }}">{{ $size->name }}</option>';
    @endforeach

    $("#add_variation_btn").click(function() {
        let html = `<tr>
            <td>
                <select name="variation_color[]" class="form-control">${colorOptions}</select>
            </td>
            <td>
                <select name="variation_size[]" class="form-control">${sizeOptions}</select>
            </td>
            <td>
                <input type="text" name="variation_sku[]" class="form-control" placeholder="Auto / Custom">
            </td>
            <td>
                <input type="number" name="variation_price[]" step="0.01" class="form-control" placeholder="Price">
            </td>
            <td>
                <input type="number" name="variation_stock[]" class="form-control" placeholder="Qty">
            </td>
            <td>
                <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;

        $("#variation_table tbody").append(html);
    });

    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });

    $('.pc-side-nav a').on('click', function(e) {
        e.preventDefault();

        const target = $(this.getAttribute('href'));

        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 20
            }, 350);
        }
    });
});
</script>