<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Uniform JSON error formatting for all API requests
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                $previous = $e->getPrevious();
                $message = $previous instanceof ModelNotFoundException
                    ? 'El recurso solicitado no fue encontrado.'
                    : 'Ruta no encontrada.';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'code'    => Response::HTTP_NOT_FOUND,
                ], Response::HTTP_NOT_FOUND);
            }
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Los datos proporcionados no son válidos.',
                    'errors'  => $e->errors(),
                    'code'    => Response::HTTP_UNPROCESSABLE_ENTITY,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*') && !($e instanceof ValidationException) && !($e instanceof NotFoundHttpException)) {
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
                if ($statusCode < 100 || $statusCode > 599) {
                    $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                }

                return response()->json([
                    'success' => false,
                    'message' => config('app.debug') ? $e->getMessage() : 'Ocurrió un error inesperado en el servidor.',
                    'code'    => $statusCode,
                ], $statusCode);
            }
        });
    })->create();
