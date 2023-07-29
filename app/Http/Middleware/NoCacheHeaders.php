<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class NoCacheHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header("Cache-Control", "no-store, private, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0");
        $response->header("Pragma", "no-cache");

        return $response;
    }
}
