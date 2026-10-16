<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::create([
            'name'     => 'Administrador del Sistema',
            'email'    => 'admin@inventariopro.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        $this->client = User::create([
            'name'     => 'Cliente Demo',
            'email'    => 'cliente@inventariopro.com',
            'password' => Hash::make('cliente123'),
            'role'     => 'cliente',
        ]);
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'admin@inventariopro.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'admin@inventariopro.com')
            ->assertJsonPath('data.user.role', 'admin')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'role'],
                    'token',
                ],
            ]);
    }

    public function test_client_can_login_and_receives_client_role(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'cliente@inventariopro.com',
            'password' => 'cliente123',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'cliente@inventariopro.com')
            ->assertJsonPath('data.user.role', 'cliente');
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'admin@inventariopro.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Las credenciales proporcionadas son incorrectas.');
    }

    public function test_client_cannot_create_product(): void
    {
        $category = Category::create(['name' => 'Categoría Test']);
        $token = $this->client->createToken('client-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/products', [
                'name'        => 'Producto Prohibido',
                'price'       => 10.00,
                'stock'       => 5,
                'category_id' => $category->id,
            ]);

        $response->assertStatus(403)
            ->assertJsonPath('success', false)
            ->assertJsonPath('code', 403);
    }

    public function test_can_fetch_authenticated_user_profile(): void
    {
        $token = $this->admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/auth/me');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', 'admin@inventariopro.com')
            ->assertJsonPath('data.role', 'admin');
    }

    public function test_can_logout_and_revoke_token(): void
    {
        $token = $this->admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/logout');

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertCount(0, $this->admin->fresh()->tokens);
    }
}
