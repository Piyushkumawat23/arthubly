@extends('admin.layout.app')

{{-- Custom CSS for Drag & Drop Builder --}}
<style>
    /* --- Layout Utilities --- */
    /* Removed .card-box as we are using Bootstrap Cards now */

    /* --- PREVIEW CONTAINER --- */
    .mobile-preview {
        border: 2px solid #333;
        background: #f8f9fa;
        padding: 15px;
        min-height: 500px;
        border-radius: 4px;
    }

    .mobile-preview-header {
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
        border-bottom: 2px solid #333;
        padding-bottom: 10px;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }

    /* --- MENU ITEM BOXES --- */
    .menu-box {
        border: 1px solid #000;
        background: #fff;
        padding: 10px;
        margin-bottom: 5px;
        text-align: center;
        font-weight: 600;
        color: #000;
        cursor: grab;
        position: relative;
        box-shadow: 2px 2px 0px rgba(0,0,0,0.2); 
        z-index: 2;
    }

    .menu-box:active {
        cursor: grabbing;
        box-shadow: 1px 1px 0px rgba(0,0,0,0.2);
        transform: translate(1px, 1px);
    }

    .menu-box .drag-handle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #555;
        cursor: move;
    }

    /* --- INDENTATION & DROP ZONES --- */
    .sortable-list { 
        list-style: none; 
        padding: 0; 
        margin: 0; 
        min-height: 50px; 
        padding-bottom: 20px;
    }
    
    .child-list { 
        list-style: none; 
        padding: 0;
        margin-top: 5px;
        margin-left: 30px; 
        min-height: 20px; 
        border-left: 1px dashed #ccc; 
        padding-left: 10px;
        background: rgba(0,0,0,0.02); 
    }

    .child-list:empty {
        margin-top: 0;
        min-height: 10px;
        height: 10px; 
    }

    /* --- TABLE UTILS --- */
    .is-submenu { color: #666; padding-left: 20px !important; }
    .tree-icon { color: #4a90e2; margin-right: 5px; }
    .badge-parent { background: #e9ecef; color: #495057; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; border: 1px solid #ced4da; }
    
    .already-added {
        background-color: #d4edda; /* Light Green Background */
        color: #155724; /* Dark Green Text */
        border-color: #c3e6cb;
        padding: 5px 10px;
        border-radius: 4px;
        margin-bottom: 5px;
        opacity: 0.8;
    }
    
    .already-added label {
        font-weight: bold;
        cursor: not-allowed;
        width: 100%;
    }
    
    .already-added input {
        accent-color: #155724;
    }
</style>

@section('content')
<div class="container-fluid">

    {{-- Page Header and Breadcrumb --}}
    <div class="row mb-3">
        <div class="col-sm-6">
            <h3 class="mb-0">Menu Management</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Menus</li>
            </ol>
        </div>
    </div>

    {{-- Flash Message Section --}}
    <!-- @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif -->

    <div class="row">
        
        {{-- LEFT COLUMN: Drag & Drop Builder --}}
        <div class="col-md-4">

        {{-- 1. EXISTING PAGES BOX (Ye Naya Box Hai) --}}
        <div class="card card-secondary card-outline mb-4">
                <div class="card-header">
                    <h3 class="card-title">Pages</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.addPages') }}" method="POST">
                        @csrf
                        
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; padding: 10px; margin-bottom: 10px; border-radius: 4px; background: #fff;">
                            
                            @if(isset($pages) && $pages->count() > 0)
                                @foreach($pages as $page)
                                    {{-- Check karein agar ye page pehle se menu me hai --}}
                                    @php
                                        // Check if page slug exists in the menu list
                                        $isAdded = in_array($page->slug, $existingSlugs ?? []);
                                    @endphp

                                    <div class="form-check {{ $isAdded ? 'already-added' : '' }}">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="page_ids[]" 
                                               value="{{ $page->id }}" 
                                               id="page-{{ $page->id }}"
                                               {{-- Agar add ho chuka hai to checked dikhaye (optional) --}}
                                               {{ $isAdded ? 'checked disabled' : '' }}>
                                        
                                        <label class="form-check-label d-flex justify-content-between" for="page-{{ $page->id }}">
                                            {{ $page->title }}
                                            @if($isAdded)
                                                <span style="font-size: 0.8em;">(Added) <i class="uil uil-check"></i></span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted small text-center m-0">No active pages found.</p>
                            @endif

                        </div>

                        <button type="submit" class="btn btn-sm btn-outline-dark float-end">
                            <i class="uil uil-plus-circle"></i> Add to Menu
                        </button>
                    </form>
                </div>
            </div>
            
    <div class="card card-secondary card-outline mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Menu Structure</h3>
            {{-- NEW SAVE BUTTON --}}
            <button id="save-menu-btn" class="btn btn-primary btn-sm ms-auto">
                <i class="uil uil-save"></i> Save Order
            </button>
        </div>
                <div class="card-body">
                    <p class="text-muted text-center small mb-3">
                        <i class="uil uil-info-circle"></i> Drag any box into another to make it a submenu.
                    </p>

                    {{-- Visual Builder Container --}}
                    <div class="mobile-preview">
                        <div class="mobile-preview-header">Website Menu</div>

                        <ul class="sortable-list nested-sortable" data-parent-id="">
                            @foreach($menuTree as $menu)
                                <li data-id="{{ $menu->id }}">
                                    <div class="menu-box">
                                        {{ $menu->title }}
                                        <i class="uil uil-draggabledots drag-handle"></i>
                                    </div>

                                    <ul class="child-list nested-sortable" data-parent-id="{{ $menu->id }}">
                                        @foreach($menu->children as $child)
                                            <li data-id="{{ $child->id }}">
                                                <div class="menu-box" style="font-size: 0.9rem;">
                                                    {{ $child->title }}
                                                    <i class="uil uil-draggabledots drag-handle"></i>
                                                </div>

                                                <ul class="child-list nested-sortable" data-parent-id="{{ $child->id }}">
                                                    @foreach($child->children as $subChild)
                                                        <li data-id="{{ $subChild->id }}">
                                                            <div class="menu-box" style="font-size: 0.85rem;">
                                                                {{ $subChild->title }}
                                                                <i class="uil uil-draggabledots drag-handle"></i>
                                                            </div>
                                                            <ul class="child-list nested-sortable" data-parent-id="{{ $subChild->id }}"></ul>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Save Feedback Message --}}
                    <div id="save-msg" class="alert alert-success mt-3 text-center fw-bold" style="display:none;">
                        <i class="uil uil-check-circle"></i> ORDER SAVED!
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Menu Details Table --}}
        <div class="col-md-8">
            <div class="card card-secondary card-outline mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">All Menus Detail</h3>
                    {{-- Add Button in Header --}}
                    <a href="{{ route('admin.menus.createMenu') }}" class="btn btn-success btn-sm ms-auto">
                        <i class="uil uil-plus"></i> Add New Menu
                    </a>
                </div>

                <div class="card-body">
                    @if($menus->count() > 0)
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Title</th>
                                <th>Parent</th>
                                <th style="width: 80px;">Order</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $menu)
                                <tr class="align-middle">
                                <td>{{ $loop->iteration }}</td>
                                    
                                    {{-- Title with Indication --}}
                                    <td class="{{ $menu->parent_id ? 'is-submenu' : 'fw-bold' }}">
                                        @if($menu->parent_id) <span class="tree-icon">↳</span> @endif
                                        {{ $menu->title }}
                                    </td>

                                    {{-- Parent Badge --}}
                                    <td>
                                        @if(!$menu->parent_id)
                                            <span class="badge-parent">Root</span>
                                        @else
                                            <span class="badge-parent">{{ optional($menu->parent)->title }}</span>
                                        @endif
                                    </td>

                                    <td>{{ $menu->order }}</td>

                                    {{-- Status Badge --}}
                                    <td>
                                        @if($menu->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        
                                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" style="display:inline-block;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this menu item?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="uil uil-folder-slash fs-2"></i>
                            <p>No menus found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div> 
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Initialize Sortable (Visual Drag & Drop Only)
        var nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));

        for (var i = 0; i < nestedSortables.length; i++) {
            new Sortable(nestedSortables[i], {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                handle: '.drag-handle',
                // onEnd event se API call hata di gayi hai.
                // Ab ye sirf visual change karega.
            });
        }

        // 2. Button Click par Data Save karna
        document.getElementById('save-menu-btn').addEventListener('click', function() {
            
            var btn = this;
            btn.innerHTML = '<i class="uil uil-spinner"></i> Saving...';
            btn.disabled = true;

            // Pure Menu Structure ko read karne ka function
            var menuData = [];
            
            // Root list ko pakdo
            var rootList = document.querySelector('.sortable-list');
            
            // Recursive function to traverse DOM
            function parseList(list, parentId) {
                var items = list.children; // li elements
                for (var i = 0; i < items.length; i++) {
                    var li = items[i];
                    var id = li.getAttribute('data-id');
                    
                    if(id) {
                        // Data collect karo: ID, ParentID, Order (index + 1)
                        menuData.push({
                            id: id,
                            parent_id: parentId,
                            order: i + 1
                        });

                        // Check karo agar iske andar sub-menu (child-list) hai
                        var childList = li.querySelector('.child-list');
                        if (childList) {
                            parseList(childList, id); // Is ID ko parent banakar bhejo
                        }
                    }
                }
            }

            // Start parsing from Root (parentId = null)
            parseList(rootList, null);

            // 3. Send Data to Server
            fetch("{{ route('admin.menus.reorder') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ menus: menuData })
            })
            .then(res => res.json())
            .then(data => {
                btn.innerHTML = '<i class="uil uil-save"></i> Save Order';
                btn.disabled = false;

                if(data.success) {
                    var msg = document.getElementById('save-msg');
                    msg.style.display = 'block';
                    setTimeout(() => { msg.style.display = 'none'; }, 2000);
                    // Table update dekhne ke liye reload karein
                    setTimeout(() => location.reload(), 1000); 
                }
            })
            .catch(err => {
                console.error(err);
                btn.innerHTML = 'Error';
                btn.disabled = false;
            });
        });
    });
</script>
@endsection