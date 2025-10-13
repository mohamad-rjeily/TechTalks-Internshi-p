<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // FIX: Register the 'checkUser' alias so your admin routes can find the middleware.
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'checkUser' => \App\Http\Middleware\AdminAuth::class, // <-- This is the required fix
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();