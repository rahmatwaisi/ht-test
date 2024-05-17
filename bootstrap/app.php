<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response as HttpStatusCode;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withBroadcasting(
        __DIR__.'/../routes/channels.php',
        ['prefix' => 'api', 'middleware' => ['api', 'auth:sanctum']],
    )
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(fn($request, $ex) => true);
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $ex) {
            return response()->json([
                'result' => [
                    'message' => $ex->getMessage()
                ]
            ], $ex->getStatusCode());
        });

        $exceptions->render(function ( AuthenticationException $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ], HttpStatusCode::HTTP_UNAUTHORIZED);
        });

    })->create();
