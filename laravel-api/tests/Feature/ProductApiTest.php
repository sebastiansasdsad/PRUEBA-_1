<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::create([
            'name'        => 'Ropa Urbana',
            'description' => 'Streetwear de calidad',
        ]);
    }

    public function test_can_list_products(): void
    {
        Product::create([
            'name'        => 'Camiseta Overskull',
            'description' => 'Camiseta negra edición limitada',
            'price'       => 89.90,
            'stock'       => 50,
            'category_id' => $this->category->id,
        ]);

        $response = $this->getJson('/api/v1/products');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Camiseta Overskull');
    }

    public function test_can_create_product_with_example_data(): void
    {
        $payload = [
            'name'        => 'Camiseta Overskull',
            'description' => 'Camiseta negra edición limitada',
            'price'       => 89.90,
            'stock'       => 50,
            'category_id' => $this->category->id,
        ];

        $response = $this->postJson('/api/v1/products', $payload);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Camiseta Overskull')
            ->assertJsonPath('data.price', 89.9)
            ->assertJsonPath('data.stock', 50)
            ->assertJsonPath('data.category_id', $this->category->id);

        $this->assertDatabaseHas('products', [
            'name'  => 'Camiseta Overskull',
            'stock' => 50,
        ]);
    }

    public function test_product_validation_fails_with_invalid_data(): void
    {
        $response = $this->postJson('/api/v1/products', [
            'name'        => '',
            'price'       => -10,
            'stock'       => 'cinco',
            'category_id' => 9999, // Non-existent category
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'stock', 'category_id']);
    }

    public function test_can_update_product(): void
    {
        $product = Product::create([
            'name'        => 'Original Name',
            'description' => 'Desc',
            'price'       => 50.00,
            'stock'       => 10,
            'category_id' => $this->category->id,
        ]);

        $response = $this->putJson("/api/v1/products/{$product->id}", [
            'name'        => 'Updated Name',
            'description' => 'Updated Desc',
            'price'       => 75.50,
            'stock'       => 20,
            'category_id' => $this->category->id,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Updated Name')
            ->assertJsonPath('data.price', 75.5);

        $this->assertDatabaseHas('products', [
            'id'    => $product->id,
            'name'  => 'Updated Name',
            'price' => 75.50,
        ]);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::create([
            'name'        => 'To Delete',
            'price'       => 10.00,
            'stock'       => 1,
            'category_id' => $this->category->id,
        ]);

        $response = $this->deleteJson("/api/v1/products/{$product->id}");

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_can_filter_products_by_search_query(): void
    {
        Product::create([
            'name'        => 'Camiseta Overskull',
            'price'       => 89.90,
            'stock'       => 50,
            'category_id' => $this->category->id,
        ]);

        Product::create([
            'name'        => 'Pantalón Cargo',
            'price'       => 119.00,
            'stock'       => 20,
            'category_id' => $this->category->id,
        ]);

        $response = $this->getJson('/api/v1/products?search=Overskull');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Camiseta Overskull');
    }
}
