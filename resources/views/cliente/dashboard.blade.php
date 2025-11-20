<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mi Panel</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Bienvenida -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900">Hola, {{ $user->name }}</h1>
                <p class="text-gray-600 mt-2">Bienvenido a tu panel de cliente</p>
            </div>

            <!-- SECCIÓN 1: RESUMEN RÁPIDO (Top Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Gastado -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-900 text-sm font-semibold">Total Gastado</p>
                            <p class="text-4xl font-bold mt-2 text-gray-900">${{ number_format($totalGastado, 0) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Número de Compras -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-900 text-sm font-semibold">Compras Realizadas</p>
                            <p class="text-4xl font-bold mt-2 text-gray-900">{{ $totalCompras }}</p>
                        </div>
                    </div>
                </div>

                <!-- Última Compra -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6">
                    <div>
                        <p class="text-purple-900 text-sm font-semibold">Última Compra</p>
                        @if($ultimaCompra)
                            <p class="text-2xl font-bold mt-2 text-gray-900">${{ number_format($ultimaCompra->monto_total, 0) }}</p>
                            <p class="text-gray-800 text-sm mt-1">{{ $ultimaCompra->created_at->format('d de F') }}</p>
                        @else
                            <p class="text-2xl font-bold mt-2 text-gray-900">-</p>
                            <p class="text-gray-800 text-sm mt-1">Sin compras aún</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8">
                <!-- SECCIÓN 2: HISTORIAL DE COMPRAS RECIENTES -->
                <div>
                    <div class="bg-white rounded-lg shadow-sm sm:rounded-lg p-6 mb-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Compras Recientes</h2>
                            <a href="{{ route('cliente.historial') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                Ver todas →
                            </a>
                        </div>

                        @if($comprasRecientes->isEmpty())
                            <div class="text-center py-12">
                                <p class="text-gray-500 text-lg">No tienes compras aún</p>
                                <a href="{{ route('catalogo') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                                    Ir al Catálogo
                                </a>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($comprasRecientes as $compra)
                                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h3 class="font-semibold text-lg">Compra #{{ $compra->numero_compra }}</h3>
                                                <p class="text-sm text-gray-500">{{ $compra->created_at->format('d de F de Y - H:i') }}</p>
                                            </div>
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full font-semibold">
                                                {{ ucfirst($compra->estado) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-end mt-3 pt-3 border-t">
                                            <div>
                                                <p class="text-sm text-gray-600">{{ count($compra->detalles ?? []) }} producto(s)</p>
                                            </div>
                                            <p class="text-lg font-bold text-gray-900">${{ number_format($compra->monto_total, 0) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- SECCIÓN 3: MI INFORMACIÓN PERSONAL -->
                    <div class="bg-white rounded-lg shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Mi Información</h2>
                            <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                Editar ✎
                            </a>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="text-sm text-gray-600 font-semibold">Nombre</label>
                                <p class="text-lg text-gray-900">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 font-semibold">Email</label>
                                <p class="text-lg text-gray-900">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 4: TELÉFONO -->
                    <div class="bg-white rounded-lg shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Teléfono</h2>
                            <button onclick="document.getElementById('editPhoneForm').classList.toggle('hidden')" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                Editar ✎
                            </button>
                        </div>

                        <div id="phoneDisplay" class="mb-4">
                            <p class="text-lg text-gray-900">{{ $user->telefono ?? 'No registrado' }}</p>
                        </div>

                        <div id="editPhoneForm" class="hidden border-t pt-4">
                            <form action="{{ route('cliente.telefono.update') }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label for="telefono" class="block text-sm font-semibold text-gray-700 mb-2">Nuevo Teléfono</label>
                                    <input type="tel" id="telefono" name="telefono" value="{{ $user->telefono }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Ej: +57 320 123 4567">
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Guardar</button>
                                    <button type="button" onclick="document.getElementById('editPhoneForm').classList.add('hidden')" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- SECCIÓN 5: MIS DIRECCIONES -->
                    <div class="bg-white rounded-lg shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Mis Direcciones</h2>
                            <button onclick="document.getElementById('addAddressForm').classList.toggle('hidden')" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                + Agregar Dirección
                            </button>
                        </div>

                        <!-- Formulario para agregar dirección -->
                        <div id="addAddressForm" class="hidden bg-gray-50 p-6 rounded-lg mb-6 border">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Nueva Dirección</h3>
                            <form action="{{ route('cliente.direcciones.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="nombre_direccion" class="block text-sm font-semibold text-gray-700 mb-2">Nombre de la Dirección *</label>
                                        <input type="text" id="nombre_direccion" name="nombre_direccion" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Ej: Casa, Oficina" required>
                                    </div>
                                    <div>
                                        <label for="ciudad" class="block text-sm font-semibold text-gray-700 mb-2">Ciudad *</label>
                                        <input type="text" id="ciudad" name="ciudad" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Ej: Bogotá" required>
                                    </div>
                                    <div>
                                        <label for="departamento" class="block text-sm font-semibold text-gray-700 mb-2">Departamento *</label>
                                        <input type="text" id="departamento" name="departamento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Ej: Cundinamarca" required>
                                    </div>
                                    <div>
                                        <label for="codigo_postal" class="block text-sm font-semibold text-gray-700 mb-2">Código Postal *</label>
                                        <input type="text" id="codigo_postal" name="codigo_postal" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Ej: 110111" required>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="calle" class="block text-sm font-semibold text-gray-700 mb-2">Calle *</label>
                                        <input type="text" id="calle" name="calle" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Ej: Carrera 7" required>
                                    </div>
                                    <div>
                                        <label for="numero" class="block text-sm font-semibold text-gray-700 mb-2">Número *</label>
                                        <input type="text" id="numero" name="numero" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Ej: 45-23" required>
                                    </div>
                                    <div>
                                        <label for="apartamento" class="block text-sm font-semibold text-gray-700 mb-2">Apartamento (Opcional)</label>
                                        <input type="text" id="apartamento" name="apartamento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Ej: 301">
                                    </div>
                                    <div>
                                        <label for="telefono_dir" class="block text-sm font-semibold text-gray-700 mb-2">Teléfono (Opcional)</label>
                                        <input type="tel" id="telefono_dir" name="telefono" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Ej: +57 320 123 4567">
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Guardar Dirección</button>
                                    <button type="button" onclick="document.getElementById('addAddressForm').classList.add('hidden')" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Cancelar</button>
                                </div>
                            </form>
                        </div>

                        <!-- Lista de direcciones -->
                        @if($direcciones->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500 text-sm">No tienes direcciones guardadas aún</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($direcciones as $dir)
                                    <div class="border rounded-lg p-4 @if($dir->es_principal) border-blue-500 bg-blue-50 @endif">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h3 class="font-semibold text-gray-900">{{ $dir->nombre_direccion }}</h3>
                                                <p class="text-sm text-gray-600">{{ $dir->calle }} #{{ $dir->numero }}@if($dir->apartamento), {{ $dir->apartamento }}@endif</p>
                                                <p class="text-sm text-gray-600">{{ $dir->ciudad }}, {{ $dir->departamento }} - CP: {{ $dir->codigo_postal }}</p>
                                                @if($dir->telefono)
                                                    <p class="text-sm text-gray-600">Tel: {{ $dir->telefono }}</p>
                                                @endif
                                            </div>
                                            @if($dir->es_principal)
                                                <span class="text-xs bg-blue-500 text-white px-2 py-1 rounded-full">Principal</span>
                                            @endif
                                        </div>
                                        <div class="flex gap-3 text-sm mt-3 pt-3 border-t">
                                            <button onclick="editAddress({{ $dir->id }})" class="text-blue-600 hover:text-blue-800 font-semibold">Editar</button>
                                            @if(!$dir->es_principal)
                                                <form action="{{ route('cliente.direcciones.principal', $dir->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-gray-600 hover:text-gray-800 font-semibold">Hacer Principal</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('cliente.direcciones.destroy', $dir->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta dirección?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="mt-12">
                <div class="bg-gray-50 rounded-lg p-6 border-2 border-dashed border-gray-300">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Acciones Rápidas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('catalogo') }}" class="bg-white p-4 border rounded-lg hover:shadow-md transition text-center">
                            <p class="font-semibold text-gray-900">Continuar Comprando</p>
                        </a>
                        <a href="{{ route('cliente.historial') }}" class="bg-white p-4 border rounded-lg hover:shadow-md transition text-center">
                            <p class="font-semibold text-gray-900">Ver Todas las Compras</p>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="bg-white p-4 border rounded-lg hover:shadow-md transition text-center">
                            <p class="font-semibold text-gray-900">Editar Perfil</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>