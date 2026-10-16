<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user() ?? auth('sanctum')->user();

        if ($user && $user->isClient()) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado. Se requieren permisos de administrador para realizar esta acción.',
                'code'    => Response::HTTP_FORBIDDEN,
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
