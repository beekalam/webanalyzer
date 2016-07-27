<?php

namespace App\Http\Middleware;

use Log;
use Closure;
use Illuminate\Http\Request;

class RequestLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info("Request Logged\n" . sprintf("-----------------\n%s---------------", (string)$request));

        return $next($request);
    }
}
