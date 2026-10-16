<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponse;

    #[OA\Post(
        path: '/auth/login',
        operationId: 'authLogin',
        tags: ['Autenticación'],
        summary: 'Iniciar sesión (Admin o Cliente)',
        description: 'Autentica las credenciales del usuario y retorna el perfil con su rol y un token de acceso Bearer (Laravel Sanctum).'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@inventariopro.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Autenticación exitosa',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Inicio de sesión exitoso'),
                new OA\Property(property: 'data', type: 'object',
                    properties: [
                        new OA\Property(property: 'user', type: 'object'),
                        new OA\Property(property: 'token', type: 'string', example: '1|xyz...'),
                    ]
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Credenciales inválidas'
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse(
                'Las credenciales proporcionadas son incorrectas.',
                Response::HTTP_UNAUTHORIZED
            );
        }

        // Revoke existing tokens for a clean session
        $user->tokens()->delete();

        // Generate Sanctum access token
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->successResponse([
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
            'token' => $token,
        ], 'Inicio de sesión exitoso');
    }

    #[OA\Get(
        path: '/auth/me',
        operationId: 'authMe',
        tags: ['Autenticación'],
        summary: 'Obtener información del usuario autenticado',
        description: 'Retorna el perfil del usuario autenticado mediante Bearer token.'
    )]
    #[OA\Response(
        response: 200,
        description: 'Datos del usuario autenticado'
    )]
    #[OA\Response(
        response: 401,
        description: 'No autorizado'
    )]
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->successResponse([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ], 'Perfil de usuario obtenido');
    }

    #[OA\Post(
        path: '/auth/logout',
        operationId: 'authLogout',
        tags: ['Autenticación'],
        summary: 'Cerrar sesión',
        description: 'Revoca el token de acceso actual del usuario autenticado.'
    )]
    #[OA\Response(
        response: 200,
        description: 'Sesión cerrada exitosamente'
    )]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Sesión cerrada exitosamente');
    }
}
