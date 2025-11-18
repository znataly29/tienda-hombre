<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajustes de Inventario</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold">Historial de Ajustes</h1>
                        <a href="{{ route('admin.ajustes.crear') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            ➕ Nuevo Ajuste
                        </a>
                    </div>

                    {{-- Tabla de ajustes --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left">Fecha</th>
                                    <th class="px-4 py-3 text-left">Producto</th>
                                    <th class="px-4 py-3 text-center">Tipo</th>
                                    <th class="px-4 py-3 text-center">Cantidad</th>
                                    <th class="px-4 py-3 text-left">Motivo</th>
                                    <th class="px-4 py-3 text-left">Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ajustes as $ajuste)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $ajuste->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 font-semibold">{{ $ajuste->producto->nombre }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($ajuste->tipo === 'entrada')
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                ➕ Entrada
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                                ➖ Salida
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold">{{ $ajuste->cantidad }}</td>
                                    <td class="px-4 py-3">{{ $ajuste->motivo }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $ajuste->observacion ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center text-gray-500">No hay ajustes registrados</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-6">
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
