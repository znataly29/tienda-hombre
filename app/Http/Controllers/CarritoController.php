<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        // Usuario autenticado: obtener carrito desde la BD
        if ($usuario) {
            $carrito = Carrito::with('producto')->where('usuario_id', $usuario->id)->get();
            return view('carrito', compact('carrito'));
        }

        // Invitado: obtener carrito desde la sesión y mapear a una colección
        $sessionCart = session()->get('cart', []);
        $collection = collect($sessionCart)->map(function ($it, $idx) {
            return (object) [
                'id' => $idx,
                'producto' => (object) [
                    'nombre' => $it['nombre'] ?? 'Producto',
                    'talla' => $it['talla'] ?? '',
                ],
                'precio_unitario' => $it['precio_unitario'] ?? 0,
                'cantidad' => $it['cantidad'] ?? 1,
            ];
        });

        return view('carrito', ['carrito' => $collection]);
    }

    public function agregar(Request $request)
    {
        $datos = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        // Verificar que el usuario no sea admin
        if ($request->user() && $request->user()->rol && $request->user()->rol->nombre === 'admin') {
            return response()->json([
                'ok' => false,
                'message' => 'Los administradores no pueden hacer compras',
            ], 403);
        }

        $producto = Producto::findOrFail($datos['producto_id']);
        // Si el usuario está autenticado, guardar en BD
        if ($request->user()) {
            $userId = $request->user()->id;

            $item = Carrito::where('usuario_id', $userId)->where('producto_id', $producto->id)->first();
            if ($item) {
                $item->increment('cantidad', $datos['cantidad']);
            } else {
                $item = Carrito::create([
                    'usuario_id' => $userId,
                    'producto_id' => $producto->id,
                    'cantidad' => $datos['cantidad'],
                    'precio_unitario' => $producto->precio,
                ]);
            }

            $totalItems = Carrito::where('usuario_id', $userId)->sum('cantidad');

            return response()->json([
                'ok' => true,
                'message' => 'Producto agregado al carrito',
                'item' => $item,
                'total_items' => $totalItems,
            ]);
        }

        // Invitado: guardar en session
        $sessionCart = session()->get('cart', []);
        $found = false;
        foreach ($sessionCart as &$s) {
            if ($s['producto_id'] == $producto->id) {
                $s['cantidad'] += $datos['cantidad'];
                $found = true;
                break;
            }
        }
        unset($s);

        if (! $found) {
            $sessionCart[] = [
                'producto_id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio_unitario' => $producto->precio,
                'talla' => $producto->talla,
                'cantidad' => $datos['cantidad'],
            ];
        }

        session()->put('cart', $sessionCart);
        $totalItems = array_reduce($sessionCart, fn($carry, $it) => $carry + ($it['cantidad'] ?? 0), 0);

        return response()->json([
            'ok' => true,
            'message' => 'Producto agregado al carrito (invitado)',
            'total_items' => $totalItems,
        ]);
    }

    /**
     * Retorna la cantidad total de items en el carrito del usuario autenticado.
     */
    public function count(Request $request)
    {
        if ($request->user()) {
            $count = Carrito::where('usuario_id', $request->user()->id)->sum('cantidad');
            return response()->json(['count' => $count]);
        }

        $sessionCart = session()->get('cart', []);
        $count = array_reduce($sessionCart, function ($carry, $it) {
            return $carry + ($it['cantidad'] ?? 0);
        }, 0);
        return response()->json(['count' => $count]);
    }

    public function actualizar(Request $request, Carrito $carrito)
    {
        // Verificar que el carrito pertenece al usuario autenticado
        if ($carrito->usuario_id !== $request->user()->id) {
            return response()->json(['ok' => false, 'message' => 'No autorizado'], 403);
        }

        $datos = $request->validate(['cantidad' => 'required|integer|min:1']);
        $carrito->update($datos);

        return response()->json(['ok' => true, 'carrito' => $carrito]);
    }

    public function eliminar(Request $request, Carrito $carrito)
    {
        // Verificar que el carrito pertenece al usuario autenticado
        if ($carrito->usuario_id !== $request->user()->id) {
            return response()->json(['ok' => false, 'message' => 'No autorizado'], 403);
        }
        
        $carrito->delete();
        return response()->json(['ok' => true]);
    }
}
