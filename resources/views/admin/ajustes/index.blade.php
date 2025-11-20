<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajustes de Inventario</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Mensaje de éxito --}}
            @if(session('mensaje'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('mensaje') }}
            </div>
            @endif

            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900">Historial de Ajustes</h1>
                            <p class="text-gray-600 mt-2">Gestión de entradas y salidas de inventario</p>
                        </div>
                        <a href="{{ route('admin.ajustes.crear') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold shadow-md">
                            + Nuevo Ajuste
                        </a>
                    </div>

                    {{-- Tabla de ajustes --}}
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="w-full text-sm">
                            <thead class="bg-gradient-to-r from-blue-50 to-blue-100 border-b">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Fecha</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Producto</th>
                                    <th class="px-6 py-4 text-center font-semibold text-gray-700">Tipo</th>
                                    <th class="px-6 py-4 text-center font-semibold text-gray-700">Cantidad</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Motivo</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ajustes as $ajuste)
                                <tr class="border-b hover:bg-blue-50 transition">
                                    <td class="px-6 py-4 text-gray-800">{{ $ajuste->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $ajuste->producto->nombre }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($ajuste->tipo === 'entrada')
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                + Entrada
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                                - Salida
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-900">{{ $ajuste->cantidad }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $ajuste->motivo }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $ajuste->observacion ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                            <p class="text-lg">No hay ajustes registrados</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-8 flex justify-center">
                        {{ $ajustes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
