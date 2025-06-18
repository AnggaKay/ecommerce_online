<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Shipping API
    |--------------------------------------------------------------------------
    |
    | This option controls the default shipping API that will be used when
    | calculating shipping costs. You may change this to any of the APIs defined
    | in the "apis" array below.
    |
    */
    'default' => env('SHIPPING_API', 'rajaongkir'),

    /*
    |--------------------------------------------------------------------------
    | Shipping APIs
    |--------------------------------------------------------------------------
    |
    | Here you may configure the shipping APIs for your application.
    |
    */
    'apis' => [
        'rajaongkir' => [
            'api_key' => env('RAJAONGKIR_API_KEY'),
            'package' => env('RAJAONGKIR_PACKAGE', 'starter'), // starter, basic, pro
            'base_url' => env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com'),
        ],
        'shipper' => [
            'api_key' => env('SHIPPER_API_KEY'),
            'base_url' => env('SHIPPER_BASE_URL', 'https://api.shipper.id'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping Couriers
    |--------------------------------------------------------------------------
    |
    | List of available shipping couriers
    |
    */
    'couriers' => [
        'jne' => [
            'name' => 'JNE',
            'code' => 'jne',
            'is_active' => true,
        ],
        'tiki' => [
            'name' => 'TIKI',
            'code' => 'tiki',
            'is_active' => true,
        ],
        'pos' => [
            'name' => 'POS Indonesia',
            'code' => 'pos',
            'is_active' => true,
        ],
        'sicepat' => [
            'name' => 'SiCepat',
            'code' => 'sicepat',
            'is_active' => true,
        ],
        'jnt' => [
            'name' => 'J&T Express',
            'code' => 'jnt',
            'is_active' => true,
        ],
        'anteraja' => [
            'name' => 'AnterAja',
            'code' => 'anteraja',
            'is_active' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Store Information
    |--------------------------------------------------------------------------
    |
    | Store location information for shipping calculation
    |
    */
    'store' => [
        'city_id' => env('STORE_CITY_ID'),
        'province_id' => env('STORE_PROVINCE_ID'),
        'address' => env('STORE_ADDRESS'),
        'postal_code' => env('STORE_POSTAL_CODE'),
    ],
]; 