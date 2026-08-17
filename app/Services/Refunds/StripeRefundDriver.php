<?php

namespace App\Services\Refunds;

use App\Models\Order;
use App\Models\PaymentGateway;
use Stripe\StripeClient;

class StripeRefundDriver implements RefundDriverInterface
{
    public function refund(Order $order, PaymentGateway $gateway, float $amount): array
    {
        // Stripe refund -- PaymentIntent ID (pi_xxx) chahiye, orders.transaction_id me hai
        $paymentIntent = $order->transaction_id;

        if (empty($paymentIntent) || !str_starts_with($paymentIntent, 'pi_')) {
            return [
                'success'   => false,
                'reference' => null,
                'message'   => 'Stripe PaymentIntent (pi_xxx) order me nahi mila. Manual refund karein.',
            ];
        }

        $secretKey = $gateway->credential('secret_key');
        if (empty($secretKey)) {
            return [
                'success'   => false,
                'reference' => null,
                'message'   => 'Stripe secret key gateway config me nahi mila.',
            ];
        }

        $stripe = new StripeClient($secretKey);

        // Stripe amount smallest unit (paise/cents) me -- INR ke liye x100
        $refund = $stripe->refunds->create([
            'payment_intent' => $paymentIntent,
            'amount'         => (int) round($amount * 100),
        ]);

        if (isset($refund->id)) {
            return [
                'success'   => true,
                'reference' => $refund->id,   // re_xxxxx
                'message'   => 'Stripe refund ho gaya. 5-10 din me paisa milega.',
            ];
        }

        return [
            'success'   => false,
            'reference' => null,
            'message'   => 'Stripe refund fail.',
        ];
    }
}