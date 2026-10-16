<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Service class handling business logic for Products.
 */
class ProductService
{
    /**
     * Retrieve products with optional filtering by category and search term.
     *
     * @param array $filters
     * @return Collection<int, Product>
     */
    public function getAllProducts(array $filters = []): Collection
    {
        $query = Product::with('category')->latest();

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['search'])) {
            $searchTerm = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }

        return $query->get();
    }

    /**
     * Find a product by its ID or fail.
     *
     * @param int $id
     * @return Product
     */
    public function getProductById(int $id): Product
    {
        return Product::with('category')->findOrFail($id);
    }

    /**
     * Create a new product within a database transaction.
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $product = Product::create($data);
            return $product->load('category');
        });
    }

    /**
     * Update an existing product within a database transaction.
     *
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function updateProduct(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $product->update($data);
            return $product->fresh()->load('category');
        });
    }

    /**
     * Delete a product within a database transaction.
     *
     * @param Product $product
     * @return bool
     */
    public function deleteProduct(Product $product): bool
    {
        return DB::transaction(function () use ($product) {
            return $product->delete();
        });
    }
}
