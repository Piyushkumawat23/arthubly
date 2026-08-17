<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /** Payment shuru karo — view/redirect return karega */
    public function initiate(Order $order, PaymentGateway $config);

    /** Gateway se aaya response verify karo — true = paid */
    public function handleCallback(Request $request, Order $order, PaymentGateway $config): bool;
}