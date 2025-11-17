<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo Ajuste de Inventario</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h1 class="text-3xl font-bold mb-6">➕ Registrar Ajuste</h1>

                    <form method="POST" action="{{ route('admin.ajustes.store') }}" class="space-y-6">
                        @csrf

                        {{-- Producto --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Producto *</label>
                            <select name="producto_id" required
                                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccionar producto...</option>
                                @foreach($productos as $producto)
                                <option value="{{ $producto->id }}">
                                    {{ $producto->nombre }} (Stock actual: {{ $producto->inventario?->cantidad ?? 0 }})
                                </option>
                                @endforeach
                            </select>
                            @error('producto_id')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Tipo --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Movimiento *</label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="tipo" value="entrada" required class="mr-2"
                                           @if(old('tipo') === 'entrada') checked @endif>
                                    <span class="text-green-600 font-semibold">➕ Entrada (Agregar Stock)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="tipo" value="salida" required class="mr-2"
                                           @if(old('tipo') === 'salida') checked @endif>
                                    <span class="text-red-600 font-semibold">➖ Salida (Descontar Stock)</span>
                                </label>
                            </div>
                            @error('tipo')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Cantidad --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Cantidad *</label>
                            <input type="number" name="cantidad" value="{{ old('cantidad') }}" required min="1"
                                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ej: 10">
                            @error('cantidad')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Motivo --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Motivo *</label>
                            <input type="text" name="motivo" value="{{ old('motivo') }}" required
                                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ej: Reabastecimiento, Ajuste, Devolución">
                            @error('motivo')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Observación --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Observación</label>
                            <textarea name="observacion" rows="3"
                                      class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Notas adicionales...">{{ old('observacion') }}</textarea>
                            @error('observacion')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Botones --}}
                        <div class="flex gap-4">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                ✓ Registrar Ajuste
                            </button>
                            <a href="{{ route('admin.ajustes.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition">
                                ✕ Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
