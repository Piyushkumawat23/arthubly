<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ActivityLog; // 🟢 ActivityLog Import Kiya Gaya Hai
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Saare orders ki list dikhane ke liye
    // Saare orders ki list dikhane ke liye
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Order::latest();

        // 🟢 STATUS FILTER (sidebar links se aata hai: ?status=Pending etc.)
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // 🟢 B2B SELLER LOGIC: Seller ko sirf wahi orders dikhao jinme uska product ho
        if ($user->role === 'seller') {
            $query->whereHas('items.product', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        // Status counts (sidebar/badge ke liye, optional)
        $statusList = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled', 'Returns'];

        return view('admin.orders.index', compact('orders', 'statusList'));
    }

    // Single order ki detail dikhane ke liye
    public function show($id)
    {
        $user = auth()->user();

        // 🟢 B2B SELLER LOGIC: Agar seller hai toh order ke andar sirf uske items load karo, 
        // baaki admin/staff ko sab load karke dikhao.
        $orderQuery = Order::with(['items' => function ($query) use ($user) {
            if ($user->role === 'seller') {
                $query->whereHas('product', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
        }, 'items.product']);

        $order = $orderQuery->findOrFail($id);

        // 🟢 B2B SELLER SECURITY CHECK: Agar order mein is seller ka koi item nahi hai toh access deny karo
        if ($user->role === 'seller' && $order->items->isEmpty()) {
            abort(403, 'Unauthorized access. Is order mein aapka koi product nahi hai.');
        }

        return view('admin.orders.show', compact('order'));
    }

    // Order aur Payment ka status update karne ke liye
    public function updateStatus(Request $request, $id)
    {
        $user = auth()->user();
        $order = Order::findOrFail($id);

        // 🟢 B2B SELLER SECURITY CHECK: Update karne se pehle check karo ki iska product is order mein hai ya nahi
        if ($user->role === 'seller') {
            $hasSellerProduct = Order::where('id', $id)
                ->whereHas('items.product', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->exists();

            if (!$hasSellerProduct) {
                abort(403, 'Unauthorized action. Aap dusre ke orders update nahi kar sakte.');
            }
        }
        
        // 🟢 LOG CHANGES: Purana status hold karein save hone se pehle
        $oldOrderStatus = $order->order_status;
        $oldPaymentStatus = $order->payment_status;

        $order->order_status = $request->order_status;
        $order->payment_status = $request->payment_status;
        $order->save();

        // 🟢 CREATE UPDATE ACTIVITY LOG
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Update Status',
            'module' => 'Orders',
            'description' => json_encode([
                'order_id' => $order->id,
                'old' => ['order_status' => $oldOrderStatus, 'payment_status' => $oldPaymentStatus],
                'new' => ['order_status' => $request->order_status, 'payment_status' => $request->payment_status]
            ]),
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    public function bulkStatusUpdate(Request $request)
    {
        $ids = $request->ids;
        $status = $request->status;
        $user = auth()->user();

        if (!empty($ids)) {
            // 🟢 B2B SELLER SECURITY CHECK: Bulk update mein bhi check karo
            if ($user->role === 'seller') {
                // Sirf wahi Order IDs nikaalo jinme is seller ka product maujood hai
                $validIds = Order::whereIn('id', $ids)
                    ->whereHas('items.product', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->pluck('id')->toArray();

                Order::whereIn('id', $validIds)->update(['order_status' => $status]);
                $loggedIds = $validIds;
            } else {
                // Admin, Subadmin, etc. sabhi selected orders update kar sakte hain
                Order::whereIn('id', $ids)->update(['order_status' => $status]);
                $loggedIds = $ids;
            }

            // 🟢 CREATE BULK UPDATE ACTIVITY LOG
            if (!empty($loggedIds)) {
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'Bulk Update Status',
                    'module' => 'Orders',
                    'description' => "Bulk updated order status to '{$status}' for order IDs: " . implode(', ', $loggedIds),
                    'ip_address' => request()->ip(),
                ]);
            }

            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 400);
    }
}