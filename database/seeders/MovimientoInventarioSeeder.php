<?php

namespace Database\Seeders;

use App\Models\MovimientoInventario;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class MovimientoInventarioSeeder extends Seeder
{
    public function run(): void
    {
        $productos = Producto::limit(5)->get();

        if ($productos->isEmpty()) {
            return;
        }

        $movimientos = [
            ['tipo' => 'entrada', 'cantidad' => 10, 'motivo' => 'Compra al proveedor', 'observacion' => 'Llegada de inventario inicial'],
            ['tipo' => 'salida', 'cantidad' => 2, 'motivo' => 'Compra #1', 'observacion' => 'Venta realizada'],
            ['tipo' => 'salida', 'cantidad' => 1, 'motivo' => 'Compra #1', 'observacion' => 'Venta realizada'],
            ['tipo' => 'entrada', 'cantidad' => 5, 'motivo' => 'Ajuste de inventario', 'observacion' => 'Reconteo de stock'],
            ['tipo' => 'salida', 'cantidad' => 3, 'motivo' => 'Compra #2', 'observacion' => 'Venta realizada'],
        ];

        foreach ($productos as $index => $producto) {
            if (isset($movimientos[$index])) {
                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    ...$movimientos[$index],
                ]);
            }
        }
    }
}
