<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Producto;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    /**
     * Dashboard de reportes con gráficas y datos resumidos.
     */
    public function dashboard(Request $request)
    {
        // Datos de ventas
        $hoy = Carbon::now()->startOfDay();
        $semana = Carbon::now()->startOfWeek();
        $mes = Carbon::now()->startOfMonth();

        $ventasHoy = Compra::where('estado', 'completada')
            ->where('created_at', '>=', $hoy)
            ->sum('monto_total');

        $ventasSemana = Compra::where('estado', 'completada')
            ->where('created_at', '>=', $semana)
            ->sum('monto_total');

        $ventasMes = Compra::where('estado', 'completada')
            ->where('created_at', '>=', $mes)
            ->sum('monto_total');

        $comprasConfirmadas = Compra::where('estado', 'completada')->count();
        $ticketPromedio = $comprasConfirmadas > 0 ? $ventasMes / $comprasConfirmadas : 0;

        // Datos de inventario
        $productosBajoStock = Inventario::where('cantidad', '<', 5)->count();
        $productosAgotados = Inventario::where('cantidad', 0)->count();
        $inventarioTotal = Inventario::sum('cantidad');
        $valorInventario = Inventario::with('producto')
            ->get()
            ->sum(fn($inv) => $inv->cantidad * $inv->producto->precio);

        // Gráfica de ventas últimos 7 días
        $ventasUltimaSemana = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i)->startOfDay();
            $ventasUltimaSemana[] = [
                'fecha' => $fecha->format('d/m'),
                'monto' => Compra::where('estado', 'completada')
                    ->whereDate('created_at', $fecha)
                    ->sum('monto_total'),
            ];
        }

        // Producto más vendido (por cantidad)
        $productoMasVendido = Compra::where('estado', 'completada')
            ->get()
            ->flatMap(fn($c) => $c->detalles ?? [])
            ->groupBy('nombre')
            ->map(fn($items) => ['nombre' => $items[0]['nombre'], 'cantidad' => collect($items)->sum('cantidad')])
            ->sortByDesc('cantidad')
            ->first();

        // Bajo stock alertas
        $bajoStock = Inventario::where('cantidad', '<', 10)
            ->with('producto')
            ->get();

        $data = compact(
            'ventasHoy',
            'ventasSemana',
            'ventasMes',
            'comprasConfirmadas',
            'ticketPromedio',
            'productosBajoStock',
            'productosAgotados',
            'inventarioTotal',
            'valorInventario',
            'ventasUltimaSemana',
            'productoMasVendido',
            'bajoStock'
        );

        // Si solicita PDF
        if ($request->input('formato') === 'pdf') {
            return $this->generarPdfDashboard($data);
        }

        return view('admin.reportes.dashboard', $data);
    }

    /**
     * Reporte detallado de ventas.
     */
    public function reporteVentas(Request $request)
    {
        $desde = $request->input('desde') ? Carbon::parse($request->input('desde'))->startOfDay() : Carbon::now()->startOfMonth();
        $hasta = $request->input('hasta') ? Carbon::parse($request->input('hasta'))->endOfDay() : Carbon::now()->endOfDay();
        $categoria = $request->input('categoria');

        $compras = Compra::where('estado', 'completada')
            ->whereBetween('created_at', [$desde, $hasta])
            ->with('usuario')
            ->orderBy('created_at', 'asc')
            ->get();

        // Filtrar por categoría si se proporciona
        if ($categoria) {
            $compras = $compras->filter(function ($compra) use ($categoria) {
                return collect($compra->detalles ?? [])->some(function ($detalle) use ($categoria) {
                    $producto = Producto::find($detalle['producto_id']);
                    return $producto && $producto->categoria === $categoria;
                });
            })->values();
        }

        $totalVentas = $compras->sum('monto_total');
        $cantidadPedidos = $compras->count();
        $ticketPromedio = $cantidadPedidos > 0 ? $totalVentas / $cantidadPedidos : 0;

        // Producto más vendido
        $productosMasVendidos = $compras
            ->flatMap(fn($c) => $c->detalles ?? [])
            ->groupBy('nombre')
            ->map(fn($items) => ['nombre' => $items[0]['nombre'], 'cantidad' => collect($items)->sum('cantidad'), 'monto' => collect($items)->sum(fn($i) => $i['cantidad'] * $i['precio_unitario'])])
            ->sortByDesc('cantidad')
            ->take(5);

        // Gráfica de ventas por día
        $ventasPorDia = [];
        $periodo = $desde->copy();
        while ($periodo <= $hasta) {
            $ventasPorDia[] = [
                'fecha' => $periodo->format('d/m/Y'),
                'monto' => Compra::where('estado', 'completada')
                    ->whereDate('created_at', $periodo)
                    ->sum('monto_total'),
            ];
            $periodo->addDay();
        }

        // Categorías disponibles
        $categorias = Producto::distinct()->pluck('categoria')->filter()->values();

        $formato = $request->input('formato', 'view');

        if ($formato === 'pdf') {
            return $this->generarPdfVentas(compact('compras', 'desde', 'hasta', 'totalVentas', 'cantidadPedidos', 'ticketPromedio', 'productosMasVendidos', 'categoria'));
        }

        return view('admin.reportes.ventas', compact('compras', 'desde', 'hasta', 'totalVentas', 'cantidadPedidos', 'ticketPromedio', 'productosMasVendidos', 'ventasPorDia', 'categorias', 'categoria'));
    }

    /**
     * Reporte detallado de inventario.
     */
    public function reporteInventario(Request $request)
    {
        $inventario = Inventario::with('producto')
            ->orderBy('cantidad', 'asc')
            ->get();

        $bajoStock = $inventario->filter(fn($inv) => $inv->cantidad < 10);
        $agotados = $inventario->filter(fn($inv) => $inv->cantidad == 0);
        $totalProductos = $inventario->count();
        $inventarioTotal = $inventario->sum('cantidad');
        $valorInventario = $inventario->sum(fn($inv) => $inv->cantidad * $inv->producto->precio);

        // Movimientos recientes
        $movimientos = MovimientoInventario::with('producto')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        $formato = $request->input('formato', 'view');

        if ($formato === 'pdf') {
            return $this->generarPdfInventario(compact('inventario', 'bajoStock', 'agotados', 'totalProductos', 'inventarioTotal', 'valorInventario', 'movimientos'));
        }

        return view('admin.reportes.inventario', compact('inventario', 'bajoStock', 'agotados', 'totalProductos', 'inventarioTotal', 'valorInventario', 'movimientos'));
    }

    /**
     * Generar PDF de ventas.
     */
    private function generarPdfVentas($data)
    {
        $html = view('admin.reportes.pdf-ventas', $data)->render();
        
        $pdf = Pdf::loadHtml($html)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isFontSubsettingEnabled', true);

        return $pdf->download('reporte-ventas-' . now()->format('Y-m-d-His') . '.pdf');
    }

    /**
     * Generar PDF de inventario.
     */
    private function generarPdfInventario($data)
    {
        $html = view('admin.reportes.pdf-inventario', $data)->render();
        
        $pdf = Pdf::loadHtml($html)
            ->setPaper('a4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isFontSubsettingEnabled', true);

        return $pdf->download('reporte-inventario-' . now()->format('Y-m-d-His') . '.pdf');
    }

    /**
     * Generar PDF del dashboard.
     */
    private function generarPdfDashboard($data)
    {
        $html = view('admin.reportes.pdf-dashboard', $data)->render();
        
        $pdf = Pdf::loadHtml($html)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isFontSubsettingEnabled', true);

        return $pdf->download('reporte-dashboard-' . now()->format('Y-m-d-His') . '.pdf');
    }

    // Métodos antiguos (compatibilidad)
    public function ventas(Request $request)
    {
        return $this->reporteVentas($request);
    }

    public function inventario(Request $request)
    {
        return $this->reporteInventario($request);
    }
}

