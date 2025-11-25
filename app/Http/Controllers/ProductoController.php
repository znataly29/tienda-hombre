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
            'categoria' => 'required|string|in:Camisetas,Camisas,Sudaderas,Chaquetas,Shorts,Pantalones',
            'talla' => 'required|string|in:M,L,XL,30,32,34,36',
            'cantidad' => 'required|integer|min:1',
        ], [
            'categoria.in' => 'La categoría seleccionada no es válida.',
            'talla.in' => 'La talla seleccionada no es válida.',
        ]);

        // Validar combinación de categoría y talla
        $this->validarCategoriaTalla($datos['categoria'], $datos['talla']);

        // Extraer cantidad antes de crear el producto
        $cantidad = $datos['cantidad'];
        unset($datos['cantidad']);

        // Normalizar los datos (trim)
        $datos['nombre'] = trim($datos['nombre']);
        $datos['descripcion'] = trim($datos['descripcion'] ?? '');
        $datos['categoria'] = trim($datos['categoria']);
        $datos['talla'] = trim($datos['talla']);

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
            'categoria' => 'required|string|in:Camisetas,Camisas,Sudaderas,Chaquetas,Shorts,Pantalones',
            'talla' => 'required|string|in:M,L,XL,30,32,34,36',
            'cantidad' => 'required|integer|min:1',
        ], [
            'categoria.in' => 'La categoría seleccionada no es válida.',
            'talla.in' => 'La talla seleccionada no es válida.',
        ]);

        // Validar combinación de categoría y talla
        $this->validarCategoriaTalla($datos['categoria'], $datos['talla']);

        // Extraer cantidad antes de actualizar el producto
        $cantidad = $datos['cantidad'];
        unset($datos['cantidad']);

        // Normalizar los datos (trim)
        $datos['nombre'] = trim($datos['nombre']);
        $datos['descripcion'] = trim($datos['descripcion'] ?? '');
        $datos['categoria'] = trim($datos['categoria']);
        $datos['talla'] = trim($datos['talla']);

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

    /**
     * Valida que la combinación de categoría y talla sea válida
     */
    private function validarCategoriaTalla(string $categoria, string $talla)
    {
        // Reglas: categoría -> tallas permitidas
        $validaciones = [
            'Camisetas' => ['M', 'L', 'XL'],
            'Camisas' => ['M', 'L', 'XL'],
            'Sudaderas' => ['M', 'L', 'XL'],
            'Chaquetas' => ['M', 'L', 'XL'],
            'Shorts' => ['M', 'L', 'XL'],
            'Pantalones' => ['30', '32', '34', '36']
        ];

        // Verificar si la categoría existe en nuestras reglas
        if (!isset($validaciones[$categoria])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'categoria' => 'Categoría no reconocida.'
            ]);
        }

        // Verificar si la talla es válida para esta categoría
        if (!in_array($talla, $validaciones[$categoria])) {
            $tallasPermitidas = implode(', ', $validaciones[$categoria]);
            
            throw \Illuminate\Validation\ValidationException::withMessages([
                'talla' => "Para {$categoria} solo están disponibles las tallas: {$tallasPermitidas}"
            ]);
        }
    }
}
