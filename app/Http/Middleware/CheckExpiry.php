<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->saas_key == env('SAAS_KEY')) {
            return $next($request);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
