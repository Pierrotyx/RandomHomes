<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class FrameHeadersMiddleware
{
    public function handle($request, Closure $next)
    {
         $response = $next($request);
         $response->header('X-Frame-Options', 'ALLOW FROM https://www.randomhome.net/');
         return $response;
     }
}
