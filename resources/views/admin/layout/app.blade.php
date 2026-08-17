<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Admin v1 | Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('public/assets/admin/css/adminlte.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/adminlte.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">


    <style>
        /* Flash Message Box Common Styles */
        /* Flash Message Box Common Styles */
        .flash-message {
            position: fixed;
            bottom: 20px;
            right: 20px;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            /* Opacity 1 rakhein taki agar animation fail ho to bhi dikhe */
            opacity: 1;
            animation: slideIn 0.5s ease-out;
        }

        /* Success Type */
        .flash-success {
            background-color: #28a745;
            /* Green */
            border-left: 5px solid #1e7e34;
        }

        /* Error Type */
        .flash-error {
            background-color: #dc3545;
            /* Red */
            border-left: 5px solid #bd2130;
        }

        .flash-message-content {
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Niche se upar aane wala animation */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                /* Right side se aayega */
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>

</head>

<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    @if (session('success'))
        <div class="flash-message flash-success" id="success-alert">
            <div class="flash-message-content">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="flash-message flash-error" id="error-alert">
            <div class="flash-message-content">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>

                    <div class="d-flex justify-content-around align-items-left align-items-stretch ml-3">
                        <div class="aiz-topbar-item">
                            <div class="d-flex align-items-center" title="Clear Cache">
                                <form action="{{ route('admin.update-code') }}" method="POST" id="updateCodeForm">

                                    @csrf

                                    <button type="submit" class="btn btn-primary"
                                        onclick="return confirm('Are you sure you want to update the code/clear cache?')">
                                        Update Code
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </ul>
                <!--end::Start Navbar Links-->
                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto">
                    <!--begin::Navbar Search-->
                    <li class="nav-item">
                        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>
                    <!--end::Navbar Search-->
                    <!--begin::Messages Dropdown Menu-->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-chat-text"></i>
                            <span class="navbar-badge badge text-bg-danger">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('public/assets/admin/assets/img/user1-128x128.jpg') }}"
                                            alt="User Avatar" class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            Brad Diesel
                                            <span class="float-end fs-7 text-danger"><i
                                                    class="bi bi-star-fill"></i></span>
                                        </h3>
                                        <p class="fs-7">Call me whenever you can...</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>

                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('public/assets/admin/assets/img/user3-128x128.jpg') }}"
                                            alt="User Avatar" class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            Nora Silvester
                                            <span class="float-end fs-7 text-warning">
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                        </h3>
                                        <p class="fs-7">The subject goes here</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                        </div>
                    </li>
                    <!--end::Messages Dropdown Menu-->
                    <!--begin::Notifications Dropdown Menu-->
                    @php
                        $user = auth()->user();
                        $unreadNotifications = $user ? $user->unreadNotifications : collect();
                        $count = $unreadNotifications->count();
                    @endphp

                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-bell-fill"></i>
                            @if ($count > 0)
                                <span class="navbar-badge badge text-bg-danger">{{ $count }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow-sm border-0">
                            <span class="dropdown-item dropdown-header fw-bold bg-light">
                                {{ $count }} Unread Notifications
                            </span>

                            <div class="dropdown-divider m-0"></div>

                            <div style="max-height: 300px; overflow-y: auto;">
                                @forelse($unreadNotifications->take(5) as $notification)
                                    <a href="{{ $notification->data['url'] ?? '#' }}"
                                        class="dropdown-item text-wrap py-2">
                                        @if ($notification->data['type'] == 'order')
                                            <i class="bi bi-cart-check-fill text-success me-2"></i>
                                        @elseif($notification->data['type'] == 'stock')
                                            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                        @elseif($notification->data['type'] == 'review')
                                            <i class="bi bi-star-fill text-warning me-2"></i>
                                        @elseif($notification->data['type'] == 'setting')
                                            <i class="bi bi-gear-fill text-secondary me-2"></i>
                                        @elseif($notification->data['type'] == 'system')
                                            <i class="bi bi-shield-lock-fill text-danger me-2"></i>
                                        @elseif($notification->data['type'] == 'email')
                                            <i class="bi bi-envelope-fill text-info me-2"></i>
                                        @else
                                            <i class="bi bi-info-circle-fill text-primary me-2"></i>
                                        @endif

                                        <span class="text-dark text-sm">{{ $notification->data['message'] }}</span>
                                        <div class="text-muted fs-7 text-end mt-1">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </a>
                                    <div class="dropdown-divider m-0"></div>
                                @empty
                                    <div class="dropdown-item text-center text-muted py-3">
                                        <i class="bi bi-bell-slash fs-4 d-block mb-1"></i>
                                        No new notifications
                                    </div>
                                @endforelse
                            </div>

                            @if ($count > 0)
                                <div class="dropdown-divider m-0"></div>
                                <a href="{{ route('admin.notifications.read') }}"
                                    class="dropdown-item dropdown-footer text-center text-primary fw-medium bg-light">
                                    Mark all as read
                                </a>
                            @endif
                        </div>
                    </li>
                    <!--end::Notifications Dropdown Menu-->
                    <!--begin::Fullscreen Toggle-->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                        </a>
                    </li>
                    <!--end::Fullscreen Toggle-->
                    <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ asset('public/assets/admin/assets/img/user2-160x160.jpg') }}"
                                class="user-image rounded-circle shadow" alt="User Image" />
                            <span class="d-none d-md-inline">{{ $user->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <li class="user-header text-bg-primary">
                            <img src="{{ asset('public/assets/admin/assets/img/user2-160x160.jpg') }}"
                                class="rounded-circle shadow" alt="User Image" />
                            <p>
                                {{ auth()->user()->name }} 
                                <small style="text-transform: capitalize;">Role: {{ auth()->user()->role }}</small>
                                
                                <small>Member since {{ auth()->user()->created_at->format('M. Y') }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <a href="{{ route('admin.profile.edit') }}" class="btn btn-default btn-flat">Profile</a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                            <a href="#" class="btn btn-default btn-flat float-end"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Sign out
                            </a>
                        </li>
                        </ul>
                    </li>
                    <!--end::User Menu Dropdown-->
                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>
        <!--end::Header-->
        <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <!--begin::Brand Link-->
                <a href="{{ route('admin.dashboard') }}" class="brand-link">
                    <!--begin::Brand Image-->
                    <img src="{{ asset('public/assets/admin/assets/img/AdminLTELogo.png') }}"
                        src="{{ asset('public/assets/admin/assets/img/AdminLTELogo.jpng') }}" alt="Super Admin Logo"
                        class="brand-image opacity-75 shadow" />
                    <!--end::Brand Image-->
                    <!--begin::Brand Text-->
                    <span class="brand-text fw-light">Super Admin 1</span>
                    <!--end::Brand Text-->
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu"
                        data-accordion="false">

                        {{-- 1. Dashboard --}}
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link @if (Request::routeIs('admin.dashboard')) active @endif">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        {{-- 2. E-commerce Operations --}}
                        @canany(['products.view', 'products.add'])
                            <li class="nav-item @if (Request::routeIs('admin.products.*') || Request::routeIs('admin.stock.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('admin.products.*') || Request::routeIs('admin.stock.*')) active @endif">
                                    <i class="nav-icon bi bi-box-seam"></i>
                                    <p>Products <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('products.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.products.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.products.index')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>View Products</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.stock.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.stock.index')) active @endif">
                                                <i class="nav-icon bi bi-circle text-warning"></i>
                                                <p>Stock Management</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('products.add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.products.create') }}"
                                                class="nav-link @if (Request::routeIs('admin.products.create')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Add Product</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                        @can('reviews.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.reviews.index') }}"
                                    class="nav-link @if (Request::routeIs('admin.reviews.*')) active @endif">
                                    <i class="nav-icon bi bi-star"></i>
                                    <p>Reviews</p>
                                </a>
                            </li>
                        @endcan
                       @can('orders.view')
                        <li class="nav-item @if (Request::routeIs('admin.orders.*')) menu-open @endif">
                            <a href="#" class="nav-link @if (Request::routeIs('admin.orders.*')) active @endif">
                                <i class="nav-icon bi bi-cart-check"></i>
                                <p>Orders <i class="nav-arrow bi bi-chevron-right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                {{-- All Orders --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index') }}"
                                        class="nav-link @if (Request::routeIs('admin.orders.index') && !request('status')) active @endif">
                                        <i class="nav-icon bi bi-list-ul"></i>
                                        <p>All Orders</p>
                                    </a>
                                </li>

                                {{-- Pending --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index', ['status' => 'Pending']) }}"
                                        class="nav-link @if (request('status') === 'Pending') active @endif">
                                        <i class="nav-icon bi bi-hourglass-split text-warning"></i>
                                        <p>Pending</p>
                                    </a>
                                </li>

                                {{-- Processing --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index', ['status' => 'Processing']) }}"
                                        class="nav-link @if (request('status') === 'Processing') active @endif">
                                        <i class="nav-icon bi bi-gear text-info"></i>
                                        <p>Processing</p>
                                    </a>
                                </li>

                                {{-- Shipped --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index', ['status' => 'Shipped']) }}"
                                        class="nav-link @if (request('status') === 'Shipped') active @endif">
                                        <i class="nav-icon bi bi-truck text-primary"></i>
                                        <p>Shipped</p>
                                    </a>
                                </li>

                                {{-- Delivered --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index', ['status' => 'Delivered']) }}"
                                        class="nav-link @if (request('status') === 'Delivered') active @endif">
                                        <i class="nav-icon bi bi-check-circle text-success"></i>
                                        <p>Delivered</p>
                                    </a>
                                </li>

                                {{-- Cancelled --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index', ['status' => 'Cancelled']) }}"
                                        class="nav-link @if (request('status') === 'Cancelled') active @endif">
                                        <i class="nav-icon bi bi-x-circle text-danger"></i>
                                        <p>Cancelled</p>
                                    </a>
                                </li>

                                {{-- Returns --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index', ['status' => 'Returns']) }}"
                                        class="nav-link @if (request('status') === 'Returns') active @endif">
                                        <i class="nav-icon bi bi-arrow-return-left text-secondary"></i>
                                        <p>Returns</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    {{-- ============================================================= --}}
                    {{-- RETURNS & REFUNDS MENU                                        --}}
                    {{-- Orders menu ke aas-paas paste karo                            --}}
                    {{-- ============================================================= --}}
                    @can('returns.view')
                        <li class="nav-item @if (Request::routeIs('admin.returns.*')) menu-open @endif">
                            <a href="#" class="nav-link @if (Request::routeIs('admin.returns.*')) active @endif">
                                <i class="nav-icon bi bi-arrow-return-left"></i>
                                <p>Returns & Refunds <i class="nav-arrow bi bi-chevron-right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.returns.index', ['status' => 'Pending']) }}"
                                        class="nav-link @if (request('status') === 'Pending') active @endif">
                                        <i class="nav-icon bi bi-hourglass-split text-warning"></i>
                                        <p>Return Requests</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.returns.index', ['refund' => 'pending']) }}"
                                        class="nav-link @if (request('refund') === 'pending') active @endif">
                                        <i class="nav-icon bi bi-cash-coin text-info"></i>
                                        <p>Refund Requests</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.returns.index', ['status' => 'Approved']) }}"
                                        class="nav-link @if (request('status') === 'Approved') active @endif">
                                        <i class="nav-icon bi bi-check-circle text-success"></i>
                                        <p>Approved</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.returns.index', ['status' => 'Rejected']) }}"
                                        class="nav-link @if (request('status') === 'Rejected') active @endif">
                                        <i class="nav-icon bi bi-x-circle text-danger"></i>
                                        <p>Rejected</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                        @canany(['categories.view', 'categories.add'])
                            <li class="nav-item @if (Request::routeIs('categories.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('categories.*')) active @endif">
                                    <i class="nav-icon bi bi-grid"></i>
                                    <p>Categories <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('categories.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.categories.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.categories.index')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Categories View</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('categories.add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.categories.create') }}"
                                                class="nav-link @if (Request::routeIs('admin.categories.create')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Add Categories</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @canany(['brands.view', 'colors.view', 'sizes.view'])
                            <li class="nav-item @if (Request::routeIs('admin.brands.*') || Request::routeIs('admin.colors.*') || Request::routeIs('admin.sizes.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('admin.brands.*') || Request::routeIs('admin.colors.*') || Request::routeIs('admin.sizes.*')) active @endif">
                                    <i class="nav-icon bi bi-tags"></i>
                                    <p>Catalog Setup <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('brands.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.brands.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.brands.*')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Brands</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('colors.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.colors.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.colors.*')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Colors</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sizes.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.sizes.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.sizes.*')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Sizes</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        {{-- 3. Marketing & Promotions --}}
                        @canany(['coupons.view', 'coupons.add'])
                            <li class="nav-item @if (Request::routeIs('admin.coupons.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('admin.coupons.*')) active @endif">
                                    <i class="nav-icon bi bi-ticket-perforated"></i>
                                    <p>Coupons <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('coupons.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.coupons.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.coupons.index')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>View Coupons</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('coupons.add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.coupons.create') }}"
                                                class="nav-link @if (Request::routeIs('admin.coupons.create')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Add Coupon</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @canany(['discounts.view', 'discounts.add'])
                            <li class="nav-item @if (Request::routeIs('admin.discounts.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('admin.discounts.*')) active @endif">
                                    <i class="nav-icon bi bi-percent"></i>
                                    <p>Discounts (Sales) <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('discounts.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.discounts.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.discounts.index')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>View Discounts</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('discounts.add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.discounts.create') }}"
                                                class="nav-link @if (Request::routeIs('admin.discounts.create')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Add Discount</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @canany(['newsletter.view'])
                            <li class="nav-item @if (Request::routeIs('newsletter.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('newsletter.*')) active @endif">
                                    <i class="nav-icon bi bi-envelope-paper"></i>
                                    <p>Newsletter <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.newsletter.index') }}"
                                            class="nav-link @if (Request::routeIs('newsletter.index')) active @endif">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Newsletter View</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcanany

                        {{-- 4. Content Management --}}
                        @canany(['blogs.view', 'blogs.add'])
                            <li class="nav-item @if (Request::routeIs('blogs.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('blogs.*')) active @endif">
                                    <i class="nav-icon bi bi-journal-text"></i>
                                    <p>Blogs <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('blogs.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.blogs.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.blogs.index')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Blogs View</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('blogs.add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.blogs.create') }}"
                                                class="nav-link @if (Request::routeIs('admin.blogs.create')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Add Blogs</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @canany(['posts.view', 'posts.add'])
                            <li class="nav-item @if (Request::routeIs('posts.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('posts.*')) active @endif">
                                    <i class="nav-icon bi bi-file-earmark-text"></i>
                                    <p>Posts <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('posts.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.posts.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.posts.index')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Post View</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('posts.add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.posts.create') }}"
                                                class="nav-link @if (Request::routeIs('admin.posts.create')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Add Posts</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                        @can('reports.view')
                            <li class="nav-item @if (Request::routeIs('admin.reports.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('admin.reports.*')) active @endif">
                                    <i class="nav-icon bi bi-bar-chart-line"></i>
                                    <p>Reports <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reports.sales') }}"
                                            class="nav-link @if (Request::routeIs('admin.reports.sales')) active @endif">
                                            <i class="nav-icon bi bi-cash-stack"></i>
                                            <p>Sales Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reports.orders') }}"
                                            class="nav-link @if (Request::routeIs('admin.reports.orders')) active @endif">
                                            <i class="nav-icon bi bi-cart-check"></i>
                                            <p>Orders Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reports.products') }}"
                                            class="nav-link @if (Request::routeIs('admin.reports.products')) active @endif">
                                            <i class="nav-icon bi bi-box-seam"></i>
                                            <p>Products Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reports.customers') }}"
                                            class="nav-link @if (Request::routeIs('admin.reports.customers')) active @endif">
                                            <i class="nav-icon bi bi-people"></i>
                                            <p>Customer Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reports.stock') }}"
                                            class="nav-link @if (Request::routeIs('admin.reports.stock')) active @endif">
                                            <i class="nav-icon bi bi-clipboard-data"></i>
                                            <p>Stock Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reports.revenue') }}"
                                            class="nav-link @if (Request::routeIs('admin.reports.revenue')) active @endif">
                                            <i class="nav-icon bi bi-graph-up-arrow"></i>
                                            <p>Revenue Report</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @canany(['pages.view', 'pages.add'])
                            <li class="nav-item @if (Request::routeIs('pages.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('pages.*')) active @endif">
                                    <i class="nav-icon bi bi-file-earmark"></i>
                                    <p>Pages <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('pages.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.pages.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.pages.index')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Pages View</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('pages.add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.pages.create') }}"
                                                class="nav-link @if (Request::routeIs('admin.pages.create')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Add Pages</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @canany(['notes.view', 'notes.add'])
                        <li class="nav-item @if (Request::routeIs('admin.notes.*')) menu-open @endif">
                            <a href="#" class="nav-link @if (Request::routeIs('admin.notes.*')) active @endif">
                                <i class="nav-icon bi bi-journal-richtext"></i>
                                <p>Short Notes <i class="nav-arrow bi bi-chevron-right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('notes.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.notes.index') }}"
                                            class="nav-link @if (Request::routeIs('admin.notes.index')) active @endif">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>View Notes</p>
                                        </a>
                                    </li>
                                @endcan
                                @can('notes.add')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.notes.create') }}"
                                            class="nav-link @if (Request::routeIs('admin.notes.create')) active @endif">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>Add Note</p>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                        @canany(['menus.view', 'menus.add'])
                            <li class="nav-item @if (Request::routeIs('menus.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('menus.*')) active @endif">
                                    <i class="nav-icon bi bi-list-ul"></i>
                                    <p>Menus <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('menus.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.menus.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.menus.index')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Menus View</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('menus.add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.menus.createMenu') }}"
                                                class="nav-link @if (Request::routeIs('admin.menus.createMenu')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Add Menus</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        {{-- 5. User Management --}}
                        @can('customers.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.customers.index') }}"
                                    class="nav-link @if (Request::routeIs('admin.customers.*')) active @endif">
                                    <i class="nav-icon bi bi-person-badge"></i>
                                    <p>Customers List</p>
                                </a>
                            </li>
                        @endcan

                        @can('posts.add')
                            <li class="nav-item">
                                <a href="{{ route('admin.social.create') }}"
                                    class="nav-link @if (Request::routeIs('admin.social.*')) active @endif">
                                    <i class="nav-icon bi bi-share"></i>
                                    <p>Social Media Post</p>
                                </a>
                            </li>
                        @endcan
                        @can('media.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.media.index') }}"
                                    class="nav-link @if (Request::routeIs('admin.media.*')) active @endif">
                                    <i class="nav-icon bi bi-images"></i>
                                    <p>Media Manager</p>
                                </a>
                            </li>
                        @endcan

                        @canany(['staffs.view', 'staffs.add'])
                            <li class="nav-item @if (Request::routeIs('staffs.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('staffs.*')) active @endif">
                                    <i class="nav-icon bi bi-people"></i>
                                    <p>Staffs <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('staffs.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.staffs.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.staffs.index')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Staffs View</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('staffs.add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.staffs.create') }}"
                                                class="nav-link @if (Request::routeIs('admin.staffs.create')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Add Staff</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @canany(['roles.view', 'roles.add'])
                            <li class="nav-item @if (Request::routeIs('roles.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('roles.*')) active @endif">
                                    <i class="nav-icon bi bi-person-gear"></i>
                                    <p>Roles <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('roles.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.roles.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.roles.index')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>View Roles</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('roles.add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.roles.create') }}"
                                                class="nav-link @if (Request::routeIs('admin.roles.create')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Add Role</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @canany(['permissions.view', 'permissions.add'])
                            <li class="nav-item @if (Request::routeIs('permissions.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('permissions.*')) active @endif">
                                    <i class="nav-icon bi bi-shield-lock"></i>
                                    <p>Permissions <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('permissions.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.permissions.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.permissions.index')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Permissions View</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('permissions.add')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.permissions.create') }}"
                                                class="nav-link @if (Request::routeIs('admin.permissions.create')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Add Permission</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        {{-- 6. System Settings & Tools --}}
                        @can('email.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.smtp.index') }}"
                                    class="nav-link @if (Request::routeIs('admin.smtp.index')) active @endif">
                                    <i class="nav-icon bi bi-envelope-check"></i>
                                    <p>Email Settings (SMTP)</p>
                                </a>
                            </li>
                        @endcan

                        @canany(['logs.view', 'backups.view'])
                            <li class="nav-item @if (Request::routeIs('admin.logs.*') || Request::routeIs('admin.backups.*')) menu-open @endif">
                                <a href="#" class="nav-link @if (Request::routeIs('admin.logs.*') || Request::routeIs('admin.backups.*')) active @endif">
                                    <i class="nav-icon bi bi-shield-check"></i>
                                    <p>Security & System <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('logs.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.logs.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.logs.*')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Activity & Login Logs</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('backups.view')
                                        <li class="nav-item">
                                            <a href="{{ route('admin.backups.index') }}"
                                                class="nav-link @if (Request::routeIs('admin.backups.*')) active @endif">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Database Backups</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @can('settings.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.index') }}"
                                    class="nav-link @if (Request::routeIs('admin.settings.*')) active @endif">
                                    <i class="nav-icon bi bi-gear"></i>
                                    <p>Settings</p>
                                </a>
                            </li>
                        @endcan
                        @canany(['payment_gateways.view'])
                        <li class="nav-item @if (Request::routeIs('admin.payment-gateways.*')) menu-open @endif">
                            <a href="#" class="nav-link @if (Request::routeIs('admin.payment-gateways.*')) active @endif">
                                <i class="nav-icon bi bi-credit-card"></i>
                                <p>
                                    Payment Gateways
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.payment-gateways.index') }}"
                                        class="nav-link @if (Request::routeIs('admin.payment-gateways.*')) active @endif">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Manage Gateways</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcanany

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-menu-button-wide"></i>
                                <p>
                                    Menu Options
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>


                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.generate.theme') }}" class="nav-link">
                                        <i class="nav-icon bi bi-palette"></i>
                                        <p>Theme Generate</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-box-seam-fill"></i>
                                        <p>
                                            Widgets
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('admin.widgets.small-box') }}" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Small Box</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.widgets.info-box') }}" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>info Box</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.widgets.cards') }}" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Cards</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-clipboard-fill"></i>
                                        <p>
                                            Layout Options
                                            <span class="nav-badge badge text-bg-secondary me-3">6</span>
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="./layout/unfixed-sidebar.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Default Sidebar</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./layout/fixed-sidebar.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Fixed Sidebar</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./layout/layout-custom-area.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Layout <small>+ Custom Area </small></p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./layout/sidebar-mini.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Sidebar Mini</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./layout/collapsed-sidebar.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Sidebar Mini <small>+ Collapsed</small></p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./layout/logo-switch.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Sidebar Mini <small>+ Logo Switch</small></p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./layout/layout-rtl.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Layout RTL</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-tree-fill"></i>
                                        <p>
                                            UI Elements
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="./UI/general.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>General</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./UI/icons.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Icons</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./UI/timeline.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Timeline</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-pencil-square"></i>
                                        <p>
                                            Forms
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('admin.forms.general') }}" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>General Elements</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-table"></i>
                                        <p>
                                            Tables
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('admin.tables.simple') }}" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Simple Tables</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-header">EXAMPLES</li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-box-arrow-in-right"></i>
                                        <p>
                                            Auth
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="nav-icon bi bi-box-arrow-in-right"></i>
                                                <p>
                                                    Version 1
                                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                                <li class="nav-item">
                                                    <a href="./examples/login.html" class="nav-link">
                                                        <i class="nav-icon bi bi-circle"></i>
                                                        <p>Login</p>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="./examples/register.html" class="nav-link">
                                                        <i class="nav-icon bi bi-circle"></i>
                                                        <p>Register</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="nav-icon bi bi-box-arrow-in-right"></i>
                                                <p>
                                                    Version 2
                                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                                <li class="nav-item">
                                                    <a href="./examples/login-v2.html" class="nav-link">
                                                        <i class="nav-icon bi bi-circle"></i>
                                                        <p>Login</p>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="./examples/register-v2.html" class="nav-link">
                                                        <i class="nav-icon bi bi-circle"></i>
                                                        <p>Register</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./examples/lockscreen.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Lockscreen</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-header">DOCUMENTATIONS</li>
                                <li class="nav-item">
                                    <a href="./docs/introduction.html" class="nav-link">
                                        <i class="nav-icon bi bi-download"></i>
                                        <p>Installation</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./docs/layout.html" class="nav-link">
                                        <i class="nav-icon bi bi-grip-horizontal"></i>
                                        <p>Layout</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./docs/color-mode.html" class="nav-link">
                                        <i class="nav-icon bi bi-star-half"></i>
                                        <p>Color Mode</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-ui-checks-grid"></i>
                                        <p>
                                            Components
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="./docs/components/main-header.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Main Header</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="./docs/components/main-sidebar.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Main Sidebar</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-filetype-js"></i>
                                        <p>
                                            Javascript
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="./docs/javascript/treeview.html" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Treeview</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="./docs/browser-support.html" class="nav-link">
                                        <i class="nav-icon bi bi-browser-edge"></i>
                                        <p>Browser Support</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./docs/how-to-contribute.html" class="nav-link">
                                        <i class="nav-icon bi bi-hand-thumbs-up-fill"></i>
                                        <p>How To Contribute</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.docs.faq') }}" class="nav-link">
                                        <i class="nav-icon bi bi-question-circle-fill"></i>
                                        <p>FAQ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./docs/license.html" class="nav-link">
                                        <i class="nav-icon bi bi-patch-check-fill"></i>
                                        <p>License</p>
                                    </a>
                                </li>
                                <li class="nav-header">MULTI LEVEL EXAMPLE</li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-circle-fill"></i>
                                        <p>Level 1</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-circle-fill"></i>
                                        <p>
                                            Level 1
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Level 2</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>
                                                    Level 2
                                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link">
                                                        <i class="nav-icon bi bi-record-circle-fill"></i>
                                                        <p>Level 3</p>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link">
                                                        <i class="nav-icon bi bi-record-circle-fill"></i>
                                                        <p>Level 3</p>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link">
                                                        <i class="nav-icon bi bi-record-circle-fill"></i>
                                                        <p>Level 3</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="nav-icon bi bi-circle"></i>
                                                <p>Level 2</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-circle-fill"></i>
                                        <p>Level 1</p>
                                    </a>
                                </li>
                                <li class="nav-header">LABELS</li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-circle text-danger"></i>
                                        <p class="text">Important</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-circle text-warning"></i>
                                        <p>Warning</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-circle text-info"></i>
                                        <p>Informational</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>




        <!-- Content Section -->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <div class="content-header">
                    <!-- <div class="container-fluid"> -->
                    @yield('content')

                    <!-- </div> -->
                </div>
            </div>
        </main>
        <!-- Footer -->
        <footer>
            @yield('footer')
        </footer>

        <!-- Main content inside end-->

        <!-- Start Footer -->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">Anything you want</div>
            <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Copyright &copy; 2025-2026&nbsp;
                <a href="https://piyushkumawat.in" class="text-decoration-none">Super piyushkumawat.in</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(Super Admin)-->
    {{-- <script src="../../dist/js/adminlte.js"></script> --}}
    <script src="{{ asset('public/assets/admin/js/adminlte.js') }}"></script>

    <!--end::Required Plugin(Super Admin)-->
    <!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- sortablejs -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
        integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ=" crossorigin="anonymous"></script>
    <!-- sortablejs -->
    <script>
        const connectedSortables = document.querySelectorAll('.connectedSortable');
        connectedSortables.forEach((connectedSortable) => {
            let sortable = new Sortable(connectedSortable, {
                group: 'shared',
                handle: '.card-header',
            });
        });

        const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
        cardHeaders.forEach((cardHeader) => {
            cardHeader.style.cursor = 'move';
        });
    </script>
    <!-- apexcharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
    <!-- ChartJS -->
    <script>
        // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
        // IT'S ALL JUST JUNK FOR DEMO
        // ++++++++++++++++++++++++++++++++++++++++++

        const sales_chart_options = {
            series: [{
                    name: 'Digital Goods',
                    data: [28, 48, 40, 19, 86, 27, 90],
                },
                {
                    name: 'Electronics',
                    data: [65, 59, 80, 81, 56, 55, 40],
                },
            ],
            chart: {
                height: 300,
                type: 'area',
                toolbar: {
                    show: false,
                },
            },
            legend: {
                show: false,
            },
            colors: ['#0d6efd', '#20c997'],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: 'smooth',
            },
            xaxis: {
                type: 'datetime',
                categories: [
                    '2023-01-01',
                    '2023-02-01',
                    '2023-03-01',
                    '2023-04-01',
                    '2023-05-01',
                    '2023-06-01',
                    '2023-07-01',
                ],
            },
            tooltip: {
                x: {
                    format: 'MMMM yyyy',
                },
            },
        };

        const sales_chart = new ApexCharts(
            document.querySelector('#revenue-chart'),
            sales_chart_options,
        );
        sales_chart.render();
    </script>
    <!-- jsvectormap -->
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
        integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
        integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script>
    <!-- jsvectormap -->
    <script>
        const visitorsData = {
            US: 398, // USA
            SA: 400, // Saudi Arabia
            CA: 1000, // Canada
            DE: 500, // Germany
            FR: 760, // France
            CN: 300, // China
            AU: 700, // Australia
            BR: 600, // Brazil
            IN: 800, // India
            GB: 320, // Great Britain
            RU: 3000, // Russia
        };

        // World map by jsVectorMap
        const map = new jsVectorMap({
            selector: '#world-map',
            map: 'world',
        });

        // Sparkline charts
        const option_sparkline1 = {
            series: [{
                data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
            }, ],
            chart: {
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: 'straight',
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ['#DCE6EC'],
        };

        const sparkline1 = new ApexCharts(document.querySelector('#sparkline-1'), option_sparkline1);
        sparkline1.render();

        const option_sparkline2 = {
            series: [{
                data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
            }, ],
            chart: {
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: 'straight',
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ['#DCE6EC'],
        };

        const sparkline2 = new ApexCharts(document.querySelector('#sparkline-2'), option_sparkline2);
        sparkline2.render();

        const option_sparkline3 = {
            series: [{
                data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
            }, ],
            chart: {
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: 'straight',
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ['#DCE6EC'],
        };

        const sparkline3 = new ApexCharts(document.querySelector('#sparkline-3'), option_sparkline3);
        sparkline3.render();
    </script>


    <script>
        // 4 Second baad message gayab karne ke liye
        setTimeout(function() {
            let alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.transition = "opacity 1s ease-out";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 1000); // Remove from DOM
            }
        }, 4000);
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Success Alert
            let successAlert = document.getElementById('success-alert');
            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.transition = "opacity 0.5s ease";
                    successAlert.style.opacity = "0";
                    setTimeout(() => successAlert.remove(), 500);
                }, 4000);
            }

            // Error Alert
            let errorAlert = document.getElementById('error-alert');
            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.style.transition = "opacity 0.5s ease";
                    errorAlert.style.opacity = "0";
                    setTimeout(() => errorAlert.remove(), 500);
                }, 4000);
            }
        });
    </script>
    <!--end::Script-->
</body>
<!--end::Body-->

</html>
