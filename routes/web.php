<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\DireccionEnvioController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('catalogo');
});

// Catálogo público
Route::get('/catalogo', [ProductoController::class, 'index'])->name('catalogo');

// Contador del carrito (funciona para invitados y autenticados)
Route::get('/carrito/count', [\App\Http\Controllers\CarritoController::class, 'count'])->name('carrito.count');

// Agregar al carrito: soporta invitados y usuarios autenticados
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');

// Carrito (requiere autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::patch('/carrito/{carrito}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/{carrito}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    
    // Checkout y confirmación
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/finalizar', [\App\Http\Controllers\CheckoutController::class, 'finalizar'])->name('checkout.finalizar');
    Route::get('/compra-confirmada', [\App\Http\Controllers\CheckoutController::class, 'confirmada'])->name('compra.confirmada');
    
    // Compras
    Route::post('/compras/confirmar', [CompraController::class, 'confirmar'])->name('compras.confirmar');
    Route::get('/cliente/historial', [CompraController::class, 'historial'])->name('cliente.historial');
    
    // Rutas específicas de cliente
    Route::get('/cliente/dashboard', [\App\Http\Controllers\ClienteDashboardController::class, 'index'])->name('cliente.dashboard');
    
    // Direcciones de envío
    Route::post('/cliente/direcciones', [DireccionEnvioController::class, 'store'])->name('cliente.direcciones.store');
    Route::put('/cliente/direcciones/{direccion}', [DireccionEnvioController::class, 'update'])->name('cliente.direcciones.update');
    Route::post('/cliente/direcciones/{direccion}/principal', [DireccionEnvioController::class, 'marcarPrincipal'])->name('cliente.direcciones.principal');
    Route::delete('/cliente/direcciones/{direccion}', [DireccionEnvioController::class, 'destroy'])->name('cliente.direcciones.destroy');
    
    // Teléfono del cliente
    Route::put('/cliente/telefono', [ClienteController::class, 'actualizarTelefono'])->name('cliente.telefono.update');
});

// Dashboard para admin (protegido por rol)
Route::middleware(['auth', \App\Http\Middleware\VerificarRol::class . ':admin'])->group(function () {
    Route::get('/admin/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Gestión de productos
    Route::get('/admin/productos', [ProductoController::class, 'listar'])->name('admin.productos.index');
    Route::get('/admin/productos/crear', [ProductoController::class, 'crear'])->name('admin.productos.crear');
    Route::post('/admin/productos', [ProductoController::class, 'store'])->name('admin.productos.store');
    Route::get('/admin/productos/{producto}/editar', [ProductoController::class, 'editar'])->name('admin.productos.editar');
    Route::put('/admin/productos/{producto}', [ProductoController::class, 'update'])->name('admin.productos.update');
    Route::delete('/admin/productos/{producto}', [ProductoController::class, 'destroy'])->name('admin.productos.destroy');
    
    // Reportes
    Route::get('/admin/reportes', [\App\Http\Controllers\ReporteController::class, 'dashboard'])->name('admin.reportes.dashboard');
    Route::get('/admin/reportes/ventas', [\App\Http\Controllers\ReporteController::class, 'reporteVentas'])->name('admin.reportes.ventas');
    Route::get('/admin/reportes/inventario', [\App\Http\Controllers\ReporteController::class, 'reporteInventario'])->name('admin.reportes.inventario');
    
    // Ajustes de inventario
    Route::get('/admin/ajustes', [\App\Http\Controllers\AjusteInventarioController::class, 'index'])->name('admin.ajustes.index');
    Route::get('/admin/ajustes/crear', [\App\Http\Controllers\AjusteInventarioController::class, 'crear'])->name('admin.ajustes.crear');
    Route::post('/admin/ajustes', [\App\Http\Controllers\AjusteInventarioController::class, 'store'])->name('admin.ajustes.store');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
