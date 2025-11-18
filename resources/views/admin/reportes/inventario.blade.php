<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold">Reporte de Inventario</h1>
                        <a href="{{ route('admin.reportes.inventario', ['formato' => 'pdf']) }}" class="bg-red-600 text-white rounded px-4 py-2 hover:bg-red-700">
                            Descargar PDF
                        </a>
                    </div>

                    {{-- Resumen --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Total de Productos</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $totalProductos }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Stock Total</p>
                            <p class="text-2xl font-bold text-green-600">{{ $inventarioTotal }} unidades</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Valor Total</p>
                            <p class="text-2xl font-bold text-purple-600">${{ number_format($valorInventario, 0) }}</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Productos Críticos</p>
                            <p class="text-2xl font-bold text-red-600">{{ $agotados->count() }}</p>
                        </div>
                    </div>

                    {{-- Alertas de bajo stock --}}
                    @if($bajoStock->count() > 0)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <h2 class="text-lg font-semibold text-yellow-800 mb-3">Productos con Bajo Stock (< 10 unidades)</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($bajoStock as $item)
                                <div class="bg-white p-3 rounded border-l-4 border-yellow-500">
                                    <p class="font-semibold text-gray-900">{{ $item->producto->nombre }}</p>
                                    <p class="text-sm text-gray-600">Disponible: <span class="font-bold text-yellow-600">{{ $item->cantidad }}</span> unidades</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Productos agotados --}}
                    @if($agotados->count() > 0)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <h2 class="text-lg font-semibold text-red-800 mb-3">🚨 Productos Agotados</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @foreach($agotados as $item)
                                <div class="bg-white p-3 rounded border-l-4 border-red-600">
                                    <p class="font-semibold text-gray-900">{{ $item->producto->nombre }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Listado completo de inventario --}}
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-4">Inventario Completo</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Producto</th>
                                        <th class="px-4 py-3 text-center">Precio</th>
                                        <th class="px-4 py-3 text-center">Stock</th>
                                        <th class="px-4 py-3 text-right">Valor Total</th>
                                        <th class="px-4 py-3 text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inventario as $item)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-4 py-3 font-semibold">{{ $item->producto->nombre }}</td>
                                            <td class="px-4 py-3 text-center">${{ number_format($item->producto->precio, 0) }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-block px-2 py-1 rounded text-xs font-bold 
                                                    @if($item->cantidad == 0) bg-red-100 text-red-800
                                                    @elseif($item->cantidad < 5) bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ $item->cantidad }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right font-semibold">${{ number_format($item->cantidad * $item->producto->precio, 0) }}</td>
                                            <td class="px-4 py-3 text-center">
                                                @if($item->cantidad == 0)
                                                    <span class="text-xs px-2 py-1 bg-red-100 text-red-800 rounded">Agotado</span>
                                                @elseif($item->cantidad < 5)
                                                    <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded">Bajo stock</span>
                                                @else
                                                    <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded">Disponible</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-4 py-3 text-center text-gray-500">Sin productos</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Movimientos recientes --}}
                    @if($movimientos->count() > 0)
                    <div>
                        <h2 class="text-xl font-semibold mb-4">📜 Movimientos Recientes</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Producto</th>
                                        <th class="px-4 py-3 text-center">Tipo</th>
                                        <th class="px-4 py-3 text-center">Cantidad</th>
                                        <th class="px-4 py-3 text-left">Motivo</th>
                                        <th class="px-4 py-3 text-center">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($movimientos as $mov)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-4 py-3 font-semibold">{{ $mov->producto->nombre }}</td>
                                            <td class="px-4 py-3 text-center">
                                                @if($mov->tipo === 'entrada')
                                                    <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded font-bold">↓ ENTRADA</span>
                                                @else
                                                    <span class="inline-block px-2 py-1 bg-red-100 text-red-800 text-xs rounded font-bold">↑ SALIDA</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center font-semibold">{{ $mov->cantidad }}</td>
                                            <td class="px-4 py-3 text-sm">{{ $mov->motivo ?? 'Sin motivo' }}</td>
                                            <td class="px-4 py-3 text-center text-xs">{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">← Volver al Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>
