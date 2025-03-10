<?php

// use App\Http\Middleware\VerifyFrontendOrigin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->append(EnsureFrontendRequestsAreStateful::class);
        // $middleware->append(ThrottleRequests::class . ':api');
        // $middleware->append(SubstituteBindings::class);
        // $middleware->append(VerifyFrontendOrigin::class);
        // $middleware->append(VerifyFrontendOrigin::class);
        // $middleware->alias([
        //     'verify.origin' => \App\Http\Middleware\VerifyFrontendOrigin::class,
        // ]);
        $middleware->alias([
            'open.cors' => \App\Http\Middleware\OpenCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
