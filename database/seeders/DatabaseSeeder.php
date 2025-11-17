<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear roles y usuario admin
        $this->call(\Database\Seeders\RolesSeeder::class);
        // Datos de prueba: productos
        $this->call(\Database\Seeders\ProductosSeeder::class);
        // Rellenar inventario
        $this->call(\Database\Seeders\InventarioSeeder::class);
        // Opcional: crear datos de ejemplo
        // User::factory(10)->create();
    }
}
