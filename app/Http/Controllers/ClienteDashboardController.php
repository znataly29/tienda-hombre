<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\DireccionEnvio;

class ClienteDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Estadísticas de compras
        $totalCompras = Compra::where('usuario_id', $user->id)->count();
        $totalGastado = Compra::where('usuario_id', $user->id)->sum('monto_total');
        $ultimaCompra = Compra::where('usuario_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Últimas 5 compras
        $comprasRecientes = Compra::where('usuario_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Direcciones de envío
        $direcciones = DireccionEnvio::where('usuario_id', $user->id)
            ->orderBy('es_principal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.dashboard', compact(
            'user',
            'totalCompras',
            'totalGastado',
            'ultimaCompra',
            'comprasRecientes',
            'direcciones'
        ));
    }
}

