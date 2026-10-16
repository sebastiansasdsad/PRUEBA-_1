<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

/**
 * Service class for computing dashboard statistics.
 */
class DashboardService
{
    /**
     * Compute summary statistics for dashboard display.
     *
     * @return array
     */
    public function getSummaryStats(): array
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalStock = (int) Product::sum('stock');
        $totalInventoryValue = (float) Product::select(DB::raw('SUM(price * stock) as total_val'))
            ->value('total_val') ?? 0.0;

        $recentProducts = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        $categoriesWithCount = Category::withCount('products')
            ->orderByDesc('products_count')
            ->take(6)
            ->get();

        return [
            'total_products'        => $totalProducts,
            'total_categories'      => $totalCategories,
            'total_stock'           => $totalStock,
            'total_inventory_value' => round($totalInventoryValue, 2),
            'recent_products'       => $recentProducts,
            'categories_with_count' => $categoriesWithCount,
        ];
    }
}
