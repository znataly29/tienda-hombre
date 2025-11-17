<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use Illuminate\Http\Request;

class AjusteInventarioController extends Controller
{
    public function index()
    {
        $ajustes = MovimientoInventario::with('producto')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.ajustes.index', compact('ajustes'));
    }

    public function crear()
    {
        $productos = Producto::with('inventario')->get();
        return view('admin.ajustes.crear', compact('productos'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'required|string|max:100',
            'observacion' => 'nullable|string|max:500',
        ]);

        $inventario = Inventario::where('producto_id', $datos['producto_id'])->first();
        
        if (!$inventario) {
            return back()->with('error', 'El producto no tiene inventario registrado');
        }

        // Validar que no se descuente más de lo que hay
        if ($datos['tipo'] === 'salida' && $datos['cantidad'] > $inventario->cantidad) {
            return back()->with('error', 'No hay suficiente stock para descontar');
        }

        // Actualizar cantidad
        if ($datos['tipo'] === 'entrada') {
            $inventario->increment('cantidad', $datos['cantidad']);
        } else {
            $inventario->decrement('cantidad', $datos['cantidad']);
        }

        // Registrar movimiento
        MovimientoInventario::create($datos);

        return redirect()->route('admin.ajustes.index')
            ->with('mensaje', 'Ajuste de inventario registrado correctamente');
    }
}
