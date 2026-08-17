<?php

namespace App\Services\Payments\Drivers;

use App\Models\Order;
use App\Models\PaymentGateway;
use App\Services\Payments\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class StripeDriver implements PaymentGatewayInterface
{
    public function initiate(Order $order, PaymentGateway $config)
    {
        $stripe = new StripeClient($config->credential('secret_key'));

        $session = $stripe->checkout->sessions->create([
            'mode'        => 'payment',
            'line_items'  => [[
                'price_data' => [
                    'currency'     => 'inr',
                    'product_data' => ['name' => 'Order #' . $order->id],
                    'unit_amount'  => (int) round($order->total_amount * 100),
                ],
                'quantity' => 1,
            ]],
            'customer_email' => $order->email,
            'success_url'    => route('payment.callback', $order->id) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'     => route('checkout.index'),
        ]);

        $order->transaction_id = $session->id;
        $order->save();

        return redirect()->away($session->url);
    }

    public function handleCallback(Request $request, Order $order, PaymentGateway $config): bool
    {
        $stripe  = new StripeClient($config->credential('secret_key'));
        $session = $stripe->checkout->sessions->retrieve($request->input('session_id'));

        if (($session->payment_status ?? '') === 'paid') {
            $order->transaction_id   = $session->payment_intent ?? $order->transaction_id;
            $order->payment_response = json_encode($session);
            return true;
        }

        return false;
    }
}