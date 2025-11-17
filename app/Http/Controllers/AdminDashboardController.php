<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Métricas del dashboard
        $totalProductos = \App\Models\Producto::count();
        $totalUsuarios = \App\Models\User::count();
        $comprasConfirmadas = \App\Models\Compra::where('estado', 'completada')->count();
        $ventasMes = \App\Models\Compra::where('estado', 'completada')
            ->where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())
            ->sum('monto_total');

        // Productos con stock bajo (< 10 unidades)
        $productosStockBajo = \App\Models\Producto::with('inventario')
            ->get()
            ->filter(function ($producto) {
                return ($producto->inventario?->cantidad ?? 0) < 10;
            })
            ->values();

        return view('admin.dashboard', compact('totalProductos', 'totalUsuarios', 'comprasConfirmadas', 'ventasMes', 'productosStockBajo'));
    }
}
