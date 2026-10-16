<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected CategoryService $categoryService
    ) {}

    #[OA\Get(
        path: '/categories',
        operationId: 'getCategoriesList',
        tags: ['Categorías'],
        summary: 'Listar todas las categorías',
        description: 'Retorna el listado completo de categorías con la cantidad de productos asociados.'
    )]
    #[OA\Response(
        response: 200,
        description: 'Listado obtenido exitosamente'
    )]
    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();
        return $this->successResponse(
            CategoryResource::collection($categories),
            'Categorías obtenidas correctamente'
        );
    }

    #[OA\Post(
        path: '/categories',
        operationId: 'storeCategory',
        tags: ['Categorías'],
        summary: 'Crear una nueva categoría',
        description: 'Registra una nueva categoría en la base de datos.'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Ropa Urbana'),
                new OA\Property(property: 'description', type: 'string', example: 'Prendas de vestir streetwear'),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Categoría creada exitosamente'
    )]
    #[OA\Response(
        response: 422,
        description: 'Error de validación en los datos proporcionados'
    )]
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->createCategory($request->validated());
        return $this->successResponse(
            new CategoryResource($category),
            'Categoría creada exitosamente',
            Response::HTTP_CREATED
        );
    }

    #[OA\Get(
        path: '/categories/{id}',
        operationId: 'getCategoryById',
        tags: ['Categorías'],
        summary: 'Obtener detalles de una categoría',
        description: 'Retorna una categoría específica por su ID.'
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID de la categoría',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: 'Categoría encontrada'
    )]
    #[OA\Response(
        response: 404,
        description: 'Categoría no encontrada'
    )]
    public function show(int $id): JsonResponse
    {
        $category = $this->categoryService->getCategoryById($id);
        return $this->successResponse(
            new CategoryResource($category),
            'Categoría obtenida correctamente'
        );
    }

    #[OA\Put(
        path: '/categories/{id}',
        operationId: 'updateCategory',
        tags: ['Categorías'],
        summary: 'Actualizar una categoría existente',
        description: 'Actualiza los datos de una categoría específica.'
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID de la categoría a actualizar',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Ropa Urbana Premium'),
                new OA\Property(property: 'description', type: 'string', example: 'Ediciones limitadas de streetwear'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Categoría actualizada exitosamente'
    )]
    #[OA\Response(
        response: 404,
        description: 'Categoría no encontrada'
    )]
    #[OA\Response(
        response: 422,
        description: 'Error de validación'
    )]
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $updatedCategory = $this->categoryService->updateCategory($category, $request->validated());
        return $this->successResponse(
            new CategoryResource($updatedCategory),
            'Categoría actualizada exitosamente'
        );
    }

    #[OA\Delete(
        path: '/categories/{id}',
        operationId: 'deleteCategory',
        tags: ['Categorías'],
        summary: 'Eliminar una categoría',
        description: 'Elimina la categoría especificada de la base de datos.'
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID de la categoría a eliminar',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: 'Categoría eliminada exitosamente'
    )]
    #[OA\Response(
        response: 404,
        description: 'Categoría no encontrada'
    )]
    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->deleteCategory($category);
        return $this->successResponse(
            null,
            'Categoría eliminada exitosamente'
        );
    }
}
