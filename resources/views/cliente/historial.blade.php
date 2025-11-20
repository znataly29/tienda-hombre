<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-6">Historial de Compras</h1>

                    @if($compras->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg mb-4">No tienes compras aún</p>
                            <a href="{{ route('catalogo') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                                Explorar catálogo
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($compras as $compra)
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h3 class="text-lg font-semibold">Compra #{{ $compra->numero_compra }}</h3>
                                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($compra->created_at)->locale('es')->isoFormat('DD MMMM YYYY') }}</p>
                                        </div>
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                                            {{ ucfirst($compra->estado) }}
                                        </span>
                                    </div>

                                    <div class="my-3 pt-3 border-t">
                                        <h4 class="font-semibold mb-2">Productos:</h4>
                                        <div class="space-y-1 text-sm">
                                            @forelse($compra->detalles ?? [] as $item)
                                                <div class="flex justify-between">
                                                    <span>
                                                        {{ $item['nombre'] }} 
                                                        <span class="text-gray-500">(x{{ $item['cantidad'] }})</span>
                                                    </span>
                                                    <span>${{ number_format($item['cantidad'] * $item['precio_unitario'], 2) }}</span>
                                                </div>
                                            @empty
                                                <p class="text-gray-500 italic">Sin detalles de productos</p>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="flex justify-end pt-3 border-t">
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">Total:</p>
                                            <p class="text-2xl font-bold text-gray-900">${{ number_format($compra->monto_total, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('catalogo') }}" class="text-blue-600 hover:text-blue-800">
                                ← Volver al catálogo
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
