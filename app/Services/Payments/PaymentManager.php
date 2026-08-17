<?php

namespace App\Services\Payments;

use App\Services\Payments\Drivers\RazorpayDriver;
use App\Services\Payments\Drivers\PayuDriver;
use App\Services\Payments\Drivers\CashfreeDriver;
use App\Services\Payments\Drivers\PhonePeDriver;
use App\Services\Payments\Drivers\PaytmDriver;
use App\Services\Payments\Drivers\CCAvenueDriver;
use App\Services\Payments\Drivers\PaypalDriver;
use App\Services\Payments\Drivers\StripeDriver;

class PaymentManager
{
    protected array $drivers = [
        'razorpay'    => RazorpayDriver::class,
        'payu'        => PayuDriver::class,
        'cashfree'    => CashfreeDriver::class,
        'phonepe'     => PhonePeDriver::class,
        'paytm'       => PaytmDriver::class,
        'ccavenue'    => CCAvenueDriver::class,
        'paypal'      => PaypalDriver::class,
        'stripe'      => StripeDriver::class,
        // Adyen / Braintree / 2Checkout / GPay — neeche note padho
    ];

    public function driver(string $slug): PaymentGatewayInterface
    {
        $slug = strtolower($slug);

        if (! isset($this->drivers[$slug])) {
            throw new \RuntimeException("Payment gateway [{$slug}] ka integration abhi available nahi hai.");
        }

        return app($this->drivers[$slug]);
    }
}