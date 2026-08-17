<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ReturnRequest;
use App\Services\Refunds\RefundManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    /**
     * Admin: saari return requests (status filter ke saath)
     * Sidebar links: ?status=Pending / Approved / Rejected
     * ?refund=pending  -> approved jinka refund abhi pending hai (Refund Requests)
     */
    public function index(Request $request)
    {
        $query = ReturnRequest::with(['order', 'product', 'user'])->latest();

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Refund Requests = Approved + refund abhi nahi hua
        if ($request->get('refund') === 'pending') {
            $query->where('status', 'Approved')
                ->where('refund_status', '!=', 'Refunded');
        }

        $returns = $query->paginate(15)->withQueryString();

        // Counts (cards/badges ke liye)
        $counts = [
            'pending' => ReturnRequest::where('status', 'Pending')->count(),
            'approved' => ReturnRequest::where('status', 'Approved')->count(),
            'rejected' => ReturnRequest::where('status', 'Rejected')->count(),
            'refund_pending' => ReturnRequest::where('status', 'Approved')
                ->where('refund_status', '!=', 'Refunded')->count(),
        ];

        return view('admin.returns.index', compact('returns', 'counts'));
    }

    /**
     * Ek return request ki detail
     */
    public function show($id)
    {
        $return = ReturnRequest::with(['order', 'product', 'user'])->findOrFail($id);

        return view('admin.returns.show', compact('return'));
    }

    /**
     * APPROVE -- status Approved + stock wapas badhao
     */
    public function approve(Request $request, $id)
    {
        $return = ReturnRequest::findOrFail($id);

        // Already process ho chuka?
        if ($return->status !== 'Pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        DB::transaction(function () use ($return, $request) {
            // 1. Status update
            $return->status = 'Approved';
            $return->admin_note = $request->admin_note;
            $return->approved_at = now();
            $return->refund_status = 'Pending'; // ab refund pending hai (Piece 4 me process)
            $return->save();

            // 2. Stock wapas badhao
            $this->restoreStock($return);

            // 3. Order ko Returns status (optional -- taaki order list me dikhe)
            $order = Order::find($return->order_id);
            if ($order) {
                $order->order_status = 'Returns';
                $order->save();
            }
        });

        // Activity log
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Approve',
            'module' => 'Returns',
            'description' => "Approved return #{$return->id} (Order #{$return->order_id}), stock restored.",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Return approved successfully and stock has been restored.');

    }

    /**
     * REJECT
     */
    public function reject(Request $request, $id)
    {
        $return = ReturnRequest::findOrFail($id);

        if ($return->status !== 'Pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $request->validate([
            'admin_note' => 'required|string|max:1000',
        ], [
            'admin_note.required' => 'A reason is required to reject this return request.',
        ]);

        $return->status = 'Rejected';
        $return->admin_note = $request->admin_note;
        $return->refund_status = 'Not Initiated';
        $return->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Reject',
            'module' => 'Returns',
            'description' => "Rejected return #{$return->id} (Order #{$return->order_id}). Reason: {$request->admin_note}",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Return request has been rejected successfully.');
    }

    /**
     * Stock wapas badhane ka helper.
     * Agar return item me variation hai -> us variation + main product dono badhao.
     * Warna sirf main product stock.
     */
    private function restoreStock(ReturnRequest $return)
    {
        $qty = (int) $return->quantity;
        if ($qty < 1) {
            return;
        }

        // Order item se variation_id nikaalo (agar tha)
        $variationId = null;
        if ($return->order_item_id) {
            $item = OrderItem::find($return->order_item_id);
            if ($item) {
                $variationId = $item->variation_id ?? null;
            }
        }

        // Variation stock badhao
        if ($variationId) {
            $variation = ProductVariation::find($variationId);
            if ($variation) {
                $variation->stock = $variation->stock + $qty;
                $variation->save();
            }
        }

        // Main product stock badhao
        if ($return->product_id) {
            $product = Product::find($return->product_id);
            if ($product) {
                $product->stock = $product->stock + $qty;
                $product->save();
            }
        }
    }

    public function refundManual(Request $request, $id)
    {
        $return = ReturnRequest::findOrFail($id);

        // 1. Sirf Approved return ka refund ho sakta hai
        if ($return->status !== 'Approved') {
            return back()->with('error', 'Only approved returns can be refunded.');
        }

        if ($return->refund_status === 'Refunded') {
            return back()->with('error', 'This return has already been refunded.');
        }

        // 3. Validate
        $request->validate([
            'refund_amount' => 'required|numeric|min:0',
            'refund_method' => 'required|string|max:100',
            'refund_reference' => 'nullable|string|max:255',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        // 4. Record refund
        $return->refund_status = 'Refunded';
        $return->refund_amount = $request->refund_amount;
        $return->refund_method = $request->refund_method;   // e.g. "Bank Transfer", "UPI", "Gateway (manual)"
        $return->refund_reference = $request->refund_reference; // UTR / transaction id
        $return->refunded_at = now();
        if ($request->filled('admin_note')) {
            $return->admin_note = $request->admin_note;
        }
        $return->save();

        // 5. Log
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Refund (Manual)',
            'module' => 'Returns',
            'description' => "Manual refund Rs {$request->refund_amount} for return #{$return->id} "
                            ."(Order #{$return->order_id}) via {$request->refund_method}. Ref: {$request->refund_reference}",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Refund has been recorded successfully. Return status updated to Refunded.');
    }

    public function refundGateway(Request $request, $id)
    {
        $return = ReturnRequest::with('order')->findOrFail($id);

        // 1. Sirf Approved return ka refund
        if ($return->status !== 'Approved') {
            return back()->with('error', 'Only approved returns can be refunded.');
        }

        if ($return->refund_status === 'Refunded') {
            return back()->with('error', 'This return has already been refunded.');
        }

        $order = $return->order;
        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        if ($order->payment_status !== 'Paid') {
            return back()->with('error', 'This order is not paid, so gateway refund cannot be processed. Please use manual refund.');
        }

        // 4. Refund amount (return ka amount)
        $amount = (float) $return->refund_amount;
        if ($amount <= 0) {
           return back()->with('error', 'Refund amount cannot be zero.');
        }

        // 5. RefundManager se refund karo (sahi gateway khud chunega)
        $manager = app(RefundManager::class);
        $result = $manager->refund($order, $amount);

        if (! $result['success']) {
            // Fail -- log karo, refund_status pending hi rahega
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Refund Failed (Gateway)',
                'module' => 'Returns',
                'description' => "Gateway refund FAILED for return #{$return->id} (Order #{$order->id}). "
                                ."Reason: {$result['message']}",
                'ip_address' => request()->ip(),
            ]);

            return back()->with('error', $result['message']);
        }

        // 6. Success -- record refund
        $return->refund_status = 'Refunded';
        $return->refund_amount = $amount;
        $return->refund_method = 'Auto ('.strtoupper($order->payment_method).')';
        $return->refund_reference = $result['reference'];
        $return->refunded_at = now();
        $return->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Refund (Gateway)',
            'module' => 'Returns',
            'description' => "Auto refund Rs {$amount} for return #{$return->id} (Order #{$order->id}) "
                            ."via {$order->payment_method}. Ref: {$result['reference']}",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', $result['message']);
    }
}
