@extends('admin.layout.app')

@section('content')
<div class="content-header">
    <h1 class="m-0">Edit Discount Campaign</h1>
</div>

<div class="content">
    <div class="card card-success">
        <form action="{{ route('admin.discounts.update', $discount->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="card-body row">
                <div class="form-group col-md-12 mb-3">
                    <label>Campaign Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ $discount->name }}" required>
                </div>
                
                <div class="form-group col-md-4 mb-3">
                    <label>Discount Type *</label>
                    <select name="discount_type" class="form-control" required>
                        <option value="percentage" {{ $discount->discount_type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="flat" {{ $discount->discount_type == 'flat' ? 'selected' : '' }}>Flat Amount (₹)</option>
                    </select>
                </div>

                <div class="form-group col-md-4 mb-3">
                    <label>Discount Value *</label>
                    <input type="number" step="0.01" name="discount_amount" value="{{ $discount->discount_amount }}" class="form-control" required>
                </div>

                <div class="form-group col-md-4 mb-3">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ $discount->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$discount->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="form-group col-md-6 mb-3">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ $discount->start_date }}" class="form-control">
                </div>

                <div class="form-group col-md-6 mb-3">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="{{ $discount->end_date }}" class="form-control">
                </div>

                <div class="col-md-12 mt-3 mb-4">
                    <h5 class="text-primary border-bottom pb-2">Discount Target (Apply On)</h5>
                    
                    <div class="form-check mb-4 mt-3">
                        <input class="form-check-input border-secondary" type="checkbox" name="apply_to_all" id="apply_to_all" value="1" {{ $discount->apply_to_all ? 'checked' : '' }} style="transform: scale(1.3); margin-right: 10px;">
                        <label class="form-check-label font-weight-bold pt-1" for="apply_to_all" style="font-size: 1.1em; cursor: pointer; padding-left: 5px;">
                            Apply to ALL Products in the Store
                        </label>
                    </div>

                    <div id="specific_selection_area" class="p-3 border rounded bg-light" style="{{ $discount->apply_to_all ? 'display:none;' : '' }}">
                        <div class="row">
                            <div class="form-group col-md-12 mb-3">
                                <label class="fw-bold">Apply to Specific Category</label>
                                <select name="category_id" id="category_select" class="form-select">
                                    <option value="">-- No Category (Select Manual Products below) --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $discount->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12 mb-3">
                                <label class="fw-bold">Or Select Specific Products</label>
                                
                                <div class="card shadow-none border mb-0">
                                    <div class="card-header p-2 bg-white">
                                        <input type="text" id="custom_product_search" class="form-control" placeholder="Search product by name...">
                                    </div>
                                    
                                    <div class="card-body p-0 table-responsive" style="max-height: 350px; overflow-y: auto;">
                                        <table class="table table-hover table-bordered mb-0 text-sm">
                                            <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                                <tr>
                                                    <th style="width: 5%; text-align: center;">
                                                        <input type="checkbox" id="selectAllVisible" style="transform: scale(1.2);">
                                                    </th>
                                                    <th style="width: 50%;">Product Name</th>
                                                    <th style="width: 25%;">Category</th>
                                                    <th style="width: 20%;">Price</th>
                                                </tr>
                                            </thead>
                                            <tbody id="product_table_body">
                                                <tr><td colspan="4" class="text-center p-3 text-muted">Loading products...</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="card-footer p-2 bg-light text-end">
                                        <span class="badge bg-primary" id="selected_count">0</span> Products Selected
                                    </div>
                                </div>
                                
                                <div id="hidden_inputs_container">
                                    @if(isset($discount->products))
                                        @foreach($discount->products as $product)
                                            <input type="hidden" name="products[]" value="{{ $product->id }}" id="hidden_prod_{{ $product->id }}">
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Update Discount</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    
    // --- 1. Checkbox Toggle Logic (Apply to All) ---
    function toggleSelectionArea() {
        if ($('#apply_to_all').is(':checked')) {
            $('#specific_selection_area').slideUp(300);
            $('#category_select').prop('disabled', true);
        } else {
            $('#specific_selection_area').slideDown(300);
            $('#category_select').prop('disabled', false);
        }
    }
    toggleSelectionArea();
    $('#apply_to_all').on('change', function() { toggleSelectionArea(); });

    // --- 2. Table Data & AJAX Logic ---
    // Initialize map with already selected products from PHP
    let selectedProducts = new Map(); 
    @if(isset($discount->products))
        @foreach($discount->products as $product)
            selectedProducts.set("{{ $product->id }}", true);
        @endforeach
    @endif

    $('#selected_count').text(selectedProducts.size);

    // Function to load products
    function fetchProducts(query = '') {
        $('#product_table_body').html('<tr><td colspan="4" class="text-center p-3"><span class="spinner-border spinner-border-sm"></span> Loading...</td></tr>');
        
        $.ajax({
            url: "{{ route('admin.discounts.searchProducts') }}",
            type: "GET",
            data: { q: query },
            success: function(data) {
                if(data.length === 0) {
                    $('#product_table_body').html('<tr><td colspan="4" class="text-center p-3 text-danger">No products found.</td></tr>');
                    return;
                }

                let rows = '';
                $.each(data, function(index, product) {
                    let isChecked = selectedProducts.has(product.id.toString()) ? 'checked' : '';
                    
                    rows += `
                    <tr>
                        <td class="text-center">
                            <input class="form-check-input product-checkbox" type="checkbox" value="${product.id}" id="prod_${product.id}" ${isChecked} style="cursor:pointer; transform: scale(1.2);">
                        </td>
                        <td><label for="prod_${product.id}" style="cursor:pointer; width:100%; margin:0;">${product.name}</label></td>
                        <td>${product.category}</td>
                        <td>₹${product.price}</td>
                    </tr>`;
                });
                
                $('#product_table_body').html(rows);
                checkSelectAllStatus();
            }
        });
    }

    // Load initial 50 products on page load
    fetchProducts();

    // Trigger AJAX on search type (with 300ms delay to prevent too many requests)
    let typingTimer;
    $('#custom_product_search').on('keyup', function() {
        clearTimeout(typingTimer);
        let query = $(this).val();
        typingTimer = setTimeout(function() {
            fetchProducts(query);
        }, 300);
    });

    // --- 3. Handle Checkbox Click Inside Table ---
    $(document).on('change', '.product-checkbox', function() {
        let id = $(this).val();

        if ($(this).is(':checked')) {
            selectedProducts.set(id, true);
            if ($('#hidden_prod_' + id).length === 0) {
                $('#hidden_inputs_container').append(`<input type="hidden" name="products[]" value="${id}" id="hidden_prod_${id}">`);
            }
        } else {
            selectedProducts.delete(id);
            $('#hidden_prod_' + id).remove();
        }

        $('#selected_count').text(selectedProducts.size);
        checkSelectAllStatus();
    });

    // --- 4. Select All Logic ---
    $('#selectAllVisible').on('change', function() {
        let isChecked = $(this).is(':checked');
        $('.product-checkbox').each(function() {
            // Only trigger change if it's different from the master checkbox
            if($(this).is(':checked') !== isChecked) {
                $(this).prop('checked', isChecked).trigger('change');
            }
        });
    });

    function checkSelectAllStatus() {
        let total = $('.product-checkbox').length;
        let checked = $('.product-checkbox:checked').length;
        if(total > 0 && total === checked) {
            $('#selectAllVisible').prop('checked', true);
        } else {
            $('#selectAllVisible').prop('checked', false);
        }
    }
});
</script>
@endsection