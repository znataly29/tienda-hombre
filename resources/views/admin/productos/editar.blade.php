<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Producto</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h1 class="text-3xl font-bold mb-6">✏️ Editar Producto</h1>

                    <form method="POST" action="{{ route('admin.productos.update', $producto) }}" class="space-y-6">
                        @csrf @method('PUT')

                        {{-- Nombre --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre *</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required
                                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('nombre')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                            <textarea name="descripcion" rows="3"
                                      class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $producto->descripcion) }}</textarea>
                            @error('descripcion')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Precio --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Precio *</label>
                            <input type="number" name="precio" value="{{ old('precio', $producto->precio) }}" step="0.01" required
                                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('precio')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Categoría --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Categoría</label>
                            <input type="text" name="categoria" value="{{ old('categoria', $producto->categoria) }}" placeholder="Ej: Camisetas, Pantalones..."
                                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('categoria')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Talla --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Talla</label>
                            <input type="text" name="talla" value="{{ old('talla', $producto->talla) }}" placeholder="Ej: S, M, L, XL..."
                                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('talla')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Cantidad en Inventario --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Cantidad en Inventario *</label>
                            <input type="number" name="cantidad" value="{{ old('cantidad', $producto->inventario?->cantidad ?? 0) }}" required min="1"
                                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <small class="text-gray-500">Número de unidades disponibles</small>
                            @error('cantidad')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Botones --}}
                        <div class="flex gap-4">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                ✓ Actualizar Producto
                            </button>
                            <a href="{{ route('admin.productos.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition">
                                ✕ Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
