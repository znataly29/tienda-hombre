@extends('layouts.app')

@section('titulo','Inventarios')

@section('contenido')
<h1 class="text-2xl font-bold mb-4">Inventarios</h1>
<a href="{{ route('admin.inventarios.crear') }}" class="bg-green-600 text-white px-3 py-1">Crear inventario</a>
@foreach($inventarios as $inv)
    <div class="bg-white p-3 mb-2 shadow">
        <div class="font-bold">{{ $inv->producto->nombre }}</div>
        <div>Cantidad: {{ $inv->cantidad }}</div>
    </div>
@endforeach
{{ $inventarios->links() }}
@endsection
