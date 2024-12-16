<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        api: __DIR__ .'/../routes/api.php'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        $middleware->validateCsrfTokens(
            except:[
                '/api/*',

            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();


    // create([
        
    //         "first_name"=>"ali",
    //         "last_name"=>"mohamed",
    //         "location"=>"city one"
    //         ,"email"=>"herllo@gmail.com",
    //         "password"=>"12345",
    //         "image"=>"file.jpg"
        
        
    // ])