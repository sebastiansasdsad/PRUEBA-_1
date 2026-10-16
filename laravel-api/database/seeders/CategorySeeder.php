<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Ropa Urbana',
                'description' => 'Prendas de estilo urbano, streetwear y ediciones especiales.',
            ],
            [
                'name'        => 'Calzado',
                'description' => 'Zapatillas deportivas, casuales y de colección.',
            ],
            [
                'name'        => 'Accesorios',
                'description' => 'Gorras, mochilas, cadenas y complementos de moda.',
            ],
            [
                'name'        => 'Electrónica',
                'description' => 'Gadgets, auriculares y tecnología cotidiana.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
