<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Standardized JSON response trait for API controllers.
 * Ensures consistent structure across all endpoints:
 * {
 *   "success": true|false,
 *   "message": "Human readable message",
 *   "data": [...],
 *   "errors": {...},
 *   "code": 200
 * }
 */
trait ApiResponse
{
    /**
     * Return a standardized success JSON response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse(mixed $data = null, string $message = 'Operación exitosa', int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'code'    => $code,
        ], $code);
    }

    /**
     * Return a standardized error JSON response.
     *
     * @param string $message
     * @param int $code
     * @param mixed $errors
     * @return JsonResponse
     */
    protected function errorResponse(string $message = 'Error en la operación', int $code = Response::HTTP_BAD_REQUEST, mixed $errors = null): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
            'code'    => $code,
        ];

        if (!is_null($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }
}
