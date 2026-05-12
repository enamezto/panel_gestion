<aside id="sidebar" class="bg-gray-900 text-gray-100 flex flex-col transition-all duration-300 w-64">

    {{-- Logo / título --}}
    <div class="px-6 py-5 border-b border-gray-800 flex items-center justify-between min-h-[90px]">
        <div class="sidebar-text">
            <h1 class="text-xl font-bold text-white whitespace-nowrap">SDi Panel</h1>
            <p class="text-xs text-gray-400 mt-1 whitespace-nowrap">Gestión a3ERP</p>
        </div>
        <button id="sidebar-toggle" class="text-gray-400 hover:text-white flex-shrink-0">
            <x-heroicon-o-bars-3 class="w-5 h-5" />
        </button>
    </div>

    {{-- Menú de navegación --}}
    <nav class="flex-1 px-3 py-6 space-y-1">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="relative flex items-center min-h-[44px] px-3 py-2.5 rounded-lg text-sm font-medium
                  {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            @if(request()->routeIs('dashboard'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-blue-500 rounded-r"></span>
            @endif
            <x-heroicon-o-chart-bar class="w-5 h-5 flex-shrink-0" />
            <span class="ml-3 sidebar-text whitespace-nowrap">Dashboard</span>
        </a>

        {{-- Clientes --}}
        <a href="{{ route('clientes.index') }}"
            class="relative flex items-center min-h-[44px] px-3 py-2.5 rounded-lg text-sm font-medium
                {{ request()->routeIs('clientes.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            @if(request()->routeIs('clientes.*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-blue-500 rounded-r"></span>
            @endif
            <x-heroicon-o-users class="w-5 h-5 flex-shrink-0" />
            <span class="ml-3 sidebar-text whitespace-nowrap">Clientes</span>
        </a>

        {{-- Catálogo --}}
        <a href="{{ route('catalogo.index') }}"
            class="relative flex items-center min-h-[44px] px-3 py-2.5 rounded-lg text-sm font-medium
                {{ request()->routeIs('catalogo.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            @if(request()->routeIs('catalogo.*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-blue-500 rounded-r"></span>
            @endif
            <x-heroicon-o-cube class="w-5 h-5 flex-shrink-0" />
            <span class="ml-3 sidebar-text whitespace-nowrap">Catálogo</span>
        </a>

        {{-- Histórico --}}
        <a href="{{ route('historico.index') }}"
            class="relative flex items-center min-h-[44px] px-3 py-2.5 rounded-lg text-sm font-medium
                {{ request()->routeIs('historico.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            @if(request()->routeIs('historico.*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-blue-500 rounded-r"></span>
            @endif
            <x-heroicon-o-clipboard-document-list class="w-5 h-5 flex-shrink-0" />
            <span class="ml-3 sidebar-text whitespace-nowrap">Histórico</span>
        </a>

    </nav>

    {{-- Pie del sidebar --}}
    <div class="px-6 py-4 border-t border-gray-800 min-h-[49px]">
        <p class="text-xs text-gray-500 sidebar-text whitespace-nowrap">v1.0.0</p>
    </div>

</aside>

{{-- Script para colapsar/expandir el sidebar --}}
<script>
    document.getElementById('sidebar-toggle').addEventListener('click', function () {
        const sidebar = document.getElementById('sidebar');
        const texts = document.querySelectorAll('.sidebar-text');

        sidebar.classList.toggle('w-64');
        sidebar.classList.toggle('w-20');

        texts.forEach(text => text.classList.toggle('hidden'));
    });
</script>
