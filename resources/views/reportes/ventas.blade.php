<h1>Reporte de Ventas</h1>
<p>Desde: {{ $desde ?? '---' }} Hasta: {{ $hasta ?? '---' }}</p>
<table border="1" width="100%" cellpadding="4">
    <thead>
        <tr><th>ID</th><th>Usuario</th><th>Monto</th><th>Fecha</th></tr>
    </thead>
    <tbody>
    @foreach($compras as $c)
        <tr>
            <td>{{ $c->id }}</td>
            <td>{{ $c->usuario->name ?? $c->usuario_id }}</td>
            <td>{{ $c->monto_total }}</td>
            <td>{{ $c->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
