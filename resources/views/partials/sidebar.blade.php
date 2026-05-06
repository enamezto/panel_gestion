<aside class="w-64 bg-gray-900 text-gray-100 flex flex-col">

    {{-- Logo / título --}}
    <div class="px-6 py-5 border-b border-gray-800">
        <h1 class="text-xl font-bold text-white">SDi Panel</h1>
        <p class="text-xs text-gray-400 mt-1">Gestión a3ERP</p>
    </div>

    {{-- Menú de navegación --}}
    <nav class="flex-1 px-4 py-6 space-y-1">

        <a href="{{ route('dashboard') }}"
           class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium
                  {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            <span class="mr-3">📊</span>
            Dashboard
        </a>

        <a href="#"
           class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white">
            <span class="mr-3">👥</span>
            Clientes
        </a>

        <a href="#"
           class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white">
            <span class="mr-3">📦</span>
            Catálogo
        </a>

        <a href="#"
           class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white">
            <span class="mr-3">📋</span>
            Histórico
        </a>

    </nav>

    {{-- Pie del sidebar --}}
    <div class="px-6 py-4 border-t border-gray-800">
        <p class="text-xs text-gray-500">v1.0.0</p>
    </div>

</aside>
