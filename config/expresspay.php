<?php

return [
    'api_key' => env('EXPRESSPAY_KEY'),
    'merchant_id' => env('EXPRESSPAY_ID'),
    'checkout' => [
        'submit_url' => env('EXPRESSPAY_CHECKOUT_SUBMIT_URL', 'https://sandbox.expresspaygh.com/api/submit.php'),
        'redirect_url' => env('EXPRESSPAY_CHECKOUT_REDIRECT_URL', 'https://sandbox.expresspaygh.com/api/checkout.php')
    ],
    'direct' => [],
];