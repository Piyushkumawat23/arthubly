<?php

namespace App\Services\Refunds;

use App\Models\Order;
use App\Models\PaymentGateway;
use Razorpay\Api\Api;

class RazorpayRefundDriver implements RefundDriverInterface
{
    public function refund(Order $order, PaymentGateway $gateway, float $amount): array
    {
        // 1. Payment ID -- orders.transaction_id me pay_xxxxx save hai
        $paymentId = $order->transaction_id;

        if (empty($paymentId) || !str_starts_with($paymentId, 'pay_')) {
            return [
                'success'   => false,
                'reference' => null,
                'message'   => 'Razorpay payment ID (pay_xxx) order me nahi mila. Manual refund karein.',
            ];
        }

        // 2. Credentials (gateway config me jo payment ke time use hue)
        $keyId     = $gateway->credential('key_id');
        $keySecret = $gateway->credential('key_secret');

        if (empty($keyId) || empty($keySecret)) {
            return [
                'success'   => false,
                'reference' => null,
                'message'   => 'Razorpay credentials gateway config me nahi mile.',
            ];
        }

        // 3. Razorpay API call -- amount paise me bhejna hota hai (x100)
        $api = new Api($keyId, $keySecret);

        $refund = $api->payment($paymentId)->refund([
            'amount' => (int) round($amount * 100),  // rupees -> paise
            'speed'  => 'normal',                    // 'optimum' for instant (extra charge)
            'notes'  => [
                'order_id'   => (string) $order->id,
                'refund_for' => 'Customer return',
            ],
        ]);

        // 4. Success -- refund id rfnd_xxxxx
        return [
            'success'   => true,
            'reference' => $refund['id'] ?? null,   // rfnd_xxxxx
            'message'   => 'Razorpay refund initiate ho gaya. Customer ko 5-7 din me paisa milega.',
        ];
    }
}