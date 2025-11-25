<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Producto</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h1 class="text-3xl font-bold mb-6">➕ Nuevo Producto</h1>

                    <form method="POST" action="{{ route('admin.productos.store') }}" class="space-y-6">
                        @csrf

                        {{-- Nombre --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre *</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" required
                                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('nombre')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                            <textarea name="descripcion" rows="3"
                                      class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Precio --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Precio *</label>
                            <input type="number" name="precio" value="{{ old('precio') }}" step="0.01" required
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
                                <option value="Camisetas" {{ old('categoria') === 'Camisetas' ? 'selected' : '' }}>Camisetas</option>
                                <option value="Camisas" {{ old('categoria') === 'Camisas' ? 'selected' : '' }}>Camisas</option>
                                <option value="Sudaderas" {{ old('categoria') === 'Sudaderas' ? 'selected' : '' }}>Sudaderas</option>
                                <option value="Chaquetas" {{ old('categoria') === 'Chaquetas' ? 'selected' : '' }}>Chaquetas</option>
                                <option value="Shorts" {{ old('categoria') === 'Shorts' ? 'selected' : '' }}>Shorts</option>
                                <option value="Pantalones" {{ old('categoria') === 'Pantalones' ? 'selected' : '' }}>Pantalones</option>
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
                                <option value="M" {{ old('talla') === 'M' ? 'selected' : '' }}>M</option>
                                <option value="L" {{ old('talla') === 'L' ? 'selected' : '' }}>L</option>
                                <option value="XL" {{ old('talla') === 'XL' ? 'selected' : '' }}>XL</option>
                                <option value="30" {{ old('talla') === '30' ? 'selected' : '' }}>30</option>
                                <option value="32" {{ old('talla') === '32' ? 'selected' : '' }}>32</option>
                                <option value="34" {{ old('talla') === '34' ? 'selected' : '' }}>34</option>
                                <option value="36" {{ old('talla') === '36' ? 'selected' : '' }}>36</option>
                            </select>
                            @error('talla')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Cantidad en Inventario --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Cantidad en Inventario</label>
                            <input type="number" name="cantidad" value="{{ old('cantidad', 1) }}" min="1" required
                                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-gray-500 text-sm mt-1">Especifica cuántas unidades deseas agregar al inventario</p>
                            @error('cantidad')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Botones --}}
                        <div class="flex gap-4">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                ✓ Crear Producto
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
