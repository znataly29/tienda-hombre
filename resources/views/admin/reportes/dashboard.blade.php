<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900">📊 Dashboard de Reportes</h1>
                <div class="flex gap-2">
                    <a href="{{ route('admin.reportes.ventas', ['formato' => 'pdf']) }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">📊 PDF Ventas</a>
                    <a href="{{ route('admin.reportes.inventario', ['formato' => 'pdf']) }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">📦 PDF Inventario</a>
                </div>
            </div>

            {{-- Tarjetas de resumen --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Ventas del día --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Ventas Hoy</p>
                            <p class="text-3xl font-bold text-gray-900">${{ number_format($ventasHoy, 0) }}</p>
                        </div>
                        <div class="text-4xl text-blue-600">💰</div>
                    </div>
                </div>

                {{-- Ventas semana --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Ventas Semana</p>
                            <p class="text-3xl font-bold text-gray-900">${{ number_format($ventasSemana, 0) }}</p>
                        </div>
                        <div class="text-4xl text-green-600">📈</div>
                    </div>
                </div>

                {{-- Ventas mes --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Ventas Mes</p>
                            <p class="text-3xl font-bold text-gray-900">${{ number_format($ventasMes, 0) }}</p>
                        </div>
                        <div class="text-4xl text-purple-600">💳</div>
                    </div>
                </div>

                {{-- Pedidos confirmados --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Pedidos Confirmados</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $comprasConfirmadas }}</p>
                        </div>
                        <div class="text-4xl text-orange-600">📦</div>
                    </div>
                </div>
            </div>

            {{-- Segunda fila de tarjetas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Ticket promedio --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Ticket Promedio</p>
                            <p class="text-3xl font-bold text-gray-900">${{ number_format($ticketPromedio, 0) }}</p>
                        </div>
                        <div class="text-4xl">🎯</div>
                    </div>
                </div>

                {{-- Inventario total --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Stock Total</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $inventarioTotal }} unidades</p>
                        </div>
                        <div class="text-4xl">📦</div>
                    </div>
                </div>

                {{-- Valor inventario --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Valor Inventario</p>
                            <p class="text-3xl font-bold text-gray-900">${{ number_format($valorInventario, 0) }}</p>
                        </div>
                        <div class="text-4xl text-yellow-600">💎</div>
                    </div>
                </div>

                {{-- Alertas de stock --}}
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Bajo Stock</p>
                            <p class="text-3xl font-bold text-red-600">{{ $productosBajoStock }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $productosAgotados }} agotados</p>
                        </div>
                        <div class="text-4xl">⚠️</div>
                    </div>
                </div>
            </div>

            {{-- Gráfica de ventas últimos 7 días --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Ventas Últimos 7 Días</h2>
                    <div class="space-y-3">
                        @foreach($ventasUltimaSemana as $item)
                            <div class="flex items-center gap-3">
                                <span class="w-12 text-sm font-semibold">{{ $item['fecha'] }}</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-8 relative overflow-hidden">
                                    <div class="bg-blue-600 h-full" style="width: {{ $item['monto'] > 0 ? ($item['monto'] / 100000 * 100) : 0 }}%;"></div>
                                    <span class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-gray-900">
                                        ${{ number_format($item['monto'], 0) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Producto más vendido --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Top Producto</h2>
                    @if($productoMasVendido)
                        <div class="text-center">
                            <p class="text-3xl font-bold text-green-600">{{ $productoMasVendido['cantidad'] }}</p>
                            <p class="text-gray-600 text-sm mt-2">{{ $productoMasVendido['nombre'] }}</p>
                            <p class="text-gray-500 text-xs mt-1">unidades vendidas</p>
                        </div>
                    @else
                        <p class="text-gray-500 text-center">Sin ventas aún</p>
                    @endif
                </div>
            </div>

            {{-- Alertas de bajo stock --}}
            @if($bajoStock->count() > 0)
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4 text-red-700">⚠️ Productos con Bajo Stock</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($bajoStock as $item)
                        <div class="bg-white p-3 rounded border-l-4 border-red-500">
                            <p class="font-semibold text-gray-900">{{ $item->producto->nombre }}</p>
                            <p class="text-sm text-gray-600">Stock actual: <span class="font-bold text-red-600">{{ $item->cantidad }}</span> unidades</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Botones de reportes detallados --}}
            <div class="flex gap-4 justify-end">
                <a href="{{ route('admin.reportes.ventas') }}" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    📊 Ver Reporte de Ventas
                </a>
                <a href="{{ route('admin.reportes.inventario') }}" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    📦 Ver Reporte de Inventario
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
