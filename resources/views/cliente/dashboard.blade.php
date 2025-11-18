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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- SECCIÓN 2: HISTORIAL DE COMPRAS RECIENTES (Main Content - 2 columns) -->
                <div class="lg:col-span-2">
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
                            <div>
                                <label class="text-sm text-gray-600 font-semibold">Teléfono</label>
                                <p class="text-lg text-gray-900">{{ $user->telefono ?? 'No registrado' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 font-semibold">Dirección de Envío Principal</label>
                                <p class="text-lg text-gray-900">{{ $user->direccion ?? 'No registrada' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 4: MIS DIRECCIONES DE ENVÍO (Sidebar - 1 column) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm sm:rounded-lg p-6 sticky top-4">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Mis Direcciones</h2>
                            <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                +
                            </a>
                        </div>

                        @if($direcciones->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500 text-sm mb-4">No tienes direcciones guardadas</p>
                                <button class="w-full bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 text-sm">
                                    Agregar Dirección
                                </button>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($direcciones as $dir)
                                    <div class="border rounded-lg p-4 @if($dir->es_principal) border-blue-500 bg-blue-50 @endif">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="font-semibold text-gray-900">{{ $dir->nombre_direccion }}</h3>
                                            @if($dir->es_principal)
                                                <span class="text-xs bg-blue-500 text-white px-2 py-1 rounded-full">Principal</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 mb-3">
                                            {{ $dir->calle }} #{{ $dir->numero }}
                                            @if($dir->apartamento), {{ $dir->apartamento }} @endif<br>
                                            {{ $dir->ciudad }}, {{ $dir->departamento }}<br>
                                            CP: {{ $dir->codigo_postal }}
                                        </p>
                                        @if($dir->telefono)
                                            <p class="text-sm text-gray-600 mb-3">📞 {{ $dir->telefono }}</p>
                                        @endif
                                        <div class="flex gap-2 text-xs">
                                            <button class="text-blue-600 hover:text-blue-800 font-semibold">Editar</button>
                                            @if(!$dir->es_principal)
                                                <button class="text-gray-600 hover:text-gray-800 font-semibold">Hacer Principal</button>
                                            @endif
                                            <button class="text-red-600 hover:text-red-800 font-semibold">Eliminar</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button class="w-full mt-4 bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 text-sm">
                                + Agregar Dirección
                            </button>
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