<?php

namespace App\Services\Payments\Drivers;

use App\Models\Order;
use App\Models\PaymentGateway;
use App\Services\Payments\PaymentGatewayInterface;
use Illuminate\Http\Request;

class PayuDriver implements PaymentGatewayInterface
{
    public function initiate(Order $order, PaymentGateway $config)
    {
        $key   = $config->credential('merchant_key');
        $salt  = $config->credential('merchant_salt');
        $txnid = 'PAYU' . $order->id . time();

        $amount      = number_format($order->total_amount, 2, '.', '');
        $productinfo = 'Order #' . $order->id;
        $firstname   = $order->name;
        $email       = $order->email;

        // Hash: key|txnid|amount|productinfo|firstname|email|||||||||||salt
        $hashString = "{$key}|{$txnid}|{$amount}|{$productinfo}|{$firstname}|{$email}|||||||||||{$salt}";
        $hash       = strtolower(hash('sha512', $hashString));

        $order->transaction_id = $txnid;
        $order->save();

        $action = $config->mode === 'live'
            ? 'https://secure.payu.in/_payment'
            : 'https://test.payu.in/_payment';

        return view('frontend.payment.payu', [
            'action'      => $action,
            'key'         => $key,
            'txnid'       => $txnid,
            'amount'      => $amount,
            'productinfo' => $productinfo,
            'firstname'   => $firstname,
            'email'       => $email,
            'phone'       => $order->phone,
            'hash'        => $hash,
            'order'       => $order,
        ]);
    }

    public function handleCallback(Request $request, Order $order, PaymentGateway $config): bool
    {
        $salt   = $config->credential('merchant_salt');
        $key    = $config->credential('merchant_key');
        $status = $request->input('status');

        // Reverse hash: salt|status|||||||||||email|firstname|productinfo|amount|txnid|key
        $hashString = "{$salt}|{$status}|||||||||||"
            . $request->input('email') . '|'
            . $request->input('firstname') . '|'
            . $request->input('productinfo') . '|'
            . $request->input('amount') . '|'
            . $request->input('txnid') . '|'
            . $key;

        $calculated = strtolower(hash('sha512', $hashString));

        if ($calculated === $request->input('hash') && $status === 'success') {
            $order->transaction_id   = $request->input('mihpayid', $order->transaction_id);
            $order->payment_response = json_encode($request->except(['hash']));
            return true;
        }

        return false;
    }
}