<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Productos</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold">📦 Productos</h1>
                        <a href="{{ route('admin.productos.crear') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            ➕ Nuevo Producto
                        </a>
                    </div>

                    {{-- Buscador --}}
                    <form method="GET" action="{{ route('admin.productos.index') }}" class="mb-6">
                        <div class="flex gap-2">
                            <input type="text" name="buscar" placeholder="Buscar producto..." value="{{ request('buscar') }}" 
                                   class="flex-1 border rounded px-4 py-2">
                            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                                Buscar
                            </button>
                        </div>
                    </form>

                    {{-- Tabla de productos --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left">ID</th>
                                    <th class="px-4 py-3 text-left">Nombre</th>
                                    <th class="px-4 py-3 text-left">Categoría</th>
                                    <th class="px-4 py-3 text-left">Talla</th>
                                    <th class="px-4 py-3 text-right">Precio</th>
                                    <th class="px-4 py-3 text-center">Cantidad</th>
                                    <th class="px-4 py-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productos as $producto)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">#{{ $producto->id }}</td>
                                    <td class="px-4 py-3 font-semibold">{{ $producto->nombre }}</td>
                                    <td class="px-4 py-3">{{ $producto->categoria ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">{{ $producto->talla ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-right font-semibold">${{ number_format($producto->precio, 0) }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                            @if($producto->inventario?->cantidad > 10)
                                                bg-green-100 text-green-800
                                            @elseif($producto->inventario?->cantidad > 0)
                                                bg-yellow-100 text-yellow-800
                                            @else
                                                bg-red-100 text-red-800
                                            @endif">
                                            {{ $producto->inventario?->cantidad ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.productos.editar', $producto) }}" 
                                               class="text-blue-600 hover:text-blue-800 font-semibold">✏️ Editar</a>
                                            <form method="POST" action="{{ route('admin.productos.destroy', $producto) }}" 
                                                  style="display:inline;" onsubmit="return confirm('¿Eliminar este producto?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">🗑️ Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-3 text-center text-gray-500">No hay productos</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-6">
                        {{ $productos->links() }}
                    </div>

                    {{-- Mensaje de éxito --}}
                    @if(session('mensaje'))
                    <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('mensaje') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
