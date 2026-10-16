<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories(): void
    {
        Category::create(['name' => 'Categoría Test', 'description' => 'Test Desc']);

        $response = $this->getJson('/api/v1/categories');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'description', 'created_at']
                ]
            ]);
    }

    public function test_can_create_category(): void
    {
        $payload = [
            'name'        => 'Electrónica y Audio',
            'description' => 'Equipos de sonido y componentes',
        ];

        $response = $this->postJson('/api/v1/categories', $payload);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Electrónica y Audio');

        $this->assertDatabaseHas('categories', [
            'name' => 'Electrónica y Audio',
        ]);
    }

    public function test_category_validation_requires_name(): void
    {
        $response = $this->postJson('/api/v1/categories', [
            'description' => 'Sin nombre',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_category_name_must_be_unique(): void
    {
        Category::create(['name' => 'Duplicada']);

        $response = $this->postJson('/api/v1/categories', [
            'name' => 'Duplicada',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_can_update_category(): void
    {
        $category = Category::create(['name' => 'Original', 'description' => 'Desc']);

        $response = $this->putJson("/api/v1/categories/{$category->id}", [
            'name'        => 'Actualizada',
            'description' => 'Nueva desc',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Actualizada');

        $this->assertDatabaseHas('categories', [
            'id'   => $category->id,
            'name' => 'Actualizada',
        ]);
    }

    public function test_can_delete_category(): void
    {
        $category = Category::create(['name' => 'Para borrar']);

        $response = $this->deleteJson("/api/v1/categories/{$category->id}");

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
