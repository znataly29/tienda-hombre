<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Producto;
use App\Models\Carrito;
use App\Http\Controllers\CarritoController;

Route::get('/filtros', function (Request $request) {
    $query = Producto::query();
    if ($request->filled('categoria')) $query->where('categoria', $request->categoria);
    if ($request->filled('talla')) $query->where('talla', $request->talla);
    if ($request->filled('color')) $query->where('color', $request->color);
    return response()->json($query->get());
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar']);
    Route::patch('/carrito/{carrito}', [CarritoController::class, 'actualizar']);
    Route::delete('/carrito/{carrito}', [CarritoController::class, 'eliminar']);
    
    // Obtener contador del carrito
    Route::get('/carrito/count', function (Request $request) {
        $count = Carrito::where('usuario_id', $request->user()->id)->count();
        return response()->json(['count' => $count]);
    });
});
