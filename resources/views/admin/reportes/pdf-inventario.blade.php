<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inventario</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 10px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 22px;
            margin-bottom: 3px;
        }
        
        .header p {
            font-size: 11px;
            color: #666;
        }
        
        .resumen {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }
        
        .resumen-item {
            display: table-cell;
            width: 20%;
            padding: 10px;
            border-right: 1px solid #ddd;
            text-align: center;
        }
        
        .resumen-item:last-child {
            border-right: none;
        }
        
        .resumen-item h3 {
            font-size: 10px;
            color: #666;
            margin-bottom: 3px;
        }
        
        .resumen-item .valor {
            font-size: 16px;
            font-weight: bold;
            color: #3498db;
        }
        
        .seccion-titulo {
            font-size: 13px;
            font-weight: bold;
            margin: 15px 0 8px 0;
            padding-bottom: 3px;
            border-bottom: 2px solid #e74c3c;
            color: #c0392b;
        }
        
        .alertas {
            background: #ffe5e5;
            border: 1px solid #e74c3c;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
        }
        
        .alerta-item {
            padding: 5px 0;
            border-bottom: 1px solid #ffcccc;
            font-size: 10px;
        }
        
        .alerta-item:last-child {
            border-bottom: none;
        }
        
        .estado-agotado {
            color: #c0392b;
            font-weight: bold;
        }
        
        .estado-bajo {
            color: #e67e22;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        
        table thead {
            background: #34495e;
            color: white;
        }
        
        table th {
            padding: 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #bbb;
        }
        
        table td {
            padding: 6px;
            border: 1px solid #ddd;
        }
        
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .stock-bajo {
            background: #ffebee;
        }
        
        .stock-agotado {
            background: #ffcdd2;
        }
        
        .cantidad {
            text-align: center;
        }
        
        .precio {
            text-align: right;
        }
        
        .page-break {
            page-break-after: always;
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
        <h1>Reporte de Inventario</h1>
        <p>Tienda de Ropa para Hombre - Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Resumen --}}
    <div class="resumen">
        <div class="resumen-item">
            <h3>Total Productos</h3>
            <div class="valor">{{ $totalProductos }}</div>
        </div>
        <div class="resumen-item">
            <h3>Stock Total</h3>
            <div class="valor">{{ $inventarioTotal }}</div>
        </div>
        <div class="resumen-item">
            <h3>Valor Inventario</h3>
            <div class="valor">${{ number_format($valorInventario, 0) }}</div>
        </div>
        <div class="resumen-item">
            <h3>Bajo Stock</h3>
            <div class="valor" style="color: #e67e22;">{{ $bajoStock->count() }}</div>
        </div>
        <div class="resumen-item">
            <h3>Agotados</h3>
            <div class="valor" style="color: #c0392b;">{{ $agotados->count() }}</div>
        </div>
    </div>

    {{-- Alertas --}}
    @if($bajoStock->count() > 0 || $agotados->count() > 0)
    <div class="seccion-titulo">Alertas de Stock</div>
    <div class="alertas">
        @foreach($agotados as $item)
        <div class="alerta-item">
            <span class="estado-agotado">❌ AGOTADO:</span> {{ $item->producto->nombre }}
        </div>
        @endforeach
        
        @foreach($bajoStock->where('cantidad', '>', 0) as $item)
        <div class="alerta-item">
            <span class="estado-bajo">⚠️ BAJO STOCK:</span> {{ $item->producto->nombre }} - Stock: {{ $item->cantidad }} unidades
        </div>
        @endforeach
    </div>
    @endif

    {{-- Inventario Completo --}}
    <div class="seccion-titulo">Inventario Completo</div>
    <table>
        <thead>
            <tr>
                <th style="width: 40%">Producto</th>
                <th class="precio" style="width: 15%">Precio Unitario</th>
                <th class="cantidad" style="width: 15%">Stock</th>
                <th class="precio" style="width: 15%">Valor Total</th>
                <th style="width: 15%">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventario as $item)
            <tr class="@if($item->cantidad == 0) stock-agotado @elseif($item->cantidad < 10) stock-bajo @endif">
                <td>{{ $item->producto->nombre }}</td>
                <td class="precio">${{ number_format($item->producto->precio, 0) }}</td>
                <td class="cantidad"><strong>{{ $item->cantidad }}</strong></td>
                <td class="precio">${{ number_format($item->cantidad * $item->producto->precio, 0) }}</td>
                <td>
                    @if($item->cantidad == 0)
                        <span style="background: #c0392b; color: white; padding: 2px 6px; border-radius: 3px; font-size: 8px;">Agotado</span>
                    @elseif($item->cantidad < 10)
                        <span style="background: #e67e22; color: white; padding: 2px 6px; border-radius: 3px; font-size: 8px;">Bajo Stock</span>
                    @else
                        <span style="background: #27ae60; color: white; padding: 2px 6px; border-radius: 3px; font-size: 8px;">Normal</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #999;">No hay inventario registrado</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Movimientos Recientes --}}
    @if($movimientos->count() > 0)
    <div class="page-break"></div>
    
    <div class="header">
        <h2 style="font-size: 16px; border: none; margin: 0; padding: 0;">Movimientos Recientes de Inventario</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30%">Producto</th>
                <th class="cantidad" style="width: 10%">Tipo</th>
                <th class="cantidad" style="width: 10%">Cantidad</th>
                <th style="width: 25%">Motivo</th>
                <th style="width: 25%">Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movimientos as $movimiento)
            <tr>
                <td>{{ $movimiento->producto->nombre ?? 'Producto eliminado' }}</td>
                <td class="cantidad">
                    <span style="background: @if($movimiento->tipo == 'entrada') #27ae60 @else #e74c3c @endif; color: white; padding: 2px 5px; border-radius: 2px; font-size: 8px;">
                        {{ ucfirst($movimiento->tipo) }}
                    </span>
                </td>
                <td class="cantidad">{{ $movimiento->cantidad }}</td>
                <td>{{ $movimiento->motivo }}</td>
                <td>{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #999;">No hay movimientos registrados</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Reporte generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>© 2025 Tienda de Ropa para Hombre - Todos los derechos reservados</p>
    </div>
</body>
</html>
