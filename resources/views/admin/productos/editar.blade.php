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
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Categoría *</label>
                            <select name="categoria" id="categoria" required
                                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecciona una categoría</option>
                                <option value="Camisetas" {{ old('categoria', $producto->categoria) === 'Camisetas' ? 'selected' : '' }}>Camisetas</option>
                                <option value="Camisas" {{ old('categoria', $producto->categoria) === 'Camisas' ? 'selected' : '' }}>Camisas</option>
                                <option value="Sudaderas" {{ old('categoria', $producto->categoria) === 'Sudaderas' ? 'selected' : '' }}>Sudaderas</option>
                                <option value="Chaquetas" {{ old('categoria', $producto->categoria) === 'Chaquetas' ? 'selected' : '' }}>Chaquetas</option>
                                <option value="Shorts" {{ old('categoria', $producto->categoria) === 'Shorts' ? 'selected' : '' }}>Shorts</option>
                                <option value="Pantalones" {{ old('categoria', $producto->categoria) === 'Pantalones' ? 'selected' : '' }}>Pantalones</option>
                            </select>
                            @error('categoria')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Talla --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Talla *</label>
                            <select name="talla" id="talla" required
                                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecciona una talla</option>
                                <option value="M" {{ old('talla', $producto->talla) === 'M' ? 'selected' : '' }}>M</option>
                                <option value="L" {{ old('talla', $producto->talla) === 'L' ? 'selected' : '' }}>L</option>
                                <option value="XL" {{ old('talla', $producto->talla) === 'XL' ? 'selected' : '' }}>XL</option>
                                <option value="30" {{ old('talla', $producto->talla) === '30' ? 'selected' : '' }}>30</option>
                                <option value="32" {{ old('talla', $producto->talla) === '32' ? 'selected' : '' }}>32</option>
                                <option value="34" {{ old('talla', $producto->talla) === '34' ? 'selected' : '' }}>34</option>
                                <option value="36" {{ old('talla', $producto->talla) === '36' ? 'selected' : '' }}>36</option>
                            </select>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoriaSelect = document.getElementById('categoria');
        const tallaSelect = document.getElementById('talla');
        const form = document.querySelector('form');

        // Reglas de validación: categoría -> tallas permitidas
        const validacionesPorCategoria = {
            'Camisetas': ['M', 'L', 'XL'],
            'Camisas': ['M', 'L', 'XL'],
            'Sudaderas': ['M', 'L', 'XL'],
            'Chaquetas': ['M', 'L', 'XL'],
            'Shorts': ['M', 'L', 'XL'],
            'Pantalones': ['30', '32', '34', '36']
        };

        // Mensajes por categoría
        const mensajesPorCategoria = {
            'Camisetas': 'Para camisetas solo están disponibles las tallas: M, L, XL',
            'Camisas': 'Para camisas solo están disponibles las tallas: M, L, XL',
            'Sudaderas': 'Para sudaderas solo están disponibles las tallas: M, L, XL',
            'Chaquetas': 'Para chaquetas solo están disponibles las tallas: M, L, XL',
            'Shorts': 'Para shorts solo están disponibles las tallas: M, L, XL',
            'Pantalones': 'Para pantalones solo están disponibles las tallas: 30, 32, 34, 36'
        };

        function validarTalla() {
            const categoriaSeleccionada = categoriaSelect.value;
            const tallaSeleccionada = tallaSelect.value;

            if (!categoriaSeleccionada || !tallaSeleccionada) {
                return true; // No validar si no hay selección
            }

            const tallasPermitidas = validacionesPorCategoria[categoriaSeleccionada] || [];
            
            if (!tallasPermitidas.includes(tallaSeleccionada)) {
                alert(mensajesPorCategoria[categoriaSeleccionada]);
                tallaSelect.value = '';
                return false;
            }
            return true;
        }

        // Validar cuando cambia la categoría
        categoriaSelect.addEventListener('change', function() {
            const nuevaCategoria = this.value;
            if (nuevaCategoria) {
                // Resetear la talla cuando cambia la categoría
                tallaSelect.value = '';
            }
        });

        // Validar cuando cambia la talla
        tallaSelect.addEventListener('change', validarTalla);

        // Validar antes de enviar el formulario
        form.addEventListener('submit', function(e) {
            if (!validarTalla()) {
                e.preventDefault();
            }
        });
    });
</script>
