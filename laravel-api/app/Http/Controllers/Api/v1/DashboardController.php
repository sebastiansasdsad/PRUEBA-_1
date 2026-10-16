<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    #[OA\Get(
        path: '/dashboard/stats',
        operationId: 'getDashboardStats',
        tags: ['Dashboard'],
        summary: 'Obtener métricas y estadísticas',
        description: 'Retorna el resumen de productos totales, categorías, valor total de inventario y actividad reciente.'
    )]
    #[OA\Response(
        response: 200,
        description: 'Estadísticas calculadas exitosamente'
    )]
    public function stats(): JsonResponse
    {
        $stats = $this->dashboardService->getSummaryStats();
        return $this->successResponse(
            $stats,
            'Estadísticas obtenidas correctamente'
        );
    }
}
