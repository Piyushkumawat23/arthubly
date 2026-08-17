<?php

namespace App\Services\Payments\Drivers;

use App\Models\Order;
use App\Models\PaymentGateway;
use App\Services\Payments\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PhonePeDriver implements PaymentGatewayInterface
{
    protected function baseUrl(PaymentGateway $config): string
    {
        return $config->mode === 'live'
            ? 'https://api.phonepe.com/apis/hermes'
            : 'https://api-preprod.phonepe.com/apis/pg-sandbox';
    }

    public function initiate(Order $order, PaymentGateway $config)
    {
        $merchantId  = $config->credential('merchant_id');
        $saltKey     = $config->credential('salt_key');
        $saltIndex   = $config->credential('salt_index');
        $merchantTxn = 'PHP' . $order->id . time();

        $payload = [
            'merchantId'            => $merchantId,
            'merchantTransactionId' => $merchantTxn,
            'merchantUserId'        => 'USER' . ($order->user_id ?? 'guest'),
            'amount'                => (int) round($order->total_amount * 100),
            'redirectUrl'           => route('payment.callback', $order->id),
            'redirectMode'          => 'POST',
            'callbackUrl'           => route('payment.callback', $order->id),
            'mobileNumber'          => $order->phone,
            'paymentInstrument'     => ['type' => 'PAY_PAGE'],
        ];

        $base64  = base64_encode(json_encode($payload));
        $xVerify = hash('sha256', $base64 . '/pg/v1/pay' . $saltKey) . '###' . $saltIndex;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY'     => $xVerify,
        ])->post($this->baseUrl($config) . '/pg/v1/pay', ['request' => $base64]);

        $redirect = data_get($response->json(), 'data.instrumentResponse.redirectInfo.url');

        if (! $redirect) {
            return redirect()->route('checkout.index')
                ->with('error', 'PhonePe: payment start nahi hua. ' . $response->body());
        }

        $order->transaction_id = $merchantTxn;
        $order->save();

        return redirect()->away($redirect);
    }

    public function handleCallback(Request $request, Order $order, PaymentGateway $config): bool
    {
        $merchantId = $config->credential('merchant_id');
        $saltKey    = $config->credential('salt_key');
        $saltIndex  = $config->credential('salt_index');
        $txn        = $order->transaction_id;

        $path    = "/pg/v1/status/{$merchantId}/{$txn}";
        $xVerify = hash('sha256', $path . $saltKey) . '###' . $saltIndex;

        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'X-VERIFY'      => $xVerify,
            'X-MERCHANT-ID' => $merchantId,
        ])->get($this->baseUrl($config) . $path);

        if (($response['code'] ?? '') === 'PAYMENT_SUCCESS') {
            $order->payment_response = $response->body();
            return true;
        }

        return false;
    }
}