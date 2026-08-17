<?php

namespace App\Services\Payments\Drivers;

use App\Models\Order;
use App\Models\PaymentGateway;
use App\Services\Payments\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class RazorpayDriver implements PaymentGatewayInterface
{
    public function initiate(Order $order, PaymentGateway $config)
    {
        $api = new Api($config->credential('key_id'), $config->credential('key_secret'));

        // Razorpay order create (amount paise me)
        $rzpOrder = $api->order->create([
            'receipt'  => 'order_' . $order->id,
            'amount'   => (int) round($order->total_amount * 100),
            'currency' => 'INR',
        ]);

        // Razorpay ka order_id apne order me save kar lo
        $order->transaction_id = $rzpOrder['id'];
        $order->save();

        return view('frontend.payment.razorpay', [
            'order'    => $order,
            'rzpOrder' => $rzpOrder,
            'keyId'    => $config->credential('key_id'),
        ]);
    }

    public function handleCallback(Request $request, Order $order, PaymentGateway $config): bool
    {
        try {
            $api = new Api($config->credential('key_id'), $config->credential('key_secret'));

            // Signature verify — yahi asli security hai
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);

            $order->transaction_id   = $request->razorpay_payment_id;
            $order->payment_response = json_encode($request->only([
                'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature',
            ]));

            return true; // Verified
        } catch (\Exception $e) {
            return false; // Tampered ya fail
        }
    }
}