<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/register-user', 
        '/user-item-limit-decrement', 
        '/pay', 
        '/pay-via-ajax', 
        '/success', 
        '/cancel', 
        '/fail', 
        '/ipn', 
        '/login', 
        '/ivr/log/*', 
        '/handle_call',
        '/handle_message',
        '/process-response',
        '/recording',
    ];
}
