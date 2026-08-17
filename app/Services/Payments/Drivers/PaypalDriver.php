<?php

namespace App\Services\Payments\Drivers;

use App\Models\Order;
use App\Models\PaymentGateway;
use App\Services\Payments\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalDriver implements PaymentGatewayInterface
{
    protected function client(PaymentGateway $config): PayPalClient
    {
        $mode = $config->mode === 'live' ? 'live' : 'sandbox';

        config([
            'paypal.mode'                  => $mode,
            "paypal.{$mode}.client_id"     => $config->credential('client_id'),
            "paypal.{$mode}.client_secret" => $config->credential('client_secret'),
            "paypal.{$mode}.app_id"        => '',
            'paypal.currency'              => 'USD', // PayPal me INR support limited hai
        ]);

        $provider = new PayPalClient;
        $provider->getAccessToken();
        return $provider;
    }

    public function initiate(Order $order, PaymentGateway $config)
    {
        $provider = $this->client($config);

        $response = $provider->createOrder([
            'intent'         => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => (string) $order->id,
                'amount'       => [
                    'currency_code' => 'USD',
                    'value'         => number_format($order->total_amount, 2, '.', ''),
                ],
            ]],
            'application_context' => [
                'return_url' => route('payment.callback', $order->id),
                'cancel_url' => route('checkout.index'),
            ],
        ]);

        foreach ($response['links'] ?? [] as $link) {
            if ($link['rel'] === 'approve') {
                $order->transaction_id = $response['id'];
                $order->save();
                return redirect()->away($link['href']);
            }
        }

        return redirect()->route('checkout.index')
            ->with('error', 'PayPal: order create nahi hua.');
    }

    public function handleCallback(Request $request, Order $order, PaymentGateway $config): bool
    {
        $provider = $this->client($config);
        $result   = $provider->capturePaymentOrder($request->input('token'));

        if (($result['status'] ?? '') === 'COMPLETED') {
            $order->transaction_id   = $result['id'] ?? $order->transaction_id;
            $order->payment_response = json_encode($result);
            return true;
        }

        return false;
    }
}