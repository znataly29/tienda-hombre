<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajustes de Inventario</h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            {{-- Encabezado --}}
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Historial de Ajustes</h1>
                    <p class="text-gray-600 text-sm mt-1">Gestiona todas las entradas y salidas de inventario</p>
                </div>
                <a href="{{ route('admin.ajustes.crear') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold shadow-md hover:shadow-lg">
                    + Nuevo Ajuste
                </a>
            </div>

            {{-- Tabla Principal --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        {{-- Encabezado de Tabla --}}
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                                <th class="px-8 py-5 text-left text-sm font-bold tracking-wide">Fecha</th>
                                <th class="px-8 py-5 text-left text-sm font-bold tracking-wide">Producto</th>
                                <th class="px-8 py-5 text-center text-sm font-bold tracking-wide">Tipo</th>
                                <th class="px-8 py-5 text-center text-sm font-bold tracking-wide">Cantidad</th>
                                <th class="px-8 py-5 text-left text-sm font-bold tracking-wide">Motivo</th>
                                <th class="px-8 py-5 text-left text-sm font-bold tracking-wide">Observación</th>
                            </tr>
                        </thead>

                        {{-- Cuerpo de Tabla --}}
                        <tbody class="divide-y divide-gray-200">
                            @forelse($ajustes as $ajuste)
                            <tr class="hover:bg-blue-50 transition duration-200 ease-in-out">
                                <td class="px-8 py-5 text-sm text-gray-900 font-medium whitespace-nowrap">
                                    {{ $ajuste->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-8 py-5 text-sm text-gray-900 font-semibold">
                                    <span class="inline-block bg-gray-100 px-3 py-1 rounded-lg text-gray-800">
                                        {{ $ajuste->producto->nombre }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($ajuste->tipo === 'entrada')
                                        <span class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Entrada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Salida
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-center text-sm text-gray-900 font-bold">
                                    <span class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-lg border border-blue-200">
                                        {{ $ajuste->cantidad }} unidades
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-sm text-gray-700">
                                    {{ $ajuste->motivo }}
                                </td>
                                <td class="px-8 py-5 text-sm text-gray-600">
                                    <span class="text-gray-500 italic">{{ $ajuste->observacion ?? '—' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-gray-500 text-lg font-semibold">No hay ajustes registrados</p>
                                        <p class="text-gray-400 text-sm mt-1">Los ajustes aparecerán aquí cuando se registren</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if($ajustes->hasPages())
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex justify-center">
                    {{ $ajustes->links() }}
                </div>
                @endif
            </div>

            {{-- Mensaje de éxito --}}
            @if(session('mensaje'))
            <div class="mt-6 p-5 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-green-800 font-semibold">{{ session('mensaje') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
