<?php

namespace App\Services\Payments\Drivers;

use App\Models\Order;
use App\Models\PaymentGateway;
use App\Services\Payments\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use paytm\paytmchecksum\PaytmChecksum;

class PaytmDriver implements PaymentGatewayInterface
{
    protected function baseUrl(PaymentGateway $config): string
    {
        return $config->mode === 'live'
            ? 'https://securegw.paytm.in'
            : 'https://securegw-stage.paytm.in';
    }

    public function initiate(Order $order, PaymentGateway $config)
    {
        $mid      = $config->credential('merchant_id');
        $mkey     = $config->credential('merchant_key');
        $website  = $config->credential('website') ?: 'WEBSTAGING';
        $orderId  = 'PTM' . $order->id . time();

        $body = [
            'requestType' => 'Payment',
            'mid'         => $mid,
            'websiteName' => $website,
            'orderId'     => $orderId,
            'callbackUrl' => route('payment.callback', $order->id),
            'txnAmount'   => ['value' => number_format($order->total_amount, 2, '.', ''), 'currency' => 'INR'],
            'userInfo'    => ['custId' => 'CUST' . ($order->user_id ?? 'guest')],
        ];

        $checksum = PaytmChecksum::generateSignature(json_encode(['body' => $body]), $mkey);

        $response = Http::post(
            $this->baseUrl($config) . "/theia/api/v1/initiateTransaction?mid={$mid}&orderId={$orderId}",
            ['body' => $body, 'head' => ['signature' => $checksum]]
        );

        $token = data_get($response->json(), 'body.txnToken');

        if (! $token) {
            return redirect()->route('checkout.index')
                ->with('error', 'Paytm: transaction token nahi mila. ' . $response->body());
        }

        $order->transaction_id = $orderId;
        $order->save();

        return view('frontend.payment.paytm', [
            'mid'     => $mid,
            'orderId' => $orderId,
            'token'   => $token,
            'amount'  => number_format($order->total_amount, 2, '.', ''),
            'url'     => $this->baseUrl($config) . "/theia/api/v1/showPaymentPage?mid={$mid}&orderId={$orderId}",
        ]);
    }

    public function handleCallback(Request $request, Order $order, PaymentGateway $config): bool
    {
        $mkey      = $config->credential('merchant_key');
        $paytmData = $request->all();
        $checksum  = $paytmData['CHECKSUMHASH'] ?? '';
        unset($paytmData['CHECKSUMHASH']);

        $isValid = PaytmChecksum::verifySignature($paytmData, $mkey, $checksum);

        if ($isValid && ($request->input('STATUS') === 'TXN_SUCCESS')) {
            $order->transaction_id   = $request->input('TXNID', $order->transaction_id);
            $order->payment_response = json_encode($request->all());
            return true;
        }

        return false;
    }
}