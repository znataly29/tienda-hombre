<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-6">Confirmación de Compra</h1>

                    {{-- Mensajes de error --}}
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">❌</span>
                                <div>
                                    <h3 class="font-semibold text-red-800 mb-2">Error en la compra</h3>
                                    <ul class="text-sm text-red-700 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start gap-3">
                                <span class="text-2xl">❌</span>
                                <div>
                                    <h3 class="font-semibold text-red-800 mb-2">Error</h3>
                                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('checkout.finalizar') }}" method="POST">
                        @csrf

                        {{-- RESUMEN DEL PEDIDO --}}
                        <div class="bg-gray-50 border rounded p-4 mb-6">
                            <h2 class="text-xl font-semibold mb-4">Resumen del Pedido</h2>

                            <div class="space-y-3 mb-4">
                                @foreach ($carrito as $item)
                                    <div class="flex justify-between items-start border-b pb-3">
                                        <div>
                                            <p class="font-semibold">{{ $item->producto->nombre }}</p>
                                            <p class="text-sm text-gray-500">
                                                Cantidad: <span class="font-semibold">{{ $item->cantidad }}</span> × ${{ number_format($item->precio_unitario, 0) }}
                                            </p>
                                        </div>
                                        <div class="text-right font-semibold">
                                            ${{ number_format($item->cantidad * $item->precio_unitario, 0) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t pt-3 space-y-2">
                                <div class="flex justify-between">
                                    <span>Subtotal:</span>
                                    <span>${{ number_format($subtotal, 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Envío:</span>
                                    <span>${{ number_format($envio, 0) }}</span>
                                </div>
                                <div class="flex justify-between text-xl font-bold text-green-600">
                                    <span>Total a pagar:</span>
                                    <span>${{ number_format($total, 0) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- DATOS DEL COMPRADOR --}}
                        <div class="bg-gray-50 border rounded p-4 mb-6">
                            <h2 class="text-xl font-semibold mb-4">Datos del Comprador</h2>
                            <div class="space-y-2">
                                <p><strong>Nombre:</strong> {{ $usuario->name }}</p>
                                <p><strong>Correo:</strong> {{ $usuario->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="inline-block mt-3 text-blue-600 hover:underline text-sm">
                                Editar datos
                            </a>
                        </div>

                        {{-- TÉRMINOS Y CONDICIONES --}}
                        <div class="mb-6">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="terminos" value="1" required class="w-4 h-4 border-gray-300 rounded">
                                <span class="text-gray-700">
                                    Acepto los <a href="#" class="text-blue-600 hover:underline">términos y condiciones</a> 
                                    y la <a href="#" class="text-blue-600 hover:underline">política de privacidad</a>.
                                </span>
                            </label>
                            @error('terminos')
                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- BOTONES --}}
                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold">
                                Confirmar Compra
                            </button>
                            <a href="{{ route('carrito.index') }}" class="flex-1 px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 text-center font-semibold">
                                Volver al Carrito
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
