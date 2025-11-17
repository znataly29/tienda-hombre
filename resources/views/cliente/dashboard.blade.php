<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mi Panel</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-4">Hola, {{ $user->name }}</h3>
                <p class="text-gray-600">Resumen de tu cuenta</p>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 border rounded">
                        <h4 class="font-semibold">Compras recientes</h4>
                        <ul class="text-sm mt-2">
                            @foreach(\App\Models\Compra::where('usuario_id', $user->id)->latest()->take(5)->get() as $c)
                                <li>#{{ $c->id }} - ${{ number_format($c->monto_total,2) }} - {{ $c->created_at->format('d/m/Y') }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="p-4 border rounded">
                        <h4 class="font-semibold">Acciones</h4>
                        <div class="mt-2">
                            <a href="{{ route('carrito.index') }}" class="text-blue-600 hover:underline">Ver carrito</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>