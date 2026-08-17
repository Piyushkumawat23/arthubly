<?php

return [

    'cod' => [
        'name'   => 'Cash on Delivery',
        'fields' => [],
    ],

    'razorpay' => [
        'name'   => 'Razorpay',
        'fields' => [
            'key_id'         => ['label' => 'Key ID',         'type' => 'text'],
            'key_secret'     => ['label' => 'Key Secret',     'type' => 'password'],
            'webhook_secret' => ['label' => 'Webhook Secret', 'type' => 'password'],
        ],
    ],

    'payu' => [
        'name'   => 'PayU',
        'fields' => [
            'merchant_key'  => ['label' => 'Merchant Key',  'type' => 'text'],
            'merchant_salt' => ['label' => 'Merchant Salt', 'type' => 'password'],
        ],
    ],

    'cashfree' => [
        'name'   => 'Cashfree Payments',
        'fields' => [
            'app_id'     => ['label' => 'App ID',     'type' => 'text'],
            'secret_key' => ['label' => 'Secret Key', 'type' => 'password'],
        ],
    ],

    'ccavenue' => [
        'name'   => 'CCAvenue',
        'fields' => [
            'merchant_id' => ['label' => 'Merchant ID', 'type' => 'text'],
            'access_code' => ['label' => 'Access Code', 'type' => 'text'],
            'working_key' => ['label' => 'Working Key', 'type' => 'password'],
        ],
    ],

    'paytm' => [
        'name'   => 'Paytm Payment Gateway',
        'fields' => [
            'merchant_id'   => ['label' => 'Merchant ID (MID)', 'type' => 'text'],
            'merchant_key'  => ['label' => 'Merchant Key',      'type' => 'password'],
            'website'       => ['label' => 'Website',           'type' => 'text'],
            'industry_type' => ['label' => 'Industry Type',     'type' => 'text'],
        ],
    ],

    'phonepe' => [
        'name'   => 'PhonePe Payment Gateway',
        'fields' => [
            'merchant_id' => ['label' => 'Merchant ID', 'type' => 'text'],
            'salt_key'    => ['label' => 'Salt Key',    'type' => 'password'],
            'salt_index'  => ['label' => 'Salt Index',  'type' => 'text'],
        ],
    ],

    'paypal' => [
        'name'   => 'PayPal',
        'fields' => [
            'client_id'     => ['label' => 'Client ID',     'type' => 'text'],
            'client_secret' => ['label' => 'Client Secret', 'type' => 'password'],
        ],
    ],

    'stripe' => [
        'name'   => 'Stripe',
        'fields' => [
            'publishable_key' => ['label' => 'Publishable Key', 'type' => 'text'],
            'secret_key'      => ['label' => 'Secret Key',      'type' => 'password'],
            'webhook_secret'  => ['label' => 'Webhook Secret',  'type' => 'password'],
        ],
    ],

    'adyen' => [
        'name'   => 'Adyen',
        'fields' => [
            'api_key'          => ['label' => 'API Key',          'type' => 'password'],
            'merchant_account' => ['label' => 'Merchant Account', 'type' => 'text'],
            'client_key'       => ['label' => 'Client Key',       'type' => 'text'],
            'hmac_key'         => ['label' => 'HMAC Key',         'type' => 'password'],
        ],
    ],

    'braintree' => [
        'name'   => 'Braintree',
        'fields' => [
            'merchant_id' => ['label' => 'Merchant ID', 'type' => 'text'],
            'public_key'  => ['label' => 'Public Key',  'type' => 'text'],
            'private_key' => ['label' => 'Private Key', 'type' => 'password'],
        ],
    ],

    'twocheckout' => [
        'name'   => '2Checkout',
        'fields' => [
            'merchant_code' => ['label' => 'Merchant Code', 'type' => 'text'],
            'secret_key'    => ['label' => 'Secret Key',    'type' => 'password'],
        ],
    ],

    'gpay' => [
        'name'   => 'Google Pay',
        'fields' => [
            'merchant_id'   => ['label' => 'Merchant ID',          'type' => 'text'],
            'merchant_name' => ['label' => 'Merchant Name',        'type' => 'text'],
            'gateway_id'    => ['label' => 'Gateway (PSP) ID',     'type' => 'text'],
        ],
    ],

];