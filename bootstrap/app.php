<?php

use App\Http\Middleware\SecurityHeadersMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Implementar el middleware de seguridad
        $middleware->append(SecurityHeadersMiddleware::class);

        // Excluir validación CSRF para las rutas
        $middleware->validateCsrfTokens(except: [
            'stripe/*',
            'https://*.cloudworkstations.dev/login',
            'https://*.cloudworkstations.dev/register',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
