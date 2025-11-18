<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('catalogo')" :active="request()->routeIs('catalogo')">
                        Catálogo
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            @auth
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <!-- Carrito Link + preview -->
                @php
                    if (auth()->check()) {
                        $carritoCount = \App\Models\Carrito::where('usuario_id', auth()->id())->sum('cantidad');
                        $previewItems = \App\Models\Carrito::with('producto')->where('usuario_id', auth()->id())->take(4)->get();
                    } else {
                        $sessionCart = session('cart', []);
                        $carritoCount = array_reduce($sessionCart, function ($c, $it) { return $c + ($it['cantidad'] ?? 0); }, 0);
                        $previewItems = array_slice($sessionCart, 0, 4);
                    }
                @endphp

                <div class="relative group">
                    <a href="{{ route('carrito.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span id="nav-carrito-count" class="ml-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full" style="display: {{ $carritoCount > 0 ? 'inline-flex' : 'none' }};">{{ $carritoCount }}</span>
                    </a>

                    <div class="hidden group-hover:block absolute right-0 mt-2 w-72 bg-white border rounded shadow-lg z-50">
                        <div class="p-3">
                            <h4 class="font-semibold mb-2">Carrito</h4>
                            @if(empty($previewItems) || count($previewItems) === 0)
                                <p class="text-sm text-gray-500">Tu carrito está vacío</p>
                            @else
                                <div class="space-y-2">
                                    @foreach($previewItems as $pi)
                                        @if(is_object($pi))
                                            @php $prod = $pi->producto; $qty = $pi->cantidad; @endphp
                                            <div class="flex justify-between items-center">
                                                <div class="text-sm">{{ $prod?->nombre ?? 'Producto' }} <span class="text-gray-400">x{{ $qty }}</span></div>
                                                <div class="text-sm font-semibold">${{ number_format(($pi->precio_unitario ?? ($prod?->precio ?? 0)) * $qty, 2) }}</div>
                                            </div>
                                        @else
                                            <div class="flex justify-between items-center">
                                                <div class="text-sm">{{ $pi['nombre'] ?? 'Producto' }} <span class="text-gray-400">x{{ $pi['cantidad'] ?? 1 }}</span></div>
                                                <div class="text-sm font-semibold">${{ number_format(($pi['precio_unitario'] ?? 0) * ($pi['cantidad'] ?? 1), 2) }}</div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="mt-3 text-right">
                                    <a href="{{ route('carrito.index') }}" class="inline-block text-sm text-blue-600 hover:underline">Ver carrito</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @php $rolName = auth()->user()->rol->nombre ?? null; @endphp
                        @if($rolName === 'admin')
                            <x-dropdown-link :href="route('admin.dashboard')">Panel Admin</x-dropdown-link>
                        @else
                            <x-dropdown-link :href="route('cliente.dashboard')">Mi Panel</x-dropdown-link>
                            <x-dropdown-link :href="route('cliente.historial')">Mis Compras</x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @endauth

            <!-- Login/Register Links -->
            @guest
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    {{ __('Log in') }}
                </a>
                <a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    {{ __('Register') }}
                </a>
            </div>
            @endguest

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('catalogo')" :active="request()->routeIs('catalogo')">
                    Catálogo
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            </div>        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('carrito.index')">
                    Carrito
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                @php $rolName = auth()->user()->rol->nombre ?? null; @endphp
                @if($rolName === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')">Panel Admin</x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('cliente.dashboard')">Mi Panel</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('cliente.historial')">Mis Compras</x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth

        <!-- Responsive Login/Register Links -->
        @guest
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="space-y-1">
                <x-responsive-nav-link :href="route('login')">
                    {{ __('Log in') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">
                    {{ __('Register') }}
                </x-responsive-nav-link>
            </div>
        </div>
        @endguest
    </div>
</nav>
