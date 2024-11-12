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

        foreach ((array) config('headers.remove') as $header) {
            $response->headers->remove(
                key: $header,
            );
        }

        $response->headers->set('Server', '');
        $response->headers->set('X-Powered-By', '');

        $response->withHeaders([
            'Content-Security-Policy' => "".
                "script-src 'self' 'unsafe-inline' https://*.cloudworkstations.dev https://cdn.usefathom.com https://cdnjs.cloudflare.com/;".
                "script-src-elem 'self' 'unsafe-inline' https://*.cloudworkstations.dev https://cdn.usefathom.com https://cdnjs.cloudflare.com/;".
                "style-src 'self' 'unsafe-inline' https://*.cloudworkstations.dev https://fonts.bunny.net https://use.typekit.net https://cdnjs.cloudflare.com;".
                "style-src-elem 'self' 'unsafe-inline' https://*.cloudworkstations.dev https://fonts.bunny.net https://use.typekit.net https://cdnjs.cloudflare.com;".
                "img-src 'self' https://*.cloudworkstations.dev https://laravel.com https://cdn.usefathom.com;".
                "font-src 'self'  'unsafe-inline' https://*.cloudworkstations.dev  https://fonts.bunny.net https://use.typekit.net;",
        ]);

        return $response;
    }
}
