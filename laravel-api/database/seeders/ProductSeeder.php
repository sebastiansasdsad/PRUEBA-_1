<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $urbanClothing = Category::where('name', 'Ropa Urbana')->first();
        $footwear = Category::where('name', 'Calzado')->first();
        $accessories = Category::where('name', 'Accesorios')->first();
        $electronics = Category::where('name', 'Electrónica')->first();

        $products = [
            // Example requested by user
            [
                'name'        => 'Camiseta Overskull',
                'description' => 'Camiseta negra edición limitada',
                'price'       => 89.90,
                'stock'       => 50,
                'category_id' => $urbanClothing?->id ?? 1,
            ],
            [
                'name'        => 'Hoodie Street Cyberpunk',
                'description' => 'Sudadera con capucha, diseño futurista en serigrafía de alta densidad.',
                'price'       => 149.50,
                'stock'       => 25,
                'category_id' => $urbanClothing?->id ?? 1,
            ],
            [
                'name'        => 'Pantalón Cargo Tactical',
                'description' => 'Pantalón con múltiples bolsillos reforzados y tela ripstop.',
                'price'       => 119.00,
                'stock'       => 35,
                'category_id' => $urbanClothing?->id ?? 1,
            ],
            [
                'name'        => 'Sneakers Retro Neon',
                'description' => 'Zapatillas deportivas suela gruesa con detalles fluorescentes.',
                'price'       => 220.00,
                'stock'       => 18,
                'category_id' => $footwear?->id ?? 2,
            ],
            [
                'name'        => 'Gorra Snapback Midnight',
                'description' => 'Gorra ajustable con bordado frontal metálico y visera plana.',
                'price'       => 45.00,
                'stock'       => 60,
                'category_id' => $accessories?->id ?? 3,
            ],
            [
                'name'        => 'Mochila Impermeable Modular',
                'description' => 'Compartimento acolchado para laptop 16" y correas ergonómicas.',
                'price'       => 85.00,
                'stock'       => 8,
                'category_id' => $accessories?->id ?? 3,
            ],
            [
                'name'        => 'Auriculares Wireless Studio Pro',
                'description' => 'Cancelación activa de ruido, 40 horas de autonomía y códec Hi-Res.',
                'price'       => 299.99,
                'stock'       => 15,
                'category_id' => $electronics?->id ?? 4,
            ],
        ];

        foreach ($products as $prod) {
            Product::firstOrCreate(['name' => $prod['name']], $prod);
        }
    }
}
