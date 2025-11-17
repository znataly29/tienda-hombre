<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h1 class="text-3xl font-bold mb-6">📊 Reporte de Ventas</h1>

                    {{-- Filtros --}}
                    <form method="GET" action="{{ route('admin.reportes.ventas') }}" class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2">Desde:</label>
                                <input type="date" name="desde" value="{{ $desde->format('Y-m-d') }}" class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2">Hasta:</label>
                                <input type="date" name="hasta" value="{{ $hasta->format('Y-m-d') }}" class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2">Categoría:</label>
                                <select name="categoria" class="w-full border rounded px-3 py-2">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categorias as $cat)
                                    <option value="{{ $cat }}" @if($categoria === $cat) selected @endif>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end gap-2">
                                <button type="submit" class="flex-1 bg-blue-600 text-white rounded px-4 py-2 hover:bg-blue-700">Filtrar</button>
                                <a href="{{ route('admin.reportes.ventas', array_merge(request()->query(), ['formato' => 'pdf'])) }}" class="flex-1 bg-red-600 text-white rounded px-4 py-2 hover:bg-red-700 text-center">📥 PDF</a>
                            </div>
                        </div>
                    </form>

                    {{-- Resumen --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Total de Ventas</p>
                            <p class="text-2xl font-bold text-blue-600">${{ number_format($totalVentas, 0) }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Cantidad de Pedidos</p>
                            <p class="text-2xl font-bold text-green-600">{{ $cantidadPedidos }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Ticket Promedio</p>
                            <p class="text-2xl font-bold text-purple-600">${{ number_format($ticketPromedio, 0) }}</p>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg">
                            <p class="text-gray-600 text-sm">Período</p>
                            <p class="text-sm font-semibold text-orange-600">{{ $desde->format('d/m/Y') }} - {{ $hasta->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    {{-- Productos más vendidos --}}
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-4">🏆 Top 5 Productos Vendidos</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Producto</th>
                                        <th class="px-4 py-3 text-center">Cantidad</th>
                                        <th class="px-4 py-3 text-right">Monto Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($productosMasVendidos as $prod)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-4 py-3">{{ $prod['nombre'] }}</td>
                                            <td class="px-4 py-3 text-center"><span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $prod['cantidad'] }}</span></td>
                                            <td class="px-4 py-3 text-right font-semibold">${{ number_format($prod['monto'], 0) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="px-4 py-3 text-center text-gray-500">Sin datos</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Listado de compras --}}
                    <div>
                        <h2 class="text-xl font-semibold mb-4">📋 Detalle de Compras</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left">ID</th>
                                        <th class="px-4 py-3 text-left">Cliente</th>
                                        <th class="px-4 py-3 text-center">Fecha</th>
                                        <th class="px-4 py-3 text-right">Monto</th>
                                        <th class="px-4 py-3 text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($compras as $compra)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="px-4 py-3 font-semibold">#{{ $compra->id }}</td>
                                            <td class="px-4 py-3">{{ $compra->usuario->name }}</td>
                                            <td class="px-4 py-3 text-center text-sm">{{ $compra->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3 text-right font-semibold">${{ number_format($compra->monto_total, 0) }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">{{ ucfirst($compra->estado) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-4 py-3 text-center text-gray-500">Sin compras en este período</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">← Volver al Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>
