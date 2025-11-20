<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Compra;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use App\Models\DireccionEnvio;
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

        // Obtener direcciones del usuario
        $direcciones = DireccionEnvio::where('usuario_id', $usuario->id)->get();

        return view('checkout', compact('carrito', 'usuario', 'subtotal', 'envio', 'total', 'direcciones'));
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

        // Validar que hay suficiente inventario para todos los items
        foreach ($carrito as $item) {
            $inventario = \App\Models\Inventario::where('producto_id', $item->producto_id)->first();
            
            if (!$inventario || $inventario->cantidad < $item->cantidad) {
                return redirect()->route('carrito.index')->with('error', 'Producto agotado');
            }
        }

        // Calcular totales
        $subtotal = $carrito->sum(fn($item) => $item->cantidad * $item->precio_unitario);
        $envio = 5000;
        $total = $subtotal + $envio;

        // Crear compra con detalles de items
        $detalles = $carrito->map(function ($item) {
            return [
                'producto_id' => $item->producto_id,
                'nombre' => $item->producto->nombre,
                'cantidad' => $item->cantidad,
                'precio_unitario' => $item->precio_unitario,
                'talla' => $item->producto->talla ?? '',
            ];
        })->toArray();

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $compra = Compra::create([
                'usuario_id' => $usuario->id,
                'monto_total' => $total,
                'estado' => 'completada',
                'detalles' => $detalles,
            ]);

            // Descontar inventario por cada producto comprado y registrar movimiento
            foreach ($carrito as $item) {
                $inventario = \App\Models\Inventario::where('producto_id', $item->producto_id)->first();
                if ($inventario) {
                    $inventario->decrement('cantidad', $item->cantidad);
                    
                    // Registrar movimiento de salida
                    \App\Models\MovimientoInventario::create([
                        'producto_id' => $item->producto_id,
                        'tipo' => 'salida',
                        'cantidad' => $item->cantidad,
                        'motivo' => 'Compra #' . $compra->numero_compra,
                        'observacion' => 'Compra realizada por ' . $usuario->name,
                    ]);
                }
            }

            // Limpiar carrito
            Carrito::where('usuario_id', $usuario->id)->delete();

            \Illuminate\Support\Facades\DB::commit();
            
            return redirect()->route('compra.confirmada', ['compra_id' => $compra->id])->with('compra_id', $compra->id);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('carrito.index')->with('error', 'Error procesando la compra: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar mensaje de compra confirmada.
     */
    public function confirmada(Request $request)
    {
        $compra_id = $request->query('compra_id') ?? session('compra_id');

        if (!$compra_id) {
            return redirect()->route('catalogo')->with('error', 'No hay compra para mostrar.');
        }

        $compra = Compra::findOrFail($compra_id);
        
        // Verificar que la compra pertenece al usuario autenticado
        if ($compra->usuario_id !== $request->user()?->id) {
            return redirect()->route('catalogo')->with('error', 'No tienes permiso para ver esta compra.');
        }

        return view('compra-confirmada', compact('compra'));
    }
}
