<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_is_logged_when_category_is_created_and_updated(): void
    {
        $category = Category::create([
            'name'        => 'Categoría Auditada',
            'description' => 'Descripción inicial',
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name'     => 'category',
            'subject_type' => Category::class,
            'subject_id'   => $category->id,
            'description'  => 'created',
        ]);

        $category->update(['name' => 'Categoría Modificada']);

        $this->assertDatabaseHas('activity_log', [
            'log_name'     => 'category',
            'subject_type' => Category::class,
            'subject_id'   => $category->id,
            'description'  => 'updated',
        ]);
    }

    public function test_activity_is_logged_when_product_is_created(): void
    {
        $category = Category::create(['name' => 'Ropa']);

        $product = Product::create([
            'name'        => 'Camiseta Overskull',
            'description' => 'Camiseta negra edición limitada',
            'price'       => 89.90,
            'stock'       => 50,
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name'     => 'product',
            'subject_type' => Product::class,
            'subject_id'   => $product->id,
            'description'  => 'created',
        ]);

        $activity = Activity::where('subject_type', Product::class)
            ->where('subject_id', $product->id)
            ->first();

        $this->assertNotNull($activity);
        $this->assertEquals('Camiseta Overskull', $activity->properties['attributes']['name']);
    }
}
