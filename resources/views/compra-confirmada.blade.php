<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-900">
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-green-600">¡Compra Confirmada!</h1>
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded p-6 mb-6">
                        <p class="text-gray-700 mb-3">
                            Tu compra ha sido procesada exitosamente. Recibirás un correo de confirmación en breve.
                        </p>
                        <p class="text-lg font-semibold text-gray-900">
                            Número de compra: <span class="text-green-600">#{{ $compra->numero_compra }}</span>
                        </p>
                    </div>

                    <div class="bg-gray-50 border rounded p-4 mb-6 text-left">
                        <h2 class="font-semibold mb-3">Detalles de la compra</h2>
                        <div class="space-y-2 text-sm">
                            <p><strong>Monto total:</strong> ${{ number_format($compra->monto_total, 0) }}</p>
                            <p><strong>Estado:</strong> <span class="inline-block bg-green-200 text-green-800 px-2 py-1 rounded">{{ ucfirst($compra->estado) }}</span></p>
                            <p><strong>Fecha:</strong> {{ $compra->created_at ? $compra->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('cliente.historial') }}" class="inline-block w-full px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Ver mis compras
                        </a>
                        <a href="{{ route('catalogo') }}" class="inline-block w-full px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                            Continuar comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
