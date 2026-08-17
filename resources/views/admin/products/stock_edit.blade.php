@extends('admin.layout.app')

@section('content')
<style>
    .stock-update-page {
        --su-bg: #f6f7f9;
        --su-card: #fff;
        --su-border: #e6e8ed;
        --su-text: #171a21;
        --su-muted: #747b88;
        --su-primary: #111827;
        --su-danger: #dc2626;
        background: var(--su-bg);
        min-height: 100vh;
        padding: 24px;
    }

    .su-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .su-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .su-heading-icon {
        width: 46px;
        height: 46px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: var(--su-primary);
        color: #fff;
        font-size: 17px;
    }

    .su-title {
        margin: 0;
        color: var(--su-text);
        font-size: 24px;
        font-weight: 750;
        line-height: 1.2;
    }

    .su-subtitle {
        margin: 5px 0 0;
        color: var(--su-muted);
        font-size: 13px;
    }

    .su-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 285px;
        gap: 20px;
        align-items: start;
    }

    .su-card {
        background: var(--su-card);
        border: 1px solid var(--su-border);
        border-radius: 14px;
        box-shadow: 0 3px 14px rgba(16,24,40,.035);
        overflow: hidden;
        margin-bottom: 18px;
    }

    .su-card-header {
        padding: 16px 19px;
        border-bottom: 1px solid var(--su-border);
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .su-section-icon {
        width: 32px;
        height: 32px;
        display: grid;
        place-items: center;
        border-radius: 8px;
        background: #f3f4f6;
        color: #374151;
        font-size: 12px;
    }

    .su-card-title {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--su-text);
    }

    .su-card-desc {
        margin: 2px 0 0;
        font-size: 11px;
        color: var(--su-muted);
    }

    .su-card-body {
        padding: 19px;
    }

    .su-main-stock {
        max-width: 520px;
    }

    .su-label {
        display: block;
        color: #374151;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .su-input-wrap {
        position: relative;
    }

    .su-input-wrap .form-control {
        height: 46px;
        border: 1px solid #dfe3e8;
        border-radius: 9px;
        box-shadow: none;
        font-size: 14px;
        font-weight: 600;
        padding-right: 80px;
    }

    .su-input-wrap .form-control:focus {
        border-color: #9ca3af;
    }

    .su-input-suffix {
        position: absolute;
        right: 13px;
        top: 13px;
        color: #9ca3af;
        font-size: 11px;
        pointer-events: none;
    }

    .su-stock-help {
        margin-top: 7px;
        color: #9ca3af;
        font-size: 10px;
    }

    .su-variations {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .su-variation {
        border: 1px solid var(--su-border);
        border-radius: 11px;
        background: #fff;
        padding: 14px;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .su-variation:hover {
        border-color: #cfd4dc;
        box-shadow: 0 4px 12px rgba(16,24,40,.04);
    }

    .su-variation-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 11px;
    }

    .su-variation-name {
        color: #171a21;
        font-size: 12px;
        font-weight: 700;
    }

    .su-variation-id {
        color: #9ca3af;
        font-size: 9px;
    }

    .su-variation-input {
        position: relative;
    }

    .su-variation-input .form-control {
        height: 40px;
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        box-shadow: none;
        font-size: 13px;
        font-weight: 600;
    }

    .su-low {
        margin-top: 5px;
        color: var(--su-danger);
        font-size: 9px;
        font-weight: 600;
    }

    .su-empty {
        text-align: center;
        padding: 30px 15px;
        color: var(--su-muted);
        border: 1px dashed #d8dce3;
        border-radius: 10px;
        background: #fafbfc;
        font-size: 12px;
    }

    .su-side {
        position: sticky;
        top: 20px;
    }

    .su-side-card {
        background: #fff;
        border: 1px solid var(--su-border);
        border-radius: 14px;
        box-shadow: 0 3px 14px rgba(16,24,40,.035);
        overflow: hidden;
        margin-bottom: 18px;
    }

    .su-side-title {
        padding: 15px 16px;
        border-bottom: 1px solid var(--su-border);
        color: #171a21;
        font-size: 14px;
        font-weight: 700;
    }

    .su-side-body {
        padding: 16px;
    }

    .su-product-name {
        color: #171a21;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.4;
    }

    .su-product-sku {
        margin-top: 4px;
        color: #8a909c;
        font-size: 11px;
    }

    .su-current-stock {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding-top: 13px;
        border-top: 1px solid #eef0f3;
    }

    .su-current-label {
        color: #727887;
        font-size: 11px;
    }

    .su-current-value {
        color: #111827;
        font-size: 16px;
        font-weight: 750;
    }

    .su-save {
        width: 100%;
        border-radius: 9px !important;
        padding: 10px !important;
        font-size: 12px !important;
        font-weight: 700 !important;
    }

    .su-back {
        width: 100%;
        margin-top: 8px;
        border-radius: 9px !important;
        padding: 9px !important;
        font-size: 12px;
        font-weight: 600;
    }

    .su-note {
        display: flex;
        gap: 9px;
        color: #6b7280;
        font-size: 10px;
        line-height: 1.5;
    }

    .su-note i {
        margin-top: 2px;
        color: #9ca3af;
    }

    @media (max-width: 1050px) {
        .su-layout {
            grid-template-columns: 1fr;
        }

        .su-side {
            position: static;
        }
    }

    @media (max-width: 800px) {
        .su-variations {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .stock-update-page {
            padding: 14px;
        }

        .su-header {
            flex-direction: column;
        }

        .su-title {
            font-size: 20px;
        }

        .su-variations {
            grid-template-columns: 1fr;
        }

        .su-card-header,
        .su-card-body {
            padding: 15px;
        }
    }
</style>

<div class="stock-update-page">
    <div class="su-header">
        <div class="su-heading">
            <div class="su-heading-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div>
                <h1 class="su-title">Update Stock</h1>
                <p class="su-subtitle">
                    Manage inventory for <strong>{{ $product->name }}</strong>
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.stock.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="su-layout">
            <main>
                {{-- MAIN STOCK --}}
                <section class="su-card">
                    <div class="su-card-header">
                        <div class="su-section-icon">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div>
                            <h2 class="su-card-title">Main Product Stock</h2>
                            <p class="su-card-desc">Update the total inventory quantity for this product.</p>
                        </div>
                    </div>

                    <div class="su-card-body">
                        <div class="su-main-stock">
                            <label class="su-label">Total Stock Quantity</label>

                            <div class="su-input-wrap">
                                <input
                                    type="number"
                                    name="main_stock"
                                    class="form-control"
                                    value="{{ $product->stock }}"
                                    min="0"
                                    required>
                                <span class="su-input-suffix">units</span>
                            </div>

                            <div class="su-stock-help">
                                Current stock: {{ $product->stock }} units
                            </div>
                        </div>
                    </div>
                </section>

                {{-- VARIATION STOCK --}}
                @if($product->variations->count() > 0)
                    <section class="su-card">
                        <div class="su-card-header">
                            <div class="su-section-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div>
                                <h2 class="su-card-title">Variation Wise Stock</h2>
                                <p class="su-card-desc">
                                    Update stock separately for each color and size variation.
                                </p>
                            </div>
                        </div>

                        <div class="su-card-body">
                            <div class="su-variations">
                                @foreach($product->variations as $var)
                                    <div class="su-variation">
                                        <div class="su-variation-top">
                                            <div>
                                                <div class="su-variation-name">
                                                    {{ $var->color }} / {{ $var->size }}
                                                </div>
                                                <div class="su-variation-id">
                                                    Variation #{{ $var->id }}
                                                </div>
                                            </div>

                                            @if($var->stock <= 5)
                                                <i class="fas fa-exclamation-circle text-danger"
                                                   title="Low stock"></i>
                                            @endif
                                        </div>

                                        <label class="su-label">Stock Quantity</label>

                                        <div class="su-variation-input">
                                            <input
                                                type="number"
                                                name="variation_stock[{{ $var->id }}]"
                                                class="form-control"
                                                value="{{ $var->stock }}"
                                                min="0">
                                        </div>

                                        @if($var->stock <= 5)
                                            <div class="su-low">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Low stock — only {{ $var->stock }} left
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @else
                    <section class="su-card">
                        <div class="su-card-body">
                            <div class="su-empty">
                                <i class="fas fa-layer-group mb-2" style="font-size: 22px;"></i>
                                <div>No product variations available.</div>
                                <div class="mt-1">Only the main product stock will be updated.</div>
                            </div>
                        </div>
                    </section>
                @endif
            </main>

            {{-- SIDE PANEL --}}
            <aside class="su-side">
                <div class="su-side-card">
                    <div class="su-side-title">Product</div>

                    <div class="su-side-body">
                        <div class="su-product-name">{{ $product->name }}</div>
                        <div class="su-product-sku">SKU: {{ $product->sku }}</div>

                        <div class="su-current-stock">
                            <span class="su-current-label">Current Main Stock</span>
                            <span class="su-current-value">{{ $product->stock }}</span>
                        </div>
                    </div>
                </div>

                <div class="su-side-card">
                    <div class="su-side-title">Save Changes</div>

                    <div class="su-side-body">
                        <button type="submit" class="btn btn-dark su-save">
                            <i class="fas fa-save mr-1"></i>
                            Save Stock Changes
                        </button>

                        <a href="{{ route('admin.stock.index') }}" class="btn btn-light border su-back">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Back to Inventory
                        </a>
                    </div>
                </div>

                <div class="su-side-card">
                    <div class="su-side-body">
                        <div class="su-note">
                            <i class="fas fa-info-circle"></i>
                            <span>
                                Make sure the stock quantity is correct before saving.
                                Variation stock is updated independently from the main product stock.
                            </span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection