<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo Ajuste de Inventario</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <div class="p-8">
                    <div class="mb-8">
                        <h1 class="text-4xl font-bold text-gray-900">Registrar Ajuste</h1>
                        <p class="text-gray-600 mt-2">Gestiona entradas y salidas de inventario</p>
                    </div>

                    <form method="POST" action="{{ route('admin.ajustes.store') }}" class="space-y-8">
                        @csrf

                        {{-- Producto --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Selecciona un Producto *</label>
                            <select name="producto_id" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Seleccionar producto --</option>
                                @foreach($productos as $producto)
                                <option value="{{ $producto->id }}" @if(old('producto_id') == $producto->id) selected @endif>
                                    {{ $producto->nombre }} (Stock: {{ $producto->inventario?->cantidad ?? 0 }} unidades)
                                </option>
                                @endforeach
                            </select>
                            @error('producto_id')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Tipo --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Tipo de Movimiento *</label>
                            <div class="flex gap-6">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="tipo" value="entrada" required class="w-4 h-4 text-green-600"
                                           @if(old('tipo') === 'entrada') checked @endif>
                                    <span class="ml-3 text-green-600 font-semibold text-lg">+ Entrada (Agregar Stock)</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="tipo" value="salida" required class="w-4 h-4 text-red-600"
                                           @if(old('tipo') === 'salida') checked @endif>
                                    <span class="ml-3 text-red-600 font-semibold text-lg">- Salida (Descontar Stock)</span>
                                </label>
                            </div>
                            @error('tipo')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Cantidad --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Cantidad *</label>
                            <input type="number" name="cantidad" value="{{ old('cantidad') }}" required min="1"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Ej: 10">
                            @error('cantidad')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Motivo --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Motivo *</label>
                            <input type="text" name="motivo" value="{{ old('motivo') }}" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Ej: Reabastecimiento, Ajuste, Devolución">
                            @error('motivo')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Observación --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Observación (opcional)</label>
                            <textarea name="observacion" rows="4"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Notas adicionales...">{{ old('observacion') }}</textarea>
                            @error('observacion')
                            <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Botones --}}
                        <div class="flex gap-4 justify-center pt-6 border-t">
                            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold shadow-md">
                                Registrar Ajuste
                            </button>
                            <a href="{{ route('admin.ajustes.index') }}" class="bg-gray-400 text-white px-8 py-3 rounded-lg hover:bg-gray-500 transition font-semibold shadow-md">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
