<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrador
        User::updateOrCreate(
            ['email' => 'admin@inventariopro.com'],
            [
                'name'     => 'Administrador del Sistema',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        // Cliente
        User::updateOrCreate(
            ['email' => 'cliente@inventariopro.com'],
            [
                'name'     => 'Cliente Demo',
                'password' => Hash::make('cliente123'),
                'role'     => 'cliente',
            ]
        );
    }
}
