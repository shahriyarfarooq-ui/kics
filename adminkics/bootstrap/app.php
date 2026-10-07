<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('web', \App\Http\Middleware\EnsurePortalRole::class);
        $middleware->redirectGuestsTo(function (Request $request) {
            return $request->is('admin/*') || $request->is('admin-page')
                ? route('admin.login')
                : route('staff.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();