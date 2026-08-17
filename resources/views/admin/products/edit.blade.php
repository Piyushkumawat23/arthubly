@extends('admin.layout.app')

@section('content')
<style>
    .product-edit-page {
        --pe-primary: #111827;
        --pe-border: #e5e7eb;
        --pe-muted: #6b7280;
        --pe-bg: #f7f8fa;
        --pe-card: #ffffff;
        background: var(--pe-bg);
        min-height: 100vh;
        padding: 24px;
    }

    .pe-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .pe-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .pe-title-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: #111827;
        color: #fff;
        font-size: 18px;
    }

    .pe-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #111827;
    }

    .pe-subtitle {
        margin: 4px 0 0;
        color: var(--pe-muted);
        font-size: 13px;
    }

    .pe-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .pe-btn {
        border-radius: 9px !important;
        padding: 9px 16px !important;
        font-weight: 600 !important;
        border: 1px solid var(--pe-border) !important;
    }

    .pe-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 300px;
        gap: 20px;
        align-items: start;
    }

    .pe-main {
        min-width: 0;
    }

    .pe-card {
        background: var(--pe-card);
        border: 1px solid var(--pe-border);
        border-radius: 14px;
        margin-bottom: 18px;
        box-shadow: 0 2px 8px rgba(17, 24, 39, .035);
        overflow: hidden;
    }

    .pe-card-head {
        padding: 17px 20px;
        border-bottom: 1px solid var(--pe-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .pe-card-head-left {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .pe-section-number {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #f3f4f6;
        color: #111827;
        display: grid;
        place-items: center;
        font-size: 12px;
        font-weight: 700;
    }

    .pe-card-title {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #111827;
    }

    .pe-card-desc {
        margin: 2px 0 0;
        color: var(--pe-muted);
        font-size: 12px;
    }

    .pe-card-body {
        padding: 20px;
    }

    .pe-card .form-group {
        margin-bottom: 16px;
    }

    .pe-card label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .pe-card .form-control,
    .pe-card .custom-select {
        min-height: 42px;
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        font-size: 13px;
        box-shadow: none !important;
    }

    .pe-card textarea.form-control {
        min-height: auto;
    }

    .pe-card .form-control:focus,
    .pe-card .custom-select:focus {
        border-color: #9ca3af;
    }

    .pe-required {
        color: #dc2626;
    }

    .pe-media-box {
        border: 1px dashed #d1d5db;
        border-radius: 11px;
        padding: 14px;
        background: #fafafa;
        height: 100%;
    }

    .pe-media-preview {
        width: 86px;
        height: 86px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid var(--pe-border);
        background: #fff;
        margin-bottom: 10px;
    }

    .pe-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(145px, 1fr));
        gap: 12px;
        padding: 14px;
        background: #f8fafc;
        border: 1px solid var(--pe-border);
        border-radius: 11px;
    }

    .pe-gallery-item {
        background: #fff;
        border: 1px solid var(--pe-border);
        border-radius: 10px;
        padding: 9px;
        position: relative;
    }

    .pe-gallery-item img {
        width: 100%;
        aspect-ratio: 1 / 1;
        object-fit: cover;
        border-radius: 7px;
        display: block;
        margin-bottom: 8px;
    }

    .pe-remove-check {
        color: #dc2626 !important;
        font-size: 11px !important;
    }

    .pe-upload-zone {
        border: 1px dashed #cbd5e1;
        background: #f8fafc;
        border-radius: 11px;
        padding: 16px;
    }

    .pe-gallery-block {
        background: #fff;
        border: 1px solid var(--pe-border);
        border-radius: 9px;
        padding: 12px;
        margin-bottom: 10px;
    }

    .pe-variation-wrap {
        overflow-x: auto;
    }

    .pe-variation-table {
        min-width: 950px;
        margin-bottom: 0;
    }

    .pe-variation-table thead th {
        background: #f8fafc;
        color: #4b5563;
        border-bottom: 1px solid var(--pe-border);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .02em;
        white-space: nowrap;
        padding: 11px 8px;
    }

    .pe-variation-table td {
        vertical-align: middle;
        padding: 9px 7px;
    }

    .pe-variation-table .form-control {
        min-height: 38px;
        font-size: 12px;
    }

    .pe-variation-thumb {
        width: 38px;
        height: 38px;
        object-fit: cover;
        border-radius: 7px;
        border: 1px solid var(--pe-border);
        flex: 0 0 auto;
    }

    .pe-side {
        position: sticky;
        top: 20px;
    }

    .pe-side-card {
        background: #fff;
        border: 1px solid var(--pe-border);
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 18px;
        box-shadow: 0 2px 8px rgba(17, 24, 39, .035);
    }

    .pe-side-head {
        padding: 15px 16px;
        border-bottom: 1px solid var(--pe-border);
        font-size: 14px;
        font-weight: 700;
        color: #111827;
    }

    .pe-side-body {
        padding: 16px;
    }

    .pe-status {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        background: #f8fafc;
        border-radius: 8px;
        color: #4b5563;
        font-size: 12px;
        margin-bottom: 14px;
    }

    .pe-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
    }

    .pe-side-nav a {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #4b5563;
        font-size: 12px;
        padding: 9px 10px;
        border-radius: 7px;
        text-decoration: none;
        margin-bottom: 3px;
    }

    .pe-side-nav a:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .pe-side-nav i {
        width: 16px;
        text-align: center;
    }

    .pe-switch-box {
        background: #f8fafc;
        border: 1px solid var(--pe-border);
        border-radius: 9px;
        padding: 12px;
    }

    .pe-sticky-actions {
        display: flex;
        gap: 9px;
        margin-top: 14px;
    }

    .pe-sticky-actions .btn {
        flex: 1;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .pe-help {
        color: #9ca3af;
        font-size: 11px;
        margin-top: 5px;
        display: block;
    }

    .pe-add-btn {
        border-radius: 8px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
    }

    @media (max-width: 1100px) {
        .pe-layout {
            grid-template-columns: 1fr;
        }

        .pe-side {
            position: static;
        }
    }

    @media (max-width: 767px) {
        .product-edit-page {
            padding: 14px;
        }

        .pe-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .pe-title {
            font-size: 20px;
        }

        .pe-card-body,
        .pe-card-head {
            padding: 15px;
        }

        .pe-gallery {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>

<div class="product-edit-page">
    <div class="pe-header">
        <div class="pe-title-wrap">
            <div class="pe-title-icon"><i class="fas fa-box"></i></div>
            <div>
                <h1 class="pe-title">Edit Product</h1>
                <p class="pe-subtitle">{{ $product->name }} <span class="mx-1">•</span> SKU: {{ $product->sku }}</p>
            </div>
        </div>

        <div class="pe-actions">
            <a href="{{ route('admin.products.index') }}" class="btn btn-light pe-btn">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
            <button type="submit" form="product-edit-form" class="btn btn-dark pe-btn">
                <i class="fas fa-save mr-1"></i> Save Changes
            </button>
        </div>
    </div>

    <form id="product-edit-form" action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="pe-layout">
            <main class="pe-main">

                {{-- BASIC INFORMATION --}}
                <section class="pe-card" id="basic-information">
                    <div class="pe-card-head">
                        <div class="pe-card-head-left">
                            <span class="pe-section-number">01</span>
                            <div>
                                <h2 class="pe-card-title">Basic Information</h2>
                                <p class="pe-card-desc">Product identity, category and related products.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pe-card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Product Name <span class="pe-required">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Slug (URL)</label>
                                    <input type="text" name="slug" class="form-control" value="{{ $product->slug }}">
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>SKU <span class="pe-required">*</span></label>
                                    <input type="text" name="sku" class="form-control" value="{{ $product->sku }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="5">{{ $product->description }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Category <span class="pe-required">*</span></label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Brand</label>
                                    <select name="brand_id" class="form-control">
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
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
                <section class="pe-card" id="pricing-stock">
                    <div class="pe-card-head">
                        <div class="pe-card-head-left">
                            <span class="pe-section-number">02</span>
                            <div>
                                <h2 class="pe-card-title">Pricing, Stock & Attributes</h2>
                                <p class="pe-card-desc">Set pricing, quantity limits, dimensions and shipping.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pe-card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label>Regular Price (₹) <span class="pe-required">*</span></label>
                                    <input type="number" name="price" class="form-control" value="{{ $product->price }}" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label>Sale Price (₹)</label>
                                    <input type="number" name="sale_price" class="form-control" value="{{ $product->sale_price }}" step="0.01">
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-4">
                                <div class="form-group">
                                    <label>Stock Qty <span class="pe-required">*</span></label>
                                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-4">
                                <div class="form-group">
                                    <label>Min Order Qty</label>
                                    <input type="number" name="min_order_qty" class="form-control" value="{{ $product->min_order_qty }}">
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-4">
                                <div class="form-group">
                                    <label>Max Order Qty</label>
                                    <input type="number" name="max_order_qty" class="form-control" value="{{ $product->max_order_qty }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Default Color</label>
                                    <input type="text" name="color" class="form-control" value="{{ $product->color }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Default Size</label>
                                    <input type="text" name="size" class="form-control" value="{{ $product->size }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Weight (kg/gm)</label>
                                    <input type="number" name="weight" step="0.01" class="form-control" value="{{ $product->weight }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Shipping Cost (₹)</label>
                                    <input type="number" name="shipping_cost" step="0.01" class="form-control" value="{{ $product->shipping_cost }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- MEDIA --}}
                <section class="pe-card" id="media-gallery">
                    <div class="pe-card-head">
                        <div class="pe-card-head-left">
                            <span class="pe-section-number">03</span>
                            <div>
                                <h2 class="pe-card-title">Product Media & Gallery</h2>
                                <p class="pe-card-desc">Manage hover image, video and product gallery.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pe-card-body">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="pe-media-box">
                                    <label><i class="fas fa-image mr-1"></i> Hover Image</label>
                                    <span class="pe-help">Recommended size: 276 × 276 px. Used on listing hover.</span>

                                    @if ($product->hover_image)
                                        <img src="{{ asset('public/uploads/products/hover/' . $product->hover_image) }}" class="pe-media-preview mt-2 d-block">
                                    @endif

                                    <input type="file" name="hover_image" class="form-control mt-2" accept="image/*">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="pe-media-box">
                                    <label><i class="fas fa-video mr-1"></i> Video URL</label>
                                    <span class="pe-help">YouTube or Vimeo video URL.</span>
                                    <input type="url" name="video_url" class="form-control mt-2" value="{{ $product->video_url }}" placeholder="https://www.youtube.com/watch?v=...">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="mb-0 font-weight-bold">Existing Gallery Images</label>
                            <small class="text-muted">{{ isset($product->images) ? $product->images->count() : 0 }} images</small>
                        </div>

                        @if (isset($product->images) && $product->images->count() > 0)
                            <div class="pe-gallery mb-4">
                                @foreach ($product->images as $img)
                                    <div class="pe-gallery-item">
                                        <img src="{{ asset('public/uploads/products/gallery/' . $img->image) }}">

                                        <select name="existing_gallery_color[{{ $img->id }}]" class="form-control form-control-sm mb-2">
                                            <option value="">General (All Colors)</option>
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->name }}" {{ $img->product_color == $color->name ? 'selected' : '' }}>
                                                    {{ $color->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" name="delete_gallery_images[]" id="del_img_{{ $img->id }}" value="{{ $img->id }}">
                                            <label for="del_img_{{ $img->id }}" class="custom-control-label pe-remove-check">
                                                Remove image
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-light border text-muted small">No gallery images found.</div>
                        @endif

                        <div class="pe-upload-zone">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <label class="font-weight-bold mb-1">Upload New Gallery Images</label>
                                    <span class="pe-help">You can assign each upload group to a specific color.</span>
                                </div>
                            </div>

                            <div id="gallery-blocks-container">
                                <div class="row align-items-end pe-gallery-block">
                                    <div class="col-md-6">
                                        <label class="small">Select Images</label>
                                        <input type="file" name="gallery_images[0][]" class="form-control" multiple accept="image/*">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="small">Assign to Color</label>
                                        <select name="gallery_colors[0]" class="form-control">
                                            <option value="">General (Shows for all colors)</option>
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->name }}">{{ $color->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-outline-danger btn-block remove-gallery-block" style="display:none;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-dark pe-add-btn add-gallery-block">
                                <i class="fas fa-plus mr-1"></i> Add More Gallery Colors
                            </button>
                        </div>
                    </div>
                </section>

                {{-- VARIATIONS --}}
                <section class="pe-card" id="variations">
                    <div class="pe-card-head">
                        <div class="pe-card-head-left">
                            <span class="pe-section-number">04</span>
                            <div>
                                <h2 class="pe-card-title">Product Variations</h2>
                                <p class="pe-card-desc">Create color and size combinations with individual stock and pricing.</p>
                            </div>
                        </div>

                        <button type="button" class="btn btn-dark pe-add-btn" id="add_variation_btn">
                            <i class="fas fa-plus mr-1"></i> Add Variation
                        </button>
                    </div>

                    <div class="pe-card-body p-0">
                        <div class="pe-variation-wrap">
                            <table class="table table-bordered table-hover text-center pe-variation-table" id="variation_table">
                                <thead>
                                    <tr>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Main Variation Image</th>
                                        <th>SKU</th>
                                        <th>Price (₹)</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if (isset($product->variations) && $product->variations->count() > 0)
                                        @foreach ($product->variations as $var)
                                            <tr>
                                                <td>
                                                    <select name="variation_color[]" class="form-control">
                                                        <option value="">Select Color</option>
                                                        @foreach ($colors as $color)
                                                            <option value="{{ $color->name }}" {{ $var->color == $color->name ? 'selected' : '' }}>
                                                                {{ $color->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td>
                                                    <select name="variation_size[]" class="form-control">
                                                        <option value="">Select Size</option>
                                                        @foreach ($sizes as $size)
                                                            <option value="{{ $size->name }}" {{ $var->size == $size->name ? 'selected' : '' }}>
                                                                {{ $size->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($var->image)
                                                            <img src="{{ asset('public/uploads/products/variations/' . $var->image) }}" class="pe-variation-thumb mr-2">
                                                            <input type="hidden" name="old_variation_image[]" value="{{ $var->image }}">
                                                        @else
                                                            <input type="hidden" name="old_variation_image[]" value="">
                                                        @endif
                                                        <input type="file" name="variation_image[]" class="form-control p-1" accept="image/*" style="font-size:11px;">
                                                    </div>
                                                </td>

                                                <td>
                                                    <input type="text" name="variation_sku[]" class="form-control" value="{{ $var->sku }}">
                                                </td>

                                                <td>
                                                    <input type="number" name="variation_price[]" step="0.01" class="form-control" value="{{ $var->price }}">
                                                </td>

                                                <td>
                                                    <input type="number" name="variation_stock[]" class="form-control" value="{{ $var->stock }}">
                                                </td>

                                                <td>
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                {{-- SEO --}}
                <section class="pe-card" id="seo-settings">
                    <div class="pe-card-head">
                        <div class="pe-card-head-left">
                            <span class="pe-section-number">05</span>
                            <div>
                                <h2 class="pe-card-title">SEO & Visibility</h2>
                                <p class="pe-card-desc">Control search metadata, status and storefront badges.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pe-card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control" value="{{ $product->meta_title }}">
                                </div>

                                <div class="form-group">
                                    <label>Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="form-control" value="{{ $product->meta_keywords }}">
                                </div>

                                @if(auth()->user()->role !== 'seller')
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="pending" {{ $product->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="rejected" {{ $product->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Meta Description</label>
                                    <textarea name="meta_description" class="form-control" rows="5">{{ $product->meta_description }}</textarea>
                                </div>

                                @if(auth()->user()->role !== 'seller')
                                    <div class="pe-switch-box">
                                        <label class="d-block mb-3">Visibility Badges</label>

                                        <div class="custom-control custom-switch mb-3">
                                            <input type="checkbox" class="custom-control-input" name="is_trending" id="is_trending" value="1" {{ $product->is_trending ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_trending">Mark as Trending</label>
                                        </div>

                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="is_new_arrival" id="is_new_arrival" value="1" {{ $product->is_new_arrival ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_new_arrival">Mark as New Arrival</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            {{-- RIGHT SIDEBAR --}}
            <aside class="pe-side">
                <div class="pe-side-card">
                    <div class="pe-side-head">Product Actions</div>
                    <div class="pe-side-body">
                        <div class="pe-status">
                            <span class="pe-status-dot"></span>
                            Editing product
                        </div>

                        <button type="submit" class="btn btn-dark btn-block pe-btn">
                            <i class="fas fa-save mr-1"></i> Update Product
                        </button>

                        <div class="pe-sticky-actions">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light border">Cancel</a>
                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        </div>
                    </div>
                </div>

                <div class="pe-side-card">
                    <div class="pe-side-head">Quick Navigation</div>
                    <div class="pe-side-body pe-side-nav">
                        <a href="#basic-information"><i class="fas fa-info-circle"></i> Basic Information</a>
                        <a href="#pricing-stock"><i class="fas fa-tags"></i> Pricing & Stock</a>
                        <a href="#media-gallery"><i class="fas fa-images"></i> Media & Gallery</a>
                        <a href="#variations"><i class="fas fa-layer-group"></i> Variations</a>
                        <a href="#seo-settings"><i class="fas fa-search"></i> SEO & Visibility</a>
                    </div>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    let colorOptions = '<option value="">Select Color</option>';
    @foreach ($colors as $color)
        colorOptions += '<option value="{{ $color->name }}">{{ $color->name }}</option>';
    @endforeach

    let sizeOptions = '<option value="">Select Size</option>';
    @foreach ($sizes as $size)
        sizeOptions += '<option value="{{ $size->name }}">{{ $size->name }}</option>';
    @endforeach

    let galleryColorOptions = '<option value="">General (Shows for all colors)</option>';
    @foreach ($colors as $color)
        galleryColorOptions += '<option value="{{ $color->name }}">{{ $color->name }}</option>';
    @endforeach

    let galleryIndex = 1;

    $(document).on('click', '.add-gallery-block', function () {
        let html = `
            <div class="row align-items-end pe-gallery-block">
                <div class="col-md-6">
                    <label class="small">Select Images</label>
                    <input type="file" name="gallery_images[${galleryIndex}][]" class="form-control" multiple accept="image/*">
                </div>

                <div class="col-md-4">
                    <label class="small">Assign to Color</label>
                    <select name="gallery_colors[${galleryIndex}]" class="form-control">
                        ${galleryColorOptions}
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger btn-block remove-gallery-block">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;

        $('#gallery-blocks-container').append(html);
        galleryIndex++;
    });

    $(document).on('click', '.remove-gallery-block', function () {
        $(this).closest('.pe-gallery-block').remove();
    });

    $("#add_variation_btn").click(function () {
        let html = `
            <tr>
                <td>
                    <select name="variation_color[]" class="form-control">
                        ${colorOptions}
                    </select>
                </td>

                <td>
                    <select name="variation_size[]" class="form-control">
                        ${sizeOptions}
                    </select>
                </td>

                <td>
                    <div class="d-flex align-items-center">
                        <input type="hidden" name="old_variation_image[]" value="">
                        <input type="file" name="variation_image[]" class="form-control p-1" accept="image/*" style="font-size:11px;">
                    </div>
                </td>

                <td>
                    <input type="text" name="variation_sku[]" class="form-control" placeholder="Auto/Custom">
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
            </tr>
        `;

        $("#variation_table tbody").append(html);
    });

    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });

    $('#product-edit-form').on('submit', function (e) {
        let seenCombinations = [];
        let hasDuplicate = false;

        $('#variation_table tbody tr').each(function () {
            let color = $(this).find('select[name="variation_color[]"]').val();
            let size = $(this).find('select[name="variation_size[]"]').val();

            if (color !== "" || size !== "") {
                let comboKey = (color + "-" + size).toLowerCase();

                if (seenCombinations.includes(comboKey)) {
                    hasDuplicate = true;
                    $(this).find('select').css('border-color', '#dc2626');
                    return false;
                } else {
                    seenCombinations.push(comboKey);
                    $(this).find('select').css('border-color', '#dfe3e8');
                }
            }
        });

        if (hasDuplicate) {
            e.preventDefault();
            alert('You have added a duplicate variation with the same Color and Size. Please remove the duplicate entry before submitting.');
        }
    });

    // Smooth scrolling for quick navigation
    $('.pe-side-nav a').on('click', function (e) {
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