<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 11px;
            line-height: 1.5;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 22px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 11px;
            color: #666;
        }
        
        .metricas {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        
        .metrica {
            display: table-cell;
            width: 25%;
            padding: 12px;
            border-right: 1px solid #ddd;
            text-align: center;
        }
        
        .metrica:last-child {
            border-right: none;
        }
        
        .metrica h3 {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .metrica .valor {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .seccion {
            margin-bottom: 20px;
        }
        
        .seccion-titulo {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            padding-bottom: 3px;
            border-bottom: 2px solid #3498db;
            color: #2c3e50;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10px;
        }
        
        table thead {
            background: #34495e;
            color: white;
        }
        
        table th {
            padding: 6px;
            text-align: left;
            border: 1px solid #bbb;
        }
        
        table td {
            padding: 6px;
            border: 1px solid #ddd;
        }
        
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .alerta-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
        }
        
        .alerta-item {
            padding: 5px 0;
            border-bottom: 1px solid #ffe69c;
            font-size: 9px;
        }
        
        .alerta-item:last-child {
            border-bottom: none;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dashboard de Reportes</h1>
        <p>Tienda de Ropa para Hombre - {{ now()->format('d/m/Y') }}</p>
    </div>

    {{-- Métricas principales --}}
    <div class="metricas">
        <div class="metrica">
            <h3>Ventas Hoy</h3>
            <div class="valor">${{ number_format($ventasHoy, 0) }}</div>
        </div>
        <div class="metrica">
            <h3>Ventas Semana</h3>
            <div class="valor">${{ number_format($ventasSemana, 0) }}</div>
        </div>
        <div class="metrica">
            <h3>Ventas Mes</h3>
            <div class="valor">${{ number_format($ventasMes, 0) }}</div>
        </div>
        <div class="metrica">
            <h3>Compras Confirmadas</h3>
            <div class="valor">{{ $comprasConfirmadas }}</div>
        </div>
    </div>

    {{-- Inventario --}}
    <div class="metricas">
        <div class="metrica">
            <h3>Stock Total</h3>
            <div class="valor">{{ $inventarioTotal }}</div>
        </div>
        <div class="metrica">
            <h3>Valor Inventario</h3>
            <div class="valor">${{ number_format($valorInventario, 0) }}</div>
        </div>
        <div class="metrica">
            <h3>Bajo Stock</h3>
            <div class="valor" style="color: #e67e22;">{{ $productosBajoStock }}</div>
        </div>
        <div class="metrica">
            <h3>Agotados</h3>
            <div class="valor" style="color: #c0392b;">{{ $productosAgotados }}</div>
        </div>
    </div>

    {{-- Ventas últimos 7 días --}}
    <div class="seccion">
        <div class="seccion-titulo">Ventas Últimos 7 Días</div>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventasUltimaSemana as $venta)
                <tr>
                    <td>{{ $venta['fecha'] }}</td>
                    <td>${{ number_format($venta['monto'], 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Producto más vendido --}}
    @if($productoMasVendido)
    <div class="seccion">
        <div class="seccion-titulo">🏆 Producto Más Vendido</div>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad Vendida</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $productoMasVendido['nombre'] }}</td>
                    <td>{{ $productoMasVendido['cantidad'] }} unidades</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    {{-- Alertas de bajo stock --}}
    @if($bajoStock->count() > 0)
    <div class="seccion">
        <div class="seccion-titulo">Productos con Bajo Stock</div>
        <div class="alerta-box">
            @foreach($bajoStock as $item)
            <div class="alerta-item">
                <strong>{{ $item->producto->nombre }}</strong> (Talla: {{ $item->talla }}) - Stock: <strong>{{ $item->cantidad }}</strong> unidades
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Reporte generado el {{ now()->format('d/m/Y') }}</p>
        <p>© 2025 Tienda de Ropa para Hombre - Todos los derechos reservados</p>
    </div>
</body>
</html>
