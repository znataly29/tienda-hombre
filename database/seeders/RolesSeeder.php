<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\User;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Rol::firstOrCreate(['nombre' => 'admin'], ['descripcion' => 'Administrador del sistema']);
        $cliente = Rol::firstOrCreate(['nombre' => 'cliente'], ['descripcion' => 'Cliente de la tienda']);

        // Crear usuario admin si no existe
        $u = User::where('email', 'admin@tienda.local')->first();
        if (! $u) {
            User::factory()->create([
                'name' => 'Administrador',
                'email' => 'admin@tienda.local',
                'rol_id' => $admin->id,
                'password' => bcrypt('password'),
            ]);
        }
    }
}
