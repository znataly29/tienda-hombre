<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Carrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function confirmar(Request $request)
    {
        $usuario = $request->user();
        $items = Carrito::with('producto')->where('usuario_id', $usuario->id)->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'El carrito está vacío');
        }

        $monto = $items->sum(function ($it) { return $it->cantidad * $it->precio_unitario; });

        DB::beginTransaction();
        try {
            $compra = Compra::create([
                'usuario_id' => $usuario->id,
                'monto_total' => $monto,
                'estado' => 'confirmado',
                'detalles' => $items->map(function ($it) {
                    return [
                        'producto_id' => $it->producto_id,
                        'nombre' => $it->producto->nombre,
                        'cantidad' => $it->cantidad,
                        'precio_unitario' => $it->precio_unitario,
                    ];
                })->toArray(),
            ]);

            // Descontar inventario por cada producto comprado y registrar movimiento
            foreach ($items as $item) {
                $inventario = \App\Models\Inventario::where('producto_id', $item->producto_id)->first();
                if ($inventario) {
                    $inventario->decrement('cantidad', $item->cantidad);
                    
                    // Registrar movimiento de salida
                    \App\Models\MovimientoInventario::create([
                        'producto_id' => $item->producto_id,
                        'tipo' => 'salida',
                        'cantidad' => $item->cantidad,
                        'motivo' => 'Compra #' . $compra->id,
                        'observacion' => 'Compra realizada por ' . $usuario->name,
                    ]);
                }
            }

            // Vaciar carrito
            Carrito::where('usuario_id', $usuario->id)->delete();

            DB::commit();
            return redirect()->route('cliente.historial')->with('mensaje', 'Compra confirmada');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error confirmando la compra');
        }
    }

    public function historial(Request $request)
    {
        $usuario = $request->user();
        $compras = Compra::where('usuario_id', $usuario->id)->orderBy('created_at', 'desc')->get();
        return view('cliente.historial', compact('compras'));
    }
}
