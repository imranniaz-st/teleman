<?php

/*
 * This file is part of the Laravel Paystack package.
 *
 * (c) Prosper Otemuyiwa <prosperotemuyiwa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /**
     * Public Key From Paystack Dashboard
     *
     */
    'publicKey' => getenv('PAYSTACK_PUBLIC_KEY'), // 'pk_test_6c9f9f1c8f9f1c9f9f9f9f9f9f9f9f9f9f9f9f9',

    /**
     * Secret Key From Paystack Dashboard
     *
     */
    'secretKey' => getenv('PAYSTACK_SECRET_KEY'), // 'sk_test_6e41e5e2f7a9f8f8a01d5f9f9',

    /**
     * Paystack Payment URL
     *
     */
    'paymentUrl' => getenv('PAYSTACK_PAYMENT_URL'), // 'https://api.paystack.co/transaction/initialize',

    /**
     * Optional email address of the merchant
     *
     */
    'merchantEmail' => getenv('MERCHANT_EMAIL'), // optional

];
