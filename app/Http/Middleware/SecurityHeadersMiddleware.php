<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Content-Security-Policy', "default-src 'self';".
            "script-src 'nonce-".Vite::cspNonce()."'".
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net;".
            "img-src 'self' https://laravel.com https://flowbite.com;".
            "font-src 'self' https://fonts.bunny.net;"
        );

        return $response;
    }
}
