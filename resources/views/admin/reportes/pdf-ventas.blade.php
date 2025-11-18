<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas</title>
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
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 12px;
            color: #666;
        }
        
        .periodo {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .resumen {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        
        .resumen-item {
            display: table-cell;
            width: 25%;
            padding: 15px;
            border-right: 1px solid #ddd;
            text-align: center;
        }
        
        .resumen-item:last-child {
            border-right: none;
        }
        
        .resumen-item h3 {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .resumen-item .valor {
            font-size: 18px;
            font-weight: bold;
            color: #2ecc71;
        }
        
        .seccion-titulo {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #2ecc71;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead {
            background: #34495e;
            color: white;
        }
        
        table th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #bbb;
        }
        
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .precio {
            text-align: right;
        }
        
        .cantidad {
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Ventas</h1>
        <p>Tienda de Ropa para Hombre</p>
    </div>

    <div class="periodo">
        <strong>Período:</strong> {{ $desde->format('d/m/Y') }} - {{ $hasta->format('d/m/Y') }}
    </div>

    {{-- Resumen --}}
    <div class="resumen">
        <div class="resumen-item">
            <h3>Total de Ventas</h3>
            <div class="valor">${{ number_format($totalVentas, 0) }}</div>
        </div>
        <div class="resumen-item">
            <h3>Cantidad de Pedidos</h3>
            <div class="valor">{{ $cantidadPedidos }}</div>
        </div>
        <div class="resumen-item">
            <h3>Ticket Promedio</h3>
            <div class="valor">${{ number_format($ticketPromedio, 0) }}</div>
        </div>
        <div class="resumen-item">
            <h3>Venta Promedio Diaria</h3>
            <div class="valor">${{ number_format($totalVentas / max(1, $hasta->diffInDays($desde) + 1), 0) }}</div>
        </div>
    </div>

    {{-- Top Productos --}}
    <div class="seccion-titulo">Top 5 Productos Más Vendidos</div>
    <table>
        <thead>
            <tr>
                <th style="width: 40%">Producto</th>
                <th class="cantidad" style="width: 15%">Cantidad</th>
                <th class="precio" style="width: 20%">Monto</th>
                <th class="precio" style="width: 25%">% del Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productosMasVendidos as $producto)
            <tr>
                <td>{{ $producto['nombre'] }}</td>
                <td class="cantidad">{{ $producto['cantidad'] }}</td>
                <td class="precio">${{ number_format($producto['monto'], 0) }}</td>
                <td class="precio">{{ number_format(($producto['monto'] / max(1, $totalVentas)) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Detalle de Compras --}}
    <div class="seccion-titulo">Detalle de Compras</div>
    <table>
        <thead>
            <tr>
                <th style="width: 10%">ID Compra</th>
                <th style="width: 25%">Cliente</th>
                <th style="width: 20%">Fecha</th>
                <th class="precio" style="width: 20%">Monto</th>
                <th style="width: 25%">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($compras as $compra)
            <tr>
                <td>#{{ $compra->id }}</td>
                <td>{{ $compra->usuario->name ?? 'Desconocido' }}</td>
                <td>{{ $compra->created_at->format('d/m/Y H:i') }}</td>
                <td class="precio">${{ number_format($compra->monto_total, 0) }}</td>
                <td>
                    <span style="background: #2ecc71; color: white; padding: 3px 8px; border-radius: 3px; font-size: 10px;">
                        {{ ucfirst($compra->estado) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #999;">No hay compras en este período</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>© 2025 Tienda de Ropa para Hombre - Todos los derechos reservados</p>
    </div>
</body>
</html>
