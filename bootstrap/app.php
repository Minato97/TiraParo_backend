<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
)
    ->withMiddleware(function (Middleware $middleware) {
        // Confiar en ngrok como proxy (necesario para HTTPS y headers correctos)
        $middleware->trustProxies(at: '*');

        // Permitir el header que ngrok requiere para saltarse la pantalla de advertencia
        $middleware->validateCsrfTokens(except: ['*']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
