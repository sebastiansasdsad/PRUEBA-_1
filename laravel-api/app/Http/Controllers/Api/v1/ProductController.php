<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ProductService $productService
    ) {}

    #[OA\Get(
        path: '/products',
        operationId: 'getProductsList',
        tags: ['Productos'],
        summary: 'Listar productos',
        description: 'Retorna el listado de productos con soporte para búsqueda por texto y filtrado por categoría.'
    )]
    #[OA\Parameter(
        name: 'search',
        in: 'query',
        required: false,
        description: 'Término de búsqueda por nombre o descripción',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'category_id',
        in: 'query',
        required: false,
        description: 'Filtrar por ID de categoría',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: 'Listado obtenido exitosamente'
    )]
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'category_id']);
        $products = $this->productService->getAllProducts($filters);

        return $this->successResponse(
            ProductResource::collection($products),
            'Productos obtenidos correctamente'
        );
    }

    #[OA\Post(
        path: '/products',
        operationId: 'storeProduct',
        tags: ['Productos'],
        summary: 'Crear un nuevo producto',
        description: 'Registra un nuevo producto asociado a una categoría existente.'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'price', 'stock', 'category_id'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Camiseta Overskull'),
                new OA\Property(property: 'description', type: 'string', example: 'Camiseta negra edición limitada'),
                new OA\Property(property: 'price', type: 'number', format: 'float', example: 89.90),
                new OA\Property(property: 'stock', type: 'integer', example: 50),
                new OA\Property(property: 'category_id', type: 'integer', example: 1),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Producto creado exitosamente'
    )]
    #[OA\Response(
        response: 422,
        description: 'Error de validación en los datos proporcionados'
    )]
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());
        return $this->successResponse(
            new ProductResource($product),
            'Producto creado exitosamente',
            Response::HTTP_CREATED
        );
    }

    #[OA\Get(
        path: '/products/{id}',
        operationId: 'getProductById',
        tags: ['Productos'],
        summary: 'Obtener detalles de un producto',
        description: 'Retorna la información completa de un producto por su ID.'
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID del producto',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: 'Producto encontrado'
    )]
    #[OA\Response(
        response: 404,
        description: 'Producto no encontrado'
    )]
    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);
        return $this->successResponse(
            new ProductResource($product),
            'Producto obtenido correctamente'
        );
    }

    #[OA\Put(
        path: '/products/{id}',
        operationId: 'updateProduct',
        tags: ['Productos'],
        summary: 'Actualizar un producto existente',
        description: 'Actualiza los datos de un producto existente.'
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID del producto a actualizar',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'price', 'stock', 'category_id'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Camiseta Overskull V2'),
                new OA\Property(property: 'description', type: 'string', example: 'Camiseta negra con nuevo diseño'),
                new OA\Property(property: 'price', type: 'number', format: 'float', example: 99.90),
                new OA\Property(property: 'stock', type: 'integer', example: 40),
                new OA\Property(property: 'category_id', type: 'integer', example: 1),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Producto actualizado exitosamente'
    )]
    #[OA\Response(
        response: 404,
        description: 'Producto no encontrado'
    )]
    #[OA\Response(
        response: 422,
        description: 'Error de validación'
    )]
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $updatedProduct = $this->productService->updateProduct($product, $request->validated());
        return $this->successResponse(
            new ProductResource($updatedProduct),
            'Producto actualizado exitosamente'
        );
    }

    #[OA\Delete(
        path: '/products/{id}',
        operationId: 'deleteProduct',
        tags: ['Productos'],
        summary: 'Eliminar un producto',
        description: 'Elimina un producto del catálogo.'
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID del producto a eliminar',
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: 'Producto eliminado exitosamente'
    )]
    #[OA\Response(
        response: 404,
        description: 'Producto no encontrado'
    )]
    public function destroy(Product $product): JsonResponse
    {
        $this->productService->deleteProduct($product);
        return $this->successResponse(
            null,
            'Producto eliminado exitosamente'
        );
    }
}
