<?php

namespace App\Services\Refunds;

use App\Models\Order;
use App\Models\PaymentGateway;
use Illuminate\Support\Str;

class RefundManager
{
    /**
     * payment_method (slug) -> refund driver class
     */
    protected array $drivers = [
        'razorpay' => RazorpayRefundDriver::class,
        'payu'     => PayuRefundDriver::class,
        'cashfree' => CashfreeRefundDriver::class,
        'stripe'   => StripeRefundDriver::class,
    ];

    /**
     * Order ke payment_method se sahi driver le ke refund karo.
     *
     * @return array ['success'=>bool, 'reference'=>?string, 'message'=>string]
     */
    public function refund(Order $order, float $amount): array
    {
        // 1. Order ka gateway slug (payment_method DB me UPPERCASE save hota hai)
        $slug = strtolower($order->payment_method);

        // 2. COD ka auto-refund nahi hota
        if ($slug === 'cod') {
            return [
                'success'   => false,
                'reference' => null,
                'message'   => 'COD order ka auto-refund nahi ho sakta. Manual refund karein.',
            ];
        }

        // 3. Driver available hai?
        if (!isset($this->drivers[$slug])) {
            return [
                'success'   => false,
                'reference' => null,
                'message'   => "'{$order->payment_method}' ka auto-refund abhi support nahi. Manual refund karein.",
            ];
        }

        // 4. Gateway config (credentials) DB se
        $gateway = PaymentGateway::where('slug', $slug)->first();
        if (!$gateway) {
            return [
                'success'   => false,
                'reference' => null,
                'message'   => "Gateway '{$slug}' config nahi mila. Manual refund karein.",
            ];
        }

        // 5. Driver call karo
        try {
            $driver = app($this->drivers[$slug]);
            return $driver->refund($order, $gateway, $amount);
        } catch (\Throwable $e) {
            return [
                'success'   => false,
                'reference' => null,
                'message'   => 'Refund error: ' . $e->getMessage(),
            ];
        }
    }
}