<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Inventario;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los productos
        $productos = Producto::all();

        foreach ($productos as $producto) {
            // Crear registro de inventario con cantidad aleatoria
            Inventario::create([
                'producto_id' => $producto->id,
                'cantidad' => rand(5, 50), // Stock aleatorio entre 5 y 50
            ]);
        }
    }
}
