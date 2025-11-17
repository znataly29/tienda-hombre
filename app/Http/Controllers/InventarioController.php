<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $inventarios = Inventario::with('producto')->paginate(15);
        return view('admin.inventarios.index', compact('inventarios'));
    }

    public function crear()
    {
        $productos = Producto::all();
        return view('admin.inventarios.crear', compact('productos'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        Inventario::create($datos);

        return redirect()->route('admin.inventarios.index')->with('mensaje', 'Inventario creado');
    }

    public function editar(Inventario $inventario)
    {
        $productos = Producto::all();
        return view('admin.inventarios.editar', compact('inventario', 'productos'));
    }

    public function update(Request $request, Inventario $inventario)
    {
        $datos = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        $inventario->update($datos);

        return redirect()->route('admin.inventarios.index')->with('mensaje', 'Inventario actualizado');
    }

    public function destroy(Inventario $inventario)
    {
        $inventario->delete();
        return back()->with('mensaje', 'Inventario eliminado');
    }
}
