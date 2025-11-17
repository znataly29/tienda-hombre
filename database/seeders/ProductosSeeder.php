<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductosSeeder extends Seeder
{
    public function run(): void
    {
        $ejemplos = [
            // Talla M - Mínimo 3 productos
            ['nombre'=>'Camiseta M blanca','descripcion'=>'Camiseta algodón 100%','precio'=>19.99,'categoria'=>'Camisetas','talla'=>'M'],
            ['nombre'=>'Polo M azul','descripcion'=>'Polo piqué','precio'=>29.90,'categoria'=>'Camisetas','talla'=>'M'],
            ['nombre'=>'Camisa M formal','descripcion'=>'Camisa para oficina','precio'=>34.90,'categoria'=>'Camisas','talla'=>'M'],
            ['nombre'=>'Sudadera M gris','descripcion'=>'Sudadera cómoda','precio'=>39.99,'categoria'=>'Sudaderas','talla'=>'M'],
            ['nombre'=>'Chaqueta M deportiva','descripcion'=>'Chaqueta para deportes','precio'=>84.50,'categoria'=>'Chaquetas','talla'=>'M'],
            ['nombre'=>'Shorts M deportivos','descripcion'=>'Shorts deportivos','precio'=>29.90,'categoria'=>'Shorts','talla'=>'M'],
            
            // Talla L - Mínimo 3 productos
            ['nombre'=>'Camiseta L negra','descripcion'=>'Camiseta algodón 100%','precio'=>19.99,'categoria'=>'Camisetas','talla'=>'L'],
            ['nombre'=>'Polo L blanco','descripcion'=>'Polo piqué','precio'=>29.90,'categoria'=>'Camisetas','talla'=>'L'],
            ['nombre'=>'Camisa L azul','descripcion'=>'Camisa para oficina','precio'=>34.90,'categoria'=>'Camisas','talla'=>'L'],
            ['nombre'=>'Sudadera L negra','descripcion'=>'Sudadera premium','precio'=>49.99,'categoria'=>'Sudaderas','talla'=>'L'],
            ['nombre'=>'Chaqueta L negra','descripcion'=>'Chaqueta cortaviento','precio'=>79.50,'categoria'=>'Chaquetas','talla'=>'L'],
            ['nombre'=>'Shorts L azul','descripcion'=>'Shorts de playa','precio'=>22.90,'categoria'=>'Shorts','talla'=>'L'],
            ['nombre'=>'Sudadera sin capucha L','descripcion'=>'Sudadera casual','precio'=>34.99,'categoria'=>'Sudaderas','talla'=>'L'],
            
            // Talla XL - Mínimo 3 productos
            ['nombre'=>'Camiseta XL premium','descripcion'=>'Camiseta algodón premium','precio'=>39.90,'categoria'=>'Camisetas','talla'=>'XL'],
            ['nombre'=>'Polo XL rojo','descripcion'=>'Polo deportivo','precio'=>34.90,'categoria'=>'Camisetas','talla'=>'XL'],
            ['nombre'=>'Sudadera XL gris','descripcion'=>'Sudadera premium','precio'=>49.99,'categoria'=>'Sudaderas','talla'=>'XL'],
            ['nombre'=>'Chaqueta XL azul','descripcion'=>'Chaqueta impermeable','precio'=>89.99,'categoria'=>'Chaquetas','talla'=>'XL'],
            
            // Talla 30 (Pantalones) - Mínimo 3 productos
            ['nombre'=>'Pantalón 30 clásico','descripcion'=>'Pantalón azul oscuro','precio'=>49.90,'categoria'=>'Pantalones','talla'=>'30'],
            ['nombre'=>'Pantalón 30 slim fit','descripcion'=>'Pantalón slim fit negro','precio'=>54.90,'categoria'=>'Pantalones','talla'=>'30'],
            ['nombre'=>'Pantalón 30 formal','descripcion'=>'Pantalón para oficina','precio'=>55.00,'categoria'=>'Pantalones','talla'=>'30'],
            ['nombre'=>'Pantalón 30 chino','descripcion'=>'Chino casual beige','precio'=>45.00,'categoria'=>'Pantalones','talla'=>'30'],
            
            // Talla 32 (Pantalones) - Mínimo 3 productos
            ['nombre'=>'Pantalón 32 rotos','descripcion'=>'Pantalón con look desgastado','precio'=>59.90,'categoria'=>'Pantalones','talla'=>'32'],
            ['nombre'=>'Pantalón 32 chino','descripcion'=>'Chino casual','precio'=>45.00,'categoria'=>'Pantalones','talla'=>'32'],
            ['nombre'=>'Pantalón 32 clásico','descripcion'=>'Pantalón clásico azul','precio'=>49.90,'categoria'=>'Pantalones','talla'=>'32'],
            ['nombre'=>'Pantalón 32 premium','descripcion'=>'Pantalón premium gris','precio'=>69.90,'categoria'=>'Pantalones','talla'=>'32'],
            
            // Talla 34 (Pantalones) - Mínimo 3 productos
            ['nombre'=>'Pantalón 34 vintage','descripcion'=>'Pantalón talle medio','precio'=>64.90,'categoria'=>'Pantalones','talla'=>'34'],
            ['nombre'=>'Pantalón 34 chino','descripcion'=>'Chino casual gris','precio'=>45.00,'categoria'=>'Pantalones','talla'=>'34'],
            ['nombre'=>'Pantalón 34 formal','descripcion'=>'Pantalón para oficina','precio'=>55.00,'categoria'=>'Pantalones','talla'=>'34'],
            ['nombre'=>'Pantalón 34 negro','descripcion'=>'Pantalón negro clásico','precio'=>52.90,'categoria'=>'Pantalones','talla'=>'34'],
            
            // Talla 36 (Pantalones) - Mínimo 3 productos
            ['nombre'=>'Pantalón 36 formal','descripcion'=>'Pantalón para oficina','precio'=>55.00,'categoria'=>'Pantalones','talla'=>'36'],
            ['nombre'=>'Pantalón 36 clásico','descripcion'=>'Pantalón clásico azul','precio'=>49.90,'categoria'=>'Pantalones','talla'=>'36'],
            ['nombre'=>'Pantalón 36 confort','descripcion'=>'Pantalón cómodo','precio'=>52.90,'categoria'=>'Pantalones','talla'=>'36'],
            ['nombre'=>'Pantalón 36 chino','descripcion'=>'Chino casual','precio'=>45.00,'categoria'=>'Pantalones','talla'=>'36'],
        ];

        foreach ($ejemplos as $p) {
            Producto::firstOrCreate(['nombre' => $p['nombre']], $p);
        }
    }
}
