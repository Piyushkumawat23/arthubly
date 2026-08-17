<?php

namespace App\Services\Refunds;

use App\Models\Order;
use App\Models\PaymentGateway;

interface RefundDriverInterface
{
    /**
     * Refund process karega.
     *
     * @param  Order          $order    Jis order ka refund
     * @param  PaymentGateway $gateway  Gateway config (credentials ke liye)
     * @param  float          $amount   Kitna refund karna hai
     * @return array  ['success' => bool, 'reference' => string|null, 'message' => string]
     */
    public function refund(Order $order, PaymentGateway $gateway, float $amount): array;
}