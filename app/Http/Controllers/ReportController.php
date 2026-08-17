<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * "Valid" orders = paisa-banne-wale orders.
     * Paid (online) + COD orders, cancelled chhod ke.
     * Apne business hisaab se yahan logic badal sakte ho.
     */
    private function validOrders()
    {
        return Order::where(function ($q) {
            $q->where('payment_status', 'Paid')
              ->orWhere('payment_method', 'COD');
        })->where('order_status', '!=', 'Cancelled');
    }

    // Date range nikalne ka common helper (default last 30 days)
    private function dateRange(Request $request)
    {
        $from = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();

        $to = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : Carbon::now()->endOfDay();

        return [$from, $to];
    }

    // Activity log helper
    private function log($name, $from, $to)
    {
        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'View',
            'module'      => 'Reports',
            'description' => "Viewed {$name} ({$from->toDateString()} to {$to->toDateString()})",
            'ip_address'  => request()->ip(),
        ]);
    }

    // ==========================================
    // 1. SALES REPORT
    // ==========================================
    public function sales(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $base = $this->validOrders()->whereBetween('created_at', [$from, $to]);

        $totalSales  = (clone $base)->sum('total_amount');
        $totalOrders = (clone $base)->count();
        $avgOrder    = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        $daily = (clone $base)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount) as sales')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->get();

        $this->log('Sales Report', $from, $to);

        return view('admin.reports.sales', compact('totalSales', 'totalOrders', 'avgOrder', 'daily', 'from', 'to'));
    }

    // ==========================================
    // 2. ORDERS REPORT
    // ==========================================
    public function orders(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $base = Order::whereBetween('created_at', [$from, $to]);

        $totalOrders = (clone $base)->count();

        // Payment status breakdown
        $byPaymentStatus = (clone $base)
            ->select('payment_status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as amount'))
            ->groupBy('payment_status')
            ->get();

        // Order status breakdown
        $byOrderStatus = (clone $base)
            ->select('order_status', DB::raw('COUNT(*) as count'))
            ->groupBy('order_status')
            ->get();

        // Payment method breakdown
        $byMethod = (clone $base)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as amount'))
            ->groupBy('payment_method')
            ->get();

        // Recent orders list
        $recentOrders = (clone $base)->latest()->limit(50)->get();

        $this->log('Orders Report', $from, $to);

        return view('admin.reports.orders', compact(
            'totalOrders', 'byPaymentStatus', 'byOrderStatus', 'byMethod', 'recentOrders', 'from', 'to'
        ));
    }

    // ==========================================
    // 3. PRODUCTS REPORT (best / least selling)
    // ==========================================
    public function products(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        // Valid order ids in range
        $orderIds = $this->validOrders()->whereBetween('created_at', [$from, $to])->pluck('id');

        // Best selling (by qty)
        $bestSelling = OrderItem::whereIn('order_id', $orderIds)
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(quantity * price) as total_revenue')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(20)
            ->get();

        // Product names attach karo
        $productMap = Product::whereIn('id', $bestSelling->pluck('product_id'))->get()->keyBy('id');

        $this->log('Products Report', $from, $to);

        return view('admin.reports.products', compact('bestSelling', 'productMap', 'from', 'to'));
    }

    // ==========================================
    // 4. CUSTOMER REPORT (top customers)
    // ==========================================
    public function customers(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $base = $this->validOrders()->whereBetween('created_at', [$from, $to]);

        // Top customers by spend (user_id wise; guest orders ka user_id null hota hai)
        $topCustomers = (clone $base)
            ->whereNotNull('user_id')
            ->select(
                'user_id',
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount) as spent')
            )
            ->groupBy('user_id')
            ->orderByDesc('spent')
            ->limit(20)
            ->get();

        $userMap = User::whereIn('id', $topCustomers->pluck('user_id'))->get()->keyBy('id');

        // New customers in range
        $newCustomers = User::whereBetween('created_at', [$from, $to])->count();

        // Guest orders count
        $guestOrders = (clone $base)->whereNull('user_id')->count();

        $this->log('Customer Report', $from, $to);

        return view('admin.reports.customers', compact(
            'topCustomers', 'userMap', 'newCustomers', 'guestOrders', 'from', 'to'
        ));
    }

    // ==========================================
    // 5. STOCK REPORT (inventory) -- date filter nahi, current snapshot
    // ==========================================
    public function stock(Request $request)
    {
        $lowStockThreshold = 5;

        // Main product stock
        $outOfStock = Product::where('stock', '<=', 0)->count();
        $lowStock   = Product::where('stock', '>', 0)->where('stock', '<=', $lowStockThreshold)->count();
        $inStock    = Product::where('stock', '>', $lowStockThreshold)->count();

        // Low/out products list (main stock)
        $lowProducts = Product::where('stock', '<=', $lowStockThreshold)
            ->orderBy('stock', 'asc')
            ->limit(50)
            ->get();

        // Low/out variations list
        $lowVariations = ProductVariation::where('stock', '<=', $lowStockThreshold)
            ->orderBy('stock', 'asc')
            ->limit(50)
            ->get();

        $variationProductMap = Product::whereIn('id', $lowVariations->pluck('product_id'))->get()->keyBy('id');

        // Inventory value (main stock * price)
        $inventoryValue = Product::sum(DB::raw('stock * price'));

        // For stock, date range bas log ke liye
        [$from, $to] = $this->dateRange($request);
        $this->log('Stock Report', $from, $to);

        return view('admin.reports.stock', compact(
            'outOfStock', 'lowStock', 'inStock', 'lowProducts', 'lowVariations',
            'variationProductMap', 'inventoryValue', 'lowStockThreshold'
        ));
    }

    // ==========================================
    // 6. REVENUE REPORT (payment-method wise + growth)
    // ==========================================
    public function revenue(Request $request)
    {
        [$from, $to] = $this->dateRange($request);
        $base = $this->validOrders()->whereBetween('created_at', [$from, $to]);

        $totalRevenue = (clone $base)->sum('total_amount');

        // Payment method wise revenue
        $byMethod = (clone $base)
            ->select('payment_method', DB::raw('COUNT(*) as orders'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('payment_method')
            ->orderByDesc('revenue')
            ->get();

        // Month-wise revenue (saari history, range se independent — trend dekhne ke liye)
        $monthly = $this->validOrders()
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Paid vs Pending (sirf is range me)
        $paidRevenue    = Order::where('payment_status', 'Paid')->whereBetween('created_at', [$from, $to])->sum('total_amount');
        $pendingRevenue = Order::where('payment_status', 'Pending')->whereBetween('created_at', [$from, $to])->sum('total_amount');

        $this->log('Revenue Report', $from, $to);

        return view('admin.reports.revenue', compact(
            'totalRevenue', 'byMethod', 'monthly', 'paidRevenue', 'pendingRevenue', 'from', 'to'
        ));
    }
}
