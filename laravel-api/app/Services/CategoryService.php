<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Service class handling business logic for Categories.
 */
class CategoryService
{
    /**
     * Retrieve all categories with products count.
     *
     * @return Collection<int, Category>
     */
    public function getAllCategories(): Collection
    {
        return Category::withCount('products')
            ->orderBy('name')
            ->get();
    }

    /**
     * Find a category by its ID or fail.
     *
     * @param int $id
     * @return Category
     */
    public function getCategoryById(int $id): Category
    {
        return Category::withCount('products')->findOrFail($id);
    }

    /**
     * Create a new category within a database transaction.
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        return DB::transaction(function () use ($data) {
            return Category::create($data);
        });
    }

    /**
     * Update an existing category within a database transaction.
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function updateCategory(Category $category, array $data): Category
    {
        return DB::transaction(function () use ($category, $data) {
            $category->update($data);
            return $category->fresh();
        });
    }

    /**
     * Delete a category within a database transaction.
     *
     * @param Category $category
     * @return bool
     */
    public function deleteCategory(Category $category): bool
    {
        return DB::transaction(function () use ($category) {
            return $category->delete();
        });
    }
}
