<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Compra;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Mostrar formulario de checkout con resumen del pedido.
     */
    public function show(Request $request)
    {
        $usuario = $request->user();

        // Obtener carrito del usuario
        $carrito = Carrito::with('producto')->where('usuario_id', $usuario->id)->get();

        if ($carrito->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        // Calcular totales
        $subtotal = $carrito->sum(fn($item) => $item->cantidad * $item->precio_unitario);
        $envio = 5000; // Envío fijo (puedes hacer dinámico después)
        $total = $subtotal + $envio;

        return view('checkout', compact('carrito', 'usuario', 'subtotal', 'envio', 'total'));
    }

    /**
     * Procesar la confirmación de compra.
     */
    public function finalizar(Request $request)
    {
        $request->validate([
            'terminos' => 'required|accepted',
        ], [
            'terminos.required' => 'Debes aceptar los términos y condiciones.',
            'terminos.accepted' => 'Debes aceptar los términos y condiciones.',
        ]);

        $usuario = $request->user();

        // Obtener carrito del usuario
        $carrito = Carrito::with('producto')->where('usuario_id', $usuario->id)->get();

        if ($carrito->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        // Calcular totales
        $subtotal = $carrito->sum(fn($item) => $item->cantidad * $item->precio_unitario);
        $envio = 5000;
        $total = $subtotal + $envio;

        // Crear compra con detalles de items
        $detalles = $carrito->map(function ($item) {
            return [
                'nombre' => $item->producto->nombre,
                'cantidad' => $item->cantidad,
                'precio_unitario' => $item->precio_unitario,
                'talla' => $item->producto->talla ?? '',
            ];
        })->toArray();

        $compra = Compra::create([
            'usuario_id' => $usuario->id,
            'monto_total' => $total,
            'estado' => 'completada',
            'detalles' => $detalles,
        ]);

        // Asociar items del carrito a la compra (guardar en tabla intermedia si existe)
        // Si tienes una relación many-to-many, hazlo aquí:
        // $compra->productos()->attach($carrito->pluck('producto_id')->toArray());

        // Limpiar carrito
        Carrito::where('usuario_id', $usuario->id)->delete();

        return redirect()->route('compra.confirmada')->with('compra_id', $compra->id);
    }

    /**
     * Mostrar mensaje de compra confirmada.
     */
    public function confirmada(Request $request)
    {
        $compra_id = session('compra_id');

        if (!$compra_id) {
            return redirect()->route('catalogo')->with('error', 'No hay compra para mostrar.');
        }

        $compra = Compra::findOrFail($compra_id);

        return view('compra-confirmada', compact('compra'));
    }
}
