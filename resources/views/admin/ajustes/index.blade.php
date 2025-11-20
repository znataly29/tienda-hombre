<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajustes de Inventario</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Historial de Ajustes</h1>
                            <p class="text-gray-600 text-sm mt-1">Registro de movimientos de inventario</p>
                        </div>
                        <a href="{{ route('admin.ajustes.crear') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                            Nuevo Ajuste
                        </a>
                    </div>

                    {{-- Tabla de ajustes --}}
                    <div class="overflow-x-auto rounded-lg border">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Fecha</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Producto</th>
                                    <th class="px-6 py-3 text-center font-semibold text-gray-700">Tipo</th>
                                    <th class="px-6 py-3 text-center font-semibold text-gray-700">Cantidad</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Motivo</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ajustes as $ajuste)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-3 text-gray-800">{{ $ajuste->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-3 font-semibold text-gray-900">{{ $ajuste->producto->nombre }}</td>
                                    <td class="px-6 py-3 text-center">
                                        @if($ajuste->tipo === 'entrada')
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                Entrada
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                                Salida
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-center font-bold text-gray-900">{{ $ajuste->cantidad }}</td>
                                    <td class="px-6 py-3 text-gray-700">{{ $ajuste->motivo }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $ajuste->observacion ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        No hay ajustes registrados
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-6 flex justify-center">
                        {{ $ajustes->links() }}
                    </div>

                    {{-- Mensaje de éxito --}}
                    @if(session('mensaje'))
                    <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('mensaje') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
