<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-6">Carrito de Compras</h1>

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

                    @if($carrito->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg mb-4">Tu carrito está vacío</p>
                            <a href="{{ route('catalogo') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                                Continuar comprando
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 border-b">
                                    <tr>
                                        <th class="text-left p-3">Producto</th>
                                        <th class="text-center p-3">Precio</th>
                                        <th class="text-center p-3">Cantidad</th>
                                        <th class="text-center p-3">Subtotal</th>
                                        <th class="text-center p-3">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($carrito as $item)
                                        <tr class="border-b hover:bg-gray-50" id="item-{{ $item->id }}">
                                            <td class="p-3">
                                                <div>
                                                    <p class="font-semibold">{{ $item->producto->nombre }}</p>
                                                    <p class="text-xs text-gray-500">Talla: {{ $item->producto->talla }}</p>
                                                </div>
                                            </td>
                                            <td class="text-center p-3">
                                                ${{ number_format($item->precio_unitario, 2) }}
                                            </td>
                                            <td class="text-center p-3">
                                                @auth
                                                <div class="flex items-center justify-center gap-2">
                                                    <button type="button" 
                                                            class="btn-disminuir px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded"
                                                            data-carrito-id="{{ $item->id }}"
                                                            data-cantidad="{{ $item->cantidad }}">
                                                        -
                                                    </button>
                                                    <span class="w-8 text-center" id="cantidad-{{ $item->id }}">
                                                        {{ $item->cantidad }}
                                                    </span>
                                                    <button type="button" 
                                                            class="btn-aumentar px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded"
                                                            data-carrito-id="{{ $item->id }}"
                                                            data-cantidad="{{ $item->cantidad }}">
                                                        +
                                                    </button>
                                                </div>
                                                @else
                                                <div class="w-20 text-center">{{ $item->cantidad }}</div>
                                                @endauth
                                            </td>
                                            <td class="text-center p-3 font-semibold" id="subtotal-{{ $item->id }}">
                                                ${{ number_format($item->cantidad * $item->precio_unitario, 2) }}
                                            </td>
                                            <td class="text-center p-3">
                                                @auth
                                                <button type="button" 
                                                        class="btn-eliminar text-red-600 hover:text-red-800 font-semibold"
                                                        data-carrito-id="{{ $item->id }}">
                                                    Eliminar
                                                </button>
                                                @else
                                                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Iniciar sesión para modificar</a>
                                                @endauth
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Total y Botones -->
                        <div class="flex justify-end gap-4 items-center mt-8 pt-6 border-t">
                            <div class="text-right">
                                <p class="text-gray-500 mb-2">Total:</p>
                                <p class="text-3xl font-bold text-gray-900" id="total-carrito">
                                    ${{ number_format($carrito->sum(fn($item) => $item->cantidad * $item->precio_unitario), 2) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 mt-6">
                            <a href="{{ route('catalogo') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Continuar comprando
                            </a>
                            @auth
                            <button type="button" 
                                    id="btn-confirmar"
                                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Confirmar Compra
                            </button>
                            @else
                            <a href="{{ route('login') }}" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Iniciar sesión para comprar</a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Aumentar cantidad
        document.querySelectorAll('.btn-aumentar').forEach(btn => {
            btn.addEventListener('click', async function() {
                const carritoId = this.dataset.carritoId;
                const nuevaCantidad = parseInt(this.dataset.cantidad) + 1;
                await actualizarCantidad(carritoId, nuevaCantidad);
            });
        });

        // Disminuir cantidad
        document.querySelectorAll('.btn-disminuir').forEach(btn => {
            btn.addEventListener('click', async function() {
                const carritoId = this.dataset.carritoId;
                let nuevaCantidad = parseInt(this.dataset.cantidad) - 1;
                if (nuevaCantidad < 1) nuevaCantidad = 1;
                await actualizarCantidad(carritoId, nuevaCantidad);
            });
        });

        // Eliminar item
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', async function() {
                if (!confirm('¿Estás seguro de que deseas eliminar este producto?')) return;
                const carritoId = this.dataset.carritoId;
                await eliminarItem(carritoId);
            });
        });

        // Confirmar compra: redirigir a checkout
        document.getElementById('btn-confirmar')?.addEventListener('click', function() {
            window.location.href = '{{ route("checkout.show") }}';
        });

        async function actualizarCantidad(carritoId, nuevaCantidad) {
            try {
                const response = await fetch(`/carrito/${carritoId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ cantidad: nuevaCantidad })
                });

                if (!response.ok) throw new Error('Error al actualizar cantidad');
                
                const data = await response.json();
                
                // Actualizar UI
                const cantidad = data.carrito.cantidad;
                const precio = data.carrito.precio_unitario;
                const subtotal = cantidad * precio;

                document.getElementById(`cantidad-${carritoId}`).textContent = cantidad;
                document.getElementById(`subtotal-${carritoId}`).textContent = '$' + subtotal.toFixed(2);
                document.querySelector(`[data-carrito-id="${carritoId}"].btn-aumentar`).dataset.cantidad = cantidad;
                document.querySelector(`[data-carrito-id="${carritoId}"].btn-disminuir`).dataset.cantidad = cantidad;

                actualizarTotal();
            } catch (error) {
                console.error('Error:', error);
                alert('Error al actualizar la cantidad');
            }
        }

        async function eliminarItem(carritoId) {
            try {
                const response = await fetch(`/carrito/${carritoId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (!response.ok) throw new Error('Error al eliminar');
                
                document.getElementById(`item-${carritoId}`).remove();
                
                const items = document.querySelectorAll('tbody tr').length;
                if (items === 0) {
                    location.reload();
                }
                
                actualizarTotal();
            } catch (error) {
                console.error('Error:', error);
                alert('Error al eliminar el producto');
            }
        }

        function actualizarTotal() {
            let total = 0;
            document.querySelectorAll('tbody tr').forEach(row => {
                const subtotalText = row.querySelector('[id^="subtotal-"]').textContent;
                const subtotal = parseFloat(subtotalText.replace('$', ''));
                total += subtotal;
            });
            document.getElementById('total-carrito').textContent = '$' + total.toFixed(2);
        }
    </script>
</x-app-layout>
