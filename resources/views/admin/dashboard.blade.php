<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel de Administración</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-8 text-gray-900">👋 Bienvenido, Administrador</h1>

            {{-- Tarjetas de resumen --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total de Productos</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalProductos }}</p>
                        </div>
                        <div class="text-4xl">📦</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total de Usuarios</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalUsuarios }}</p>
                        </div>
                        <div class="text-4xl">👥</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Compras Confirmadas</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $comprasConfirmadas }}</p>
                        </div>
                        <div class="text-4xl">✅</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Ventas Este Mes</p>
                            <p class="text-3xl font-bold text-green-600">${{ number_format($ventasMes, 0) }}</p>
                        </div>
                        <div class="text-4xl">💰</div>
                    </div>
                </div>
            </div>

            {{-- Acciones rápidas --}}
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-2xl font-semibold mb-4">Acciones Rápidas</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a href="{{ route('catalogo') }}" class="p-4 border border-blue-300 rounded-lg hover:bg-blue-50 transition">
                        <p class="font-semibold text-blue-600">📊 Ver Catálogo</p>
                        <p class="text-sm text-gray-600">Visualiza los productos de la tienda</p>
                    </a>

                    <a href="{{ route('admin.productos.index') }}" class="p-4 border border-purple-300 rounded-lg hover:bg-purple-50 transition">
                        <p class="font-semibold text-purple-600">📦 Gestionar Productos</p>
                        <p class="text-sm text-gray-600">Crear, editar o eliminar productos</p>
                    </a>

                    <a href="{{ route('admin.ajustes.index') }}" class="p-4 border border-yellow-300 rounded-lg hover:bg-yellow-50 transition">
                        <p class="font-semibold text-yellow-600">📋 Ajustes de Inventario</p>
                        <p class="text-sm text-gray-600">Registrar entradas y salidas</p>
                    </a>

                    <a href="{{ route('admin.reportes.ventas', ['formato' => 'pdf']) }}" class="p-4 border border-red-300 rounded-lg hover:bg-red-50 transition">
                        <p class="font-semibold text-red-600">📥 Descargar Reporte de Ventas</p>
                        <p class="text-sm text-gray-600">Reporte en PDF (últimas ventas)</p>
                    </a>

                    <a href="{{ route('admin.reportes.inventario', ['formato' => 'pdf']) }}" class="p-4 border border-green-300 rounded-lg hover:bg-green-50 transition">
                        <p class="font-semibold text-green-600">📦 Descargar Reporte de Inventario</p>
                        <p class="text-sm text-gray-600">Reporte en PDF (stock actual)</p>
                    </a>
                </div>
            </div>

            {{-- Alerta de Stock Bajo --}}
            @if($productosStockBajo->count() > 0)
            <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg mb-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="text-3xl">⚠️</span>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-red-800 mb-3">Productos con Stock Bajo</h3>
                        <p class="text-red-700 mb-4">{{ $productosStockBajo->count() }} producto(s) tienen menos de 10 unidades:</p>
                        <div class="space-y-2">
                            @foreach($productosStockBajo as $producto)
                            <div class="flex justify-between items-center bg-white p-3 rounded">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $producto->nombre }}</p>
                                    <p class="text-sm text-gray-600">{{ $producto->categoria }} - {{ $producto->talla }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-red-600">{{ $producto->inventario?->cantidad ?? 0 }}</p>
                                    <p class="text-xs text-gray-500">unidades</p>
                                </div>
                                <a href="{{ route('admin.productos.editar', $producto) }}" class="ml-4 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Editar
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
