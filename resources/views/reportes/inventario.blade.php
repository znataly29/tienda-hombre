<h1>Reporte de Inventario</h1>
<table border="1" width="100%" cellpadding="4">
    <thead>
        <tr><th>Producto</th><th>Cantidad</th><th>Ubicación</th></tr>
    </thead>
    <tbody>
    @foreach($inventarios as $i)
        <tr>
            <td>{{ $i->producto->nombre ?? $i->producto_id }}</td>
            <td>{{ $i->cantidad }}</td>
            <td>{{ $i->ubicacion }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
