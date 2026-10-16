<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_dashboard_summary_statistics(): void
    {
        $category = Category::create([
            'name' => 'Categoría Test',
        ]);

        Product::create([
            'name'        => 'Producto 1',
            'price'       => 100.00,
            'stock'       => 10,
            'category_id' => $category->id,
        ]);

        Product::create([
            'name'        => 'Producto 2',
            'price'       => 50.00,
            'stock'       => 5,
            'category_id' => $category->id,
        ]);

        $response = $this->getJson('/api/v1/dashboard/stats');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.total_products', 2)
            ->assertJsonPath('data.total_categories', 1)
            ->assertJsonPath('data.total_stock', 15)
            ->assertJsonPath('data.total_inventory_value', 1250); // 100*10 + 50*5 = 1000 + 250 = 1250
    }
}
