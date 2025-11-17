@extends('layouts.app')

@section('titulo','Crear inventario')

@section('contenido')
<h1 class="text-2xl font-bold mb-4">Crear inventario</h1>
<form method="POST" action="{{ route('admin.inventarios.store') }}">@csrf
    <div>
        <label>Producto</label>
        <select name="producto_id">@foreach($productos as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select>
    </div>
    <div><label>Cantidad</label><input name="cantidad" type="number" required></div>
    <div><button class="bg-blue-600 text-white px-3 py-1 mt-2">Crear</button></div>
</form>
@endsection
