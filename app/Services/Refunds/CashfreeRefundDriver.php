<?php

namespace App\Services\Refunds;

use App\Models\Order;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;

class CashfreeRefundDriver implements RefundDriverInterface
{
    public function refund(Order $order, PaymentGateway $gateway, float $amount): array
    {
        // Cashfree refund -- cf_order_id chahiye (payment_response JSON me)
        $cfOrderId = null;
        if ($order->payment_response) {
            $resp = json_decode($order->payment_response, true);
            $cfOrderId = $resp['cf_order_id'] ?? ($resp['order_id'] ?? null);
        }

        // Fallback: humne order create ke time apna order id bheja tha
        if (empty($cfOrderId)) {
            $cfOrderId = $order->transaction_id;
        }

        if (empty($cfOrderId)) {
            return [
                'success'   => false,
                'reference' => null,
                'message'   => 'Cashfree order ID nahi mila. Manual refund karein.',
            ];
        }

        $appId  = $gateway->credential('app_id');
        $secret = $gateway->credential('secret_key');

        if (empty($appId) || empty($secret)) {
            return [
                'success'   => false,
                'reference' => null,
                'message'   => 'Cashfree credentials gateway config me nahi mile.',
            ];
        }

        $base = ($gateway->mode === 'live')
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';

        $refundId = 'rfnd_' . $order->id . '_' . time();

        // NOTE: Cashfree me refund order ke 'order_id' (humara bheja hua) pe hota hai,
        // cf_order_id pe nahi. Agar aapne order create me apna order id (jaise "ORDER_16")
        // bheja tha, to wahi use karna. Yahan transaction_id ko fallback rakha hai.
        $ourOrderId = $cfOrderId;

        $response = Http::withHeaders([
            'x-api-version' => '2023-08-01',
            'x-client-id'   => $appId,
            'x-client-secret' => $secret,
            'Content-Type'  => 'application/json',
        ])->post("{$base}/orders/{$ourOrderId}/refunds", [
            'refund_amount' => $amount,
            'refund_id'     => $refundId,
            'refund_note'   => 'Customer return refund',
        ]);

        $data = $response->json();

        if ($response->successful() && isset($data['cf_refund_id'])) {
            return [
                'success'   => true,
                'reference' => $data['cf_refund_id'],
                'message'   => 'Cashfree refund initiate ho gaya. 5-7 din me paisa milega.',
            ];
        }

        return [
            'success'   => false,
            'reference' => null,
            'message'   => 'Cashfree refund fail: ' . ($data['message'] ?? 'Unknown error'),
        ];
    }
}