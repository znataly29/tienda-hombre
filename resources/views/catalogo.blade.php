<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Catálogo</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form id="form-filtros" class="mb-4 flex gap-3" method="GET" action="{{ route('catalogo') }}">
                    <select id="f_categoria" name="categoria" class="border p-2">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c }}" @if(request('categoria') == $c) selected @endif>{{ $c }}</option>
                        @endforeach
                    </select>

                    <select id="f_talla" name="talla" class="border p-2">
                        <option value="">Todas las tallas</option>
                        @foreach($tallas as $t)
                            <option value="{{ $t }}" @if(request('talla') == $t) selected @endif>{{ $t }}</option>
                        @endforeach
                    </select>

                    <button id="filtrar" type="submit" class="bg-blue-600 text-white px-4 py-2">Filtrar</button>
                </form>

                <div id="productos" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($productos as $p)
                        <div class="bg-gray-50 p-4 shadow">
                            <h2 class="font-bold">{{ $p->nombre }}</h2>
                            <p>{{ $p->descripcion }}</p>
                            <p class="font-semibold">${{ $p->precio }}</p>
                            <button data-id="{{ $p->id }}" class="agregar-carrito bg-green-500 text-white px-3 py-1 mt-2">Agregar</button>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $productos->links() }}
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/catalogo.js') }}"></script>
</x-app-layout>
