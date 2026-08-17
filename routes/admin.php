<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BlogsController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\ReturnController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::controller(AdminController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('admin.login');
    Route::post('/login', 'login');
});

// Authenticated Routes (Wrapped in Admin Prefix & Name)
Route::middleware(['auth'])->name('admin.')->group(function () {


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/{role}/dashboard', [DashboardController::class, 'routeToDashboard'])->name('role.dashboard');
    Route::get('/notifications/mark-as-read', [DashboardController::class, 'markNotificationsAsRead'])->name('notifications.read');

    Route::post('products/bulk-update', [ProductController::class, 'bulkUpdate'])->name('products.bulkUpdate');
    Route::post('orders/bulk-status', [OrderController::class, 'bulkStatusUpdate'])->name('orders.bulkStatus');
    // SECURED UPDATE CODE ROUTE
    Route::post('/update-code', function () {
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('optimize:clear');

            return redirect()->back()->with('success', 'System updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating code: '.$e->getMessage());
        }
    })->middleware('role:admin')->name('update-code');

    // Admin & Staff Management
    Route::controller(AdminController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');

        // Staffs (Name becomes: admin.staffs.index)
        Route::prefix('staffs')->name('staffs.')->group(function () {
            Route::get('/', 'StaffsIndex')->middleware('can:staffs.view')->name('index');
            Route::get('/create', 'StaffsCreate')->middleware('can:staffs.add')->name('create');
            Route::post('/', 'StaffsStore')->middleware('can:staffs.add')->name('store');
            Route::get('/{id}/edit', 'StaffsEdit')->middleware('can:staffs.edit')->name('edit');
            Route::put('/{id}', 'StaffsUpdate')->middleware('can:staffs.edit')->name('update');
        });

        // Customers (Name becomes: admin.customers.index)
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', 'CustomersIndex')->middleware('can:customers.view')->name('index');
            Route::get('/{id}/edit', 'CustomersEdit')->middleware('can:customers.edit')->name('edit');
            Route::put('/{id}', 'CustomersUpdate')->middleware('can:customers.edit')->name('update');
            Route::delete('/{id}', 'CustomersDestroy')->middleware('can:customers.delete')->name('destroy');
        });
    });

    // Roles
    Route::controller(RoleController::class)->prefix('roles')->name('roles.')->group(function () {
        Route::get('/', 'index')->middleware('can:roles.view')->name('index');
        Route::get('/create', 'create')->middleware('can:roles.add')->name('create');
        Route::post('/', 'store')->middleware('can:roles.add')->name('store');
    });

    // Permissions
    Route::controller(PermissionController::class)->prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', 'index')->middleware('can:permissions.view')->name('index');
        Route::get('/create', 'create')->middleware('can:permissions.add')->name('create');
        Route::post('/', 'store')->middleware('can:permissions.add')->name('store');
        Route::get('/{permission}/edit', 'edit')->middleware('can:permissions.edit')->name('edit');
        Route::post('/{permission}/toggle-status', 'toggleStatus')->middleware('can:permissions.edit')->name('toggle_status');
        Route::put('/{permission}', 'update')->middleware('can:permissions.edit')->name('update');
        Route::delete('/{permission}', 'destroy')->middleware('can:permissions.delete')->name('destroy');
    });

    // Products & Stock
    Route::controller(ProductController::class)->group(function () {
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', 'index')->middleware('can:products.view')->name('index');
            Route::get('/create', 'create')->middleware('can:products.add')->name('create');
            Route::post('/', 'store')->middleware('can:products.add')->name('store');
            Route::get('/{id}/edit', 'edit')->middleware('can:products.edit')->name('edit');
            Route::put('/{id}', 'update')->middleware('can:products.edit')->name('update');
            Route::delete('/{id}', 'destroy')->middleware('can:products.delete')->name('destroy');
        });
        Route::prefix('stock')->name('stock.')->middleware('can:products.edit')->group(function () {
            Route::get('/', 'stockIndex')->name('index');
            Route::get('/{id}/edit', 'stockEdit')->name('edit');
            Route::put('/{id}', 'stockUpdate')->name('update');
        });
    });

    // Categories
    Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
        Route::get('/', 'index')->middleware('can:categories.view')->name('index');
        Route::get('/create', 'create')->middleware('can:categories.add')->name('create');
        Route::post('/store', 'store')->middleware('can:categories.add')->name('store');
        Route::get('/{id}/edit', 'edit')->middleware('can:categories.edit')->name('edit');
        Route::put('/{id}', 'update')->middleware('can:categories.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('can:categories.delete')->name('destroy');
    });

    // Brands
    Route::controller(BrandController::class)->prefix('brands')->name('brands.')->group(function () {
        Route::get('/', 'index')->middleware('can:brands.view')->name('index');
        Route::get('/create', 'create')->middleware('can:brands.add')->name('create');
        Route::post('/', 'store')->middleware('can:brands.add')->name('store');
        Route::get('/{id}/edit', 'edit')->middleware('can:brands.edit')->name('edit');
        Route::put('/{id}', 'update')->middleware('can:brands.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('can:brands.delete')->name('destroy');
    });

    // Colors
    Route::controller(ColorController::class)->prefix('colors')->name('colors.')->group(function () {
        Route::get('/', 'index')->middleware('can:colors.view')->name('index');
        Route::get('/create', 'create')->middleware('can:colors.add')->name('create');
        Route::post('/', 'store')->middleware('can:colors.add')->name('store');
        Route::get('/{id}/edit', 'edit')->middleware('can:colors.edit')->name('edit');
        Route::put('/{id}', 'update')->middleware('can:colors.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('can:colors.delete')->name('destroy');
    });

    // Sizes
    Route::controller(SizeController::class)->prefix('sizes')->name('sizes.')->group(function () {
        Route::get('/', 'index')->middleware('can:sizes.view')->name('index');
        Route::get('/create', 'create')->middleware('can:sizes.add')->name('create');
        Route::post('/', 'store')->middleware('can:sizes.add')->name('store');
        Route::get('/{id}/edit', 'edit')->middleware('can:sizes.edit')->name('edit');
        Route::put('/{id}', 'update')->middleware('can:sizes.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('can:sizes.delete')->name('destroy');
    });

    // Orders
    Route::controller(OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->middleware('can:orders.view')->name('index');
        Route::get('/{id}', 'show')->middleware('can:orders.view')->name('show');
        Route::put('/{id}/status', 'updateStatus')->middleware('can:orders.edit')->name('update_status');
    });
Route::controller(ReturnController::class)->prefix('returns')->name('returns.')->group(function () {
    Route::get('/', 'index')->middleware('can:returns.view')->name('index');
    Route::get('/{id}', 'show')->middleware('can:returns.view')->name('show');
    Route::post('/{id}/approve', 'approve')->middleware('can:returns.edit')->name('approve');
    Route::post('/{id}/reject', 'reject')->middleware('can:returns.edit')->name('reject');
    Route::post('/{id}/refund-manual', 'refundManual')->middleware('can:returns.edit')->name('refund_manual');
    Route::post('/{id}/refund-gateway', 'refundGateway')->middleware('can:returns.edit')->name('refund_gateway'); // NEW
});
    // Reviews
    Route::controller(ReviewController::class)->prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', 'index')->middleware('can:reviews.view')->name('index');
        Route::get('/create', 'create')->middleware('can:reviews.add')->name('create');
        Route::post('/store', 'store')->middleware('can:reviews.add')->name('store');
        Route::get('/{id}/edit', 'edit')->middleware('can:reviews.edit')->name('edit');
        Route::put('/{id}', 'update')->middleware('can:reviews.edit')->name('update');
        Route::post('/{id}/toggle-status', 'toggleStatus')->middleware('can:reviews.edit')->name('toggle_status');
        Route::post('/{id}/toggle-spam', 'toggleSpam')->middleware('can:reviews.edit')->name('toggle_spam');
        Route::delete('/{id}', 'destroy')->middleware('can:reviews.delete')->name('destroy');
    });

    // Posts
    Route::controller(PostController::class)->prefix('posts')->name('posts.')->group(function () {
        Route::get('/', 'index')->middleware('can:posts.view')->name('index');
        Route::get('/create', 'create')->middleware('can:posts.add')->name('create');
        Route::post('/store', 'store')->middleware('can:posts.add')->name('store');
        Route::get('/{id}/edit', 'edit')->middleware('can:posts.edit')->name('edit');
        Route::put('/{id}', 'update')->middleware('can:posts.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('can:posts.delete')->name('destroy');
    });

    // Blogs
    Route::controller(BlogsController::class)->prefix('blogs')->name('blogs.')->group(function () {
        Route::get('/', 'index')->middleware('can:blogs.view')->name('index');
        Route::get('/create', 'create')->middleware('can:blogs.add')->name('create');
        Route::post('/', 'store')->middleware('can:blogs.add')->name('store');
        Route::get('/{id}/edit', 'edit')->middleware('can:blogs.edit')->name('edit');
        Route::put('/{id}', 'update')->middleware('can:blogs.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('can:blogs.delete')->name('destroy');
        Route::post('/{id}/status', 'updateStatus')->middleware('can:blogs.status')->name('status');
    });

    // Menus
    Route::controller(MenuController::class)->prefix('menus')->name('menus.')->group(function () {
        Route::get('/', 'index')->middleware('can:menus.view')->name('index');
        Route::get('/create', 'createMenu')->middleware('can:menus.add')->name('createMenu');
        Route::post('/store', 'store')->middleware('can:menus.add')->name('store');
        Route::get('/{id}/edit', 'edit')->middleware('can:menus.edit')->name('edit');
        Route::put('/{id}', 'update')->middleware('can:menus.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('can:menus.delete')->name('destroy');
        Route::post('/reorder', 'reorder')->middleware('can:menus.add')->name('reorder');
        Route::post('/add-pages', 'addPagesToMenu')->middleware('can:menus.add')->name('addPages');
    });

    // Coupons
    Route::controller(CouponController::class)->prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', 'index')->middleware('can:coupons.view')->name('index');
        Route::get('/create', 'create')->middleware('can:coupons.add')->name('create');
        Route::post('/', 'store')->middleware('can:coupons.add')->name('store');
        Route::get('/{id}/edit', 'edit')->middleware('can:coupons.edit')->name('edit');
        Route::put('/{id}', 'update')->middleware('can:coupons.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('can:coupons.delete')->name('destroy');
    });

    // Discounts
    Route::controller(DiscountController::class)->prefix('discounts')->name('discounts.')->group(function () {
        Route::get('/search-products', 'searchProducts')->name('searchProducts');
        Route::get('/', 'index')->middleware('can:discounts.view')->name('index');
        Route::get('/create', 'create')->middleware('can:discounts.add')->name('create');
        Route::post('/', 'store')->middleware('can:discounts.add')->name('store');
        Route::get('/{id}/edit', 'edit')->middleware('can:discounts.edit')->name('edit');
        Route::put('/{id}', 'update')->middleware('can:discounts.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('can:discounts.delete')->name('destroy');
    });

    // Newsletters
    Route::controller(NewsletterController::class)->prefix('newsletters')->name('newsletter.')->middleware('can:newsletter.view')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/unsubscribe/{id}', 'unsubscribe')->name('unsubscribe');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::post('/{id}/update', 'update')->name('update');
        Route::get('/delete/{id}', 'destroy')->name('delete');
        Route::get('/show', 'showindex')->name('show');
        Route::post('/send', 'sendNewsletter')->name('send');
    });

    // System Components (Logs, Backups, SMTP, Settings)
    Route::get('/logs', [ActivityLogController::class, 'index'])->middleware('can:logs.view')->name('logs.index');
    Route::delete('/logs/clear', [ActivityLogController::class, 'clear'])->middleware('can:logs.delete')->name('logs.clear');

    Route::controller(BackupController::class)->prefix('backups')->name('backups.')->group(function () {
        Route::get('/', 'index')->middleware('can:backups.view')->name('index');
        Route::post('/generate', 'generate')->middleware('can:backups.add')->name('generate');
        Route::get('/download/{fileName}', 'download')->middleware('can:backups.view')->name('download');
        Route::delete('/{fileName}', 'destroy')->middleware('can:backups.delete')->name('destroy');
    });

    Route::controller(EmailController::class)->middleware('can:email.view')->name('smtp.')->group(function () {
        Route::get('/smtp-settings', 'smtpSettings')->name('index');
        Route::post('/smtp-update-settings', 'updateSmtpSettings')->name('update');
        Route::post('/smtp/test', 'testSmtp')->name('test');
    });

    Route::controller(SettingController::class)->prefix('settings')->name('settings.')->group(function () {
        Route::get('/', 'index')->middleware('can:settings.view')->name('index');
        Route::post('/', 'update')->middleware('can:settings.edit')->name('update');
    });


    // Payment Gateways
    Route::controller(PaymentGatewayController::class)->prefix('payment-gateways')->name('payment-gateways.')->group(function () {
        Route::get('/', 'index')->middleware('can:payment_gateways.view')->name('index');
        Route::get('/{id}/edit', 'edit')->middleware('can:payment_gateways.edit')->name('edit');
        Route::put('/{id}', 'update')->middleware('can:payment_gateways.edit')->name('update');
        Route::post('/{id}/toggle-status', 'toggleStatus')->middleware('can:payment_gateways.edit')->name('toggle_status');
    });
    // Pages (CMS)
    Route::controller(PageController::class)->prefix('pages')->name('pages.')->group(function () {
        Route::get('/', 'index')->middleware('can:pages.view')->name('index');
        Route::get('/create', 'create')->middleware('can:pages.add')->name('create');
        Route::post('/', 'store')->middleware('can:pages.add')->name('store');
        Route::get('/{id}/edit', 'edit')->middleware('can:pages.edit')->name('edit');
        Route::put('/{id}', 'update')->middleware('can:pages.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('can:pages.delete')->name('destroy');
    });


    Route::controller(ReportController::class)->prefix('reports')->name('reports.')->group(function () {
    Route::get('/sales',     'sales')->middleware('can:reports.view')->name('sales');
    Route::get('/orders',    'orders')->middleware('can:reports.view')->name('orders');
    Route::get('/products',  'products')->middleware('can:reports.view')->name('products');
    Route::get('/customers', 'customers')->middleware('can:reports.view')->name('customers');
    Route::get('/stock',     'stock')->middleware('can:reports.view')->name('stock');
    Route::get('/revenue',   'revenue')->middleware('can:reports.view')->name('revenue');
});
    // Short Notes Module
    Route::controller(NoteController::class)->prefix('notes')->name('notes.')->group(function () {
        Route::get('/', 'index')->middleware('can:notes.view')->name('index');
        Route::get('/create', 'create')->middleware('can:notes.add')->name('create');
        Route::post('/', 'store')->middleware('can:notes.add')->name('store');
        Route::get('/{id}/edit', 'edit')->middleware('can:notes.edit')->name('edit');
        Route::put('/{id}', 'update')->middleware('can:notes.edit')->name('update');
        Route::delete('/{id}', 'destroy')->middleware('can:notes.delete')->name('destroy');
    });
    Route::controller(MediaController::class)->prefix('media')->name('media.')->group(function () {
        Route::get('/', 'index')->middleware('can:media.view')->name('index');
        Route::delete('/delete', 'destroy')->middleware('can:media.delete')->name('destroy');
    });

    Route::controller(SocialMediaController::class)->prefix('social')->name('social.')->group(function () {
        Route::get('/post', 'create')->middleware('can:posts.add')->name('create');
        Route::post('/post', 'store')->middleware('can:posts.add')->name('store');
    });
    // Theme, Widgets & UI Pages
    Route::controller(PageController::class)->group(function () {
        // '/admin/contact' ki jagah sirf '/contact' (kyunki '/admin' global ho gaya hai)
        Route::get('/contact', 'contact')->name('contact');
        Route::get('/generate/theme', 'theme')->name('generate.theme');
        Route::get('/widgets/small-box', 'smallBox')->name('widgets.small-box');
        Route::get('/widgets/info-box', 'infoBox')->name('widgets.info-box');
        Route::get('/widgets/cards', 'cards')->name('widgets.cards');
        Route::get('/layout/unfixed-sidebar', 'unfixedSidebar')->name('layout.unfixed-sidebar');
        Route::get('/layout/fixed-sidebar', 'fixedSidebar')->name('layout.fixed-sidebar');
        Route::get('/layout/custom-area', 'customArea')->name('layout.custom-area');
        Route::get('/layout/sidebar-mini', 'sidebarMini')->name('layout.sidebar-mini');
        Route::get('/layout/collapsed-sidebar', 'collapsedSidebar')->name('layout.collapsed-sidebar');
        Route::get('/layout/logo-switch', 'logoSwitch')->name('layout.logo-switch');
        Route::get('/layout/layout-rtl', 'layoutRtl')->name('layout.rtl');
        Route::get('/UI/general', 'generalUI')->name('ui.general');
        Route::get('/UI/icons', 'icons')->name('ui.icons');
        Route::get('/UI/timeline', 'timeline')->name('ui.timeline');
        Route::get('/forms/general', 'generalForms')->name('forms.general');
        Route::get('/tables/simple', 'simpleTables')->name('tables.simple');
        Route::get('/examples/login', 'login')->name('examples.login');
        Route::get('/examples/register', 'register')->name('examples.register');
        Route::get('/examples/login-v2', 'loginV2')->name('examples.login-v2');
        Route::get('/examples/register-v2', 'registerV2')->name('examples.register-v2');
        Route::get('/examples/lockscreen', 'lockscreen')->name('examples.lockscreen');
        Route::get('/docs/introduction', 'docsIntroduction')->name('docs.introduction');
        Route::get('/docs/color-mode', 'docsColorMode')->name('docs.color-mode');
        Route::get('/docs/components/main-header', 'mainHeader')->name('docs.main-header');
        Route::get('/docs/components/main-sidebar', 'mainSidebar')->name('docs.main-sidebar');
        Route::get('/docs/javascript/treeview', 'treeView')->name('docs.treeview');
        Route::get('/docs/browser-support', 'browserSupport')->name('docs.browser-support');
        Route::get('/docs/how-to-contribute', 'howToContribute')->name('docs.how-to-contribute');
        Route::get('/docs/faq', 'faq')->name('docs.faq');
        Route::get('/docs/license', 'license')->name('docs.license');
    });
});
