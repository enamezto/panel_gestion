<header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">

    {{-- Título de la página actual --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-800">@yield('page_title', 'Dashboard')</h2>
    </div>

    {{-- Usuario y logout --}}
    <div class="flex items-center space-x-4">

        {{-- Nombre del usuario --}}
        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>

        {{-- Avatar con inicial --}}
        <div class="w-9 h-9 rounded-full bg-gray-700 text-white flex items-center justify-center text-sm font-medium">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>

        {{-- Botón de logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                Salir
            </button>
        </form>

    </div>

</header>
