<?php

namespace App\Services\Refunds;

use App\Models\Order;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;

class PayuRefundDriver implements RefundDriverInterface
{
    public function refund(Order $order, PaymentGateway $gateway, float $amount): array
    {
        // PayU refund ke liye 'mihpayid' chahiye -- orders.transaction_id me save hai
        $mihpayid = $order->transaction_id;

        if (empty($mihpayid)) {
            return [
                'success'   => false,
                'reference' => null,
                'message'   => 'PayU payment ID (mihpayid) order me nahi mila. Manual refund karein.',
            ];
        }

        $key  = $gateway->credential('merchant_key');
        $salt = $gateway->credential('merchant_salt');

        if (empty($key) || empty($salt)) {
            return [
                'success'   => false,
                'reference' => null,
                'message'   => 'PayU credentials gateway config me nahi mile.',
            ];
        }

        // PayU refund -- merchant unique refund id
        $tokenId  = 'RFND' . $order->id . time();
        $command  = 'cancel_refund_transaction';

        // PayU hash: sha512(key|command|var1|salt)
        // var1 = mihpayid
        $hashString = $key . '|' . $command . '|' . $mihpayid . '|' . $salt;
        $hash = strtolower(hash('sha512', $hashString));

        // Mode: test ya live
        $base = ($gateway->mode === 'live')
            ? 'https://info.payu.in/merchant/postservice.php?form=2'
            : 'https://test.payu.in/merchant/postservice.php?form=2';

        $response = Http::asForm()->post($base, [
            'key'      => $key,
            'command'  => $command,
            'hash'     => $hash,
            'var1'     => $mihpayid,
            'var2'     => $tokenId,
            'var3'     => $amount,
        ]);

        $data = $response->json();

        // PayU status: 1 = success
        if (isset($data['status']) && $data['status'] == 1) {
            return [
                'success'   => true,
                'reference' => $data['request_id'] ?? $tokenId,
                'message'   => 'PayU refund request bhej diya. Customer ko 5-7 din me paisa milega.',
            ];
        }

        return [
            'success'   => false,
            'reference' => null,
            'message'   => 'PayU refund fail: ' . ($data['msg'] ?? 'Unknown error'),
        ];
    }
}