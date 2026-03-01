<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FedaPay Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your FedaPay API keys and environment.
    | Get your keys from https://app.fedapay.com or https://sandbox.fedapay.com
    |
    */

    'secret_key' => env('FEDAPAY_SECRET_KEY', ''),
    'public_key' => env('FEDAPAY_PUBLIC_KEY', ''),
    'environment' => env('FEDAPAY_ENV', 'sandbox'), // 'sandbox' or 'live'
    'currency' => env('FEDAPAY_CURRENCY', 'XOF'),
    'amount' => env('FEDAPAY_AMOUNT', 1000),
    'webhook_secret' => env('FEDAPAY_WEBHOOK_SECRET', ''),
];
