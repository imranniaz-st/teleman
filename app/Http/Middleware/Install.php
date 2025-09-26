<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Http\Request;

class Install
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if (! DB::connection()->getPdo() || env('APP_INSTALL') == 'NO') {
                return redirect()->route('install');
            }

            return $next($request);
        } catch (\Exception $exception) {
            return redirect()->route('install');
        }
    }
}
