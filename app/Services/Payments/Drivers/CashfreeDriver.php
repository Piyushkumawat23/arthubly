<?php

namespace App\Services\Payments\Drivers;

use App\Models\Order;
use App\Models\PaymentGateway;
use App\Services\Payments\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CashfreeDriver implements PaymentGatewayInterface
{
    protected function baseUrl(PaymentGateway $config): string
    {
        return $config->mode === 'live'
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';
    }

    protected function headers(PaymentGateway $config): array
    {
        return [
            'x-client-id'     => $config->credential('app_id'),
            'x-client-secret' => $config->credential('secret_key'),
            'x-api-version'   => '2023-08-01',
            'Content-Type'    => 'application/json',
        ];
    }

    public function initiate(Order $order, PaymentGateway $config)
    {
        $orderId = 'CF' . $order->id . time();

        $response = Http::withHeaders($this->headers($config))
            ->post($this->baseUrl($config) . '/orders', [
                'order_id'       => $orderId,
                'order_amount'   => (float) $order->total_amount,
                'order_currency' => 'INR',
                'customer_details' => [
                    'customer_id'    => 'CUST' . ($order->user_id ?? 'guest'),
                    'customer_name'  => $order->name,
                    'customer_email' => $order->email,
                    'customer_phone' => $order->phone,
                ],
                'order_meta' => [
                    'return_url' => route('payment.callback', $order->id),
                ],
            ]);

        if (! $response->successful() || empty($response['payment_session_id'])) {
            return redirect()->route('checkout.index')
                ->with('error', 'Cashfree: order create nahi hua. ' . $response->body());
        }

        $order->transaction_id = $orderId;
        $order->save();

        return view('frontend.payment.cashfree', [
            'sessionId' => $response['payment_session_id'],
            'mode'      => $config->mode,
        ]);
    }

    public function handleCallback(Request $request, Order $order, PaymentGateway $config): bool
    {
        // Return ke baad asli order status fetch karke verify karo
        $response = Http::withHeaders($this->headers($config))
            ->get($this->baseUrl($config) . '/orders/' . $order->transaction_id);

        if ($response->successful() && ($response['order_status'] ?? '') === 'PAID') {
            $order->payment_response = $response->body();
            return true;
        }

        return false;
    }
}