<?php

return [

    'patch' => true,

    'multitenancy' => false,

    'limit_restriction' => false,

    'dashboard_ui' => env('DASHBOARD_UI', 'EXTENDED'),  # EXTENDED, CONTAINTER

    'demo' => env('DEMO', "NO"), # YES or NO

    'hide' => false, # false means hide that

    'google_recaptcha' => env('GOOGLE_RECAPTCHA', "NO"), # YES or NO

    'braintree' => env('BRAINTREE', "NO"), # YES or NO

    'stripe' => env('STRIPE', "NO"), # YES or NO

    'flutterwave' => env('FLUTTERWAVE', "NO"), # YES or NO

    'ssl_commerz' => env('SSL_COMMERZ', "NO"), # YES or NO

    'paystack' => env('PAYSTACK', "NO"), # YES or NO
    'paystack_merchant_currency' => env('MERCHANT_CURRENCY', "ZAR"), # YES or NO

    'instamojo' => env('INSTAMOJO', "NO"), # YES or NO

    'razorpay' => env('RAZORPAY', "NO"), # YES or NO

    'squad' => env('SQUAD', "NO"), # YES or NO
    'squad_merchant_currency' => env('SQUAD_CURRENCY', "USD"), # USD or NGN
    'squad_public_key' => env('SQUAD_PUBLIC_KEY'),

    'kyc' => env('KYC', "NO"), # YES or NO

    'shop' => env('SHOP', "YES"), # YES or NO
];
