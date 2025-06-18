<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Payment Gateway
    |--------------------------------------------------------------------------
    |
    | This option controls the default payment gateway that will be used when
    | processing a payment. You may change this to any of the gateways defined
    | in the "gateways" array below.
    |
    */
    'default' => env('PAYMENT_GATEWAY', 'midtrans'),

    /*
    |--------------------------------------------------------------------------
    | Payment Gateways
    |--------------------------------------------------------------------------
    |
    | Here you may configure the payment gateways for your application.
    |
    */
    'gateways' => [
        'midtrans' => [
            'client_key' => env('MIDTRANS_CLIENT_KEY'),
            'server_key' => env('MIDTRANS_SERVER_KEY'),
            'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
            'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
            'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
            'is_3ds' => env('MIDTRANS_IS_3DS', true),
        ],
        'xendit' => [
            'api_key' => env('XENDIT_API_KEY'),
            'public_key' => env('XENDIT_PUBLIC_KEY'),
            'is_production' => env('XENDIT_IS_PRODUCTION', false),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Methods
    |--------------------------------------------------------------------------
    |
    | List of available payment methods
    |
    */
    'methods' => [
        'credit_card' => [
            'name' => 'Credit Card',
            'code' => 'credit_card',
            'is_active' => true,
        ],
        'bank_transfer' => [
            'name' => 'Bank Transfer',
            'code' => 'bank_transfer',
            'is_active' => true,
        ],
        'e_wallet' => [
            'name' => 'E-Wallet',
            'code' => 'e_wallet',
            'is_active' => true,
        ],
        'qris' => [
            'name' => 'QRIS',
            'code' => 'qris',
            'is_active' => true,
        ],
        'cod' => [
            'name' => 'Cash on Delivery',
            'code' => 'cod',
            'is_active' => true,
        ],
    ],
]; 