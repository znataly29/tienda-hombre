<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Carrito;
use App\Models\Producto;

class MergeSessionCart
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        $sessionCart = session()->get('cart', []);
        if (empty($sessionCart)) return;

        foreach ($sessionCart as $s) {
            $productoId = $s['producto_id'] ?? null;
            $cantidad = $s['cantidad'] ?? 1;
            if (! $productoId) continue;

            $producto = Producto::find($productoId);
            $precio = $producto ? $producto->precio : ($s['precio_unitario'] ?? 0);

            $item = Carrito::where('usuario_id', $user->id)->where('producto_id', $productoId)->first();
            if ($item) {
                $item->increment('cantidad', $cantidad);
            } else {
                Carrito::create([
                    'usuario_id' => $user->id,
                    'producto_id' => $productoId,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                ]);
            }
        }

        session()->forget('cart');
    }
}
