<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::with('inventario');

        if ($request->filled('categoria')) {
            $productos->whereRaw('LOWER(categoria) = LOWER(?)', [$request->categoria]);
        }
        if ($request->filled('talla')) {
            $productos->whereRaw('LOWER(talla) = LOWER(?)', [$request->talla]);
        }

        // Categorías predefinidas (solo las originales del seeder)
        $categorias = collect([
            'Camisetas',
            'Camisas',
            'Sudaderas',
            'Chaquetas',
            'Shorts',
            'Pantalones'
        ]);
        
        // Tallas predefinidas (de los productos del seeder)
        $tallas = collect([
            'M',
            'L',
            'XL',
            '30',
            '32',
            '34',
            '36'
        ]);

        return view('catalogo', [
            'productos' => $productos->paginate(12),
            'categorias' => $categorias,
            'tallas' => $tallas,
        ]);
    }

    public function listar(Request $request)
    {
        $productos = Producto::with('inventario');

        if ($request->filled('buscar')) {
            $productos->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        $productos = $productos->paginate(15);
        return view('admin.productos.index', compact('productos'));
    }

    public function crear()
    {
        return view('admin.productos.crear');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'nullable|string|max:100',
            'talla' => 'nullable|string|max:20',
            'cantidad' => 'required|integer|min:1',
        ]);

        // Extraer cantidad antes de crear el producto
        $cantidad = $datos['cantidad'];
        unset($datos['cantidad']);

        // Normalizar los datos (trim)
        $datos['nombre'] = trim($datos['nombre']);
        $datos['descripcion'] = trim($datos['descripcion'] ?? '');
        $datos['categoria'] = trim($datos['categoria'] ?? '');
        $datos['talla'] = trim($datos['talla'] ?? '');

        // Crear producto
        $producto = Producto::create($datos);

        // Crear inventario asociado
        \App\Models\Inventario::create([
            'producto_id' => $producto->id,
            'cantidad' => $cantidad,
        ]);

        return redirect()->route('admin.productos.index')->with('mensaje', 'Producto creado correctamente con ' . $cantidad . ' unidades');
    }

    public function editar(Producto $producto)
    {
        $producto->load('inventario');
        return view('admin.productos.editar', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'nullable|string|max:100',
            'talla' => 'nullable|string|max:20',
            'cantidad' => 'required|integer|min:1',
        ]);

        // Extraer cantidad antes de actualizar el producto
        $cantidad = $datos['cantidad'];
        unset($datos['cantidad']);

        // Normalizar los datos (trim)
        $datos['nombre'] = trim($datos['nombre']);
        $datos['descripcion'] = trim($datos['descripcion'] ?? '');
        $datos['categoria'] = trim($datos['categoria'] ?? '');
        $datos['talla'] = trim($datos['talla'] ?? '');

        $producto->update($datos);

        // Actualizar o crear inventario
        $inventario = \App\Models\Inventario::firstOrCreate(
            ['producto_id' => $producto->id],
            ['cantidad' => $cantidad]
        );
        
        if ($inventario->wasRecentlyCreated === false) {
            $inventario->update(['cantidad' => $cantidad]);
        }

        return redirect()->route('admin.productos.index')->with('mensaje', 'Producto actualizado correctamente');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return back()->with('mensaje', 'Producto eliminado');
    }
}
