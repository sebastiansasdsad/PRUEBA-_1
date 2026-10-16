<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "API Inventario Pro - Productos y Categorías",
    description: "Documentación interactiva OpenAPI/Swagger para el sistema de gestión de productos y categorías construido con Laravel 12."
)]
#[OA\Server(
    url: "/api/v1",
    description: "API v1 Base Endpoint"
)]
#[OA\Tag(
    name: "Productos",
    description: "Operaciones CRUD para la gestión de productos"
)]
#[OA\Tag(
    name: "Categorías",
    description: "Operaciones CRUD para la gestión de categorías"
)]
#[OA\Tag(
    name: "Dashboard",
    description: "Métricas y estadísticas del inventario"
)]
abstract class Controller
{
    //
}
