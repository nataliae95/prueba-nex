<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'El registro solicitado no existe.'
            ], Response::HTTP_NOT_FOUND);
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'error' => 'La ruta de la API que intentas consultar no existe.'
                ], Response::HTTP_NOT_FOUND);
            }
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            // Validamos que la petición sea de la API o espere un JSON
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => Response::HTTP_FORBIDDEN,
                    'error' => 'Acceso denegado: No tienes los permisos necesarios para realizar esta acción.'
                ], Response::HTTP_FORBIDDEN);
            }
        });

        $exceptions->render(function (QueryException $e, Request $request) {
            +Log::error('Error de Base de Datos: ' . $e->getMessage());

            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Hubo un problema al procesar la información en la base de datos. Revisar logs'
                ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
            }
        });
    })->create();
